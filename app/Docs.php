<?php

namespace App;

use App\Models\Document;
use App\Models\DocumentationSection;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Unfenced\UnfencedExtension;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Yaml\Yaml;

class Docs
{
    /**
     * Default document of Laravel documentation
     */
    public const DEFAULT_DOCUMENT = 'installation';

    /**
     * Default version of Laravel documentation
     */
    public const DEFAULT_VERSION = '12.x';

    /**
     * Array of supported versions
     */
    public const SUPPORT_VERSIONS = [
        '12.x',
        '11.x',
        '10.x',
        '8.x',
        '5.4',
        '4.2',
    ];

    /**
     * @var string The version of the documentation.
     */
    public $version;

    /**
     * @var string The path to the Markdown file.
     */
    protected $path;

    /**
     * @var array The array of variables extracted from the Markdown file's front matter.
     */
    protected array $variables = [];

    /**
     * @var string The file name.
     */
    public string $file;

    /**
     * @var Document
     */
    protected $model;

    /**
     * @var string The link name.
     */
    public string $name;

    /**
     * Create a new Docs instance.
     *
     * @param string $version The version of the Laravel documentation.
     * @param string $name    The link name.
     */
    public function __construct(string $version, string $name)
    {
        $this->name = $name;
        $this->file = $name.'.md';
        $this->version = $version;
        $this->path = "/$version/$this->file";
    }

    /**
     * @return string
     */
    public function raw(): string
    {
        return once(function () {
            $raw = Storage::disk('docs')->get($this->path);

            // Abort the request if the page doesn't exist
            abort_if(
                $raw === null,
                redirect(status: 300)->route('docs', ['version' => $this->version, 'page' => self::DEFAULT_DOCUMENT])
            );

            return $raw;
        });
    }

    /**
     * @param string|null $key
     *
     * @return mixed
     */
    public function variables(?string $key = null): mixed
    {
        return once(function () use ($key) {
            $variables = [];
            $yaml = Str::of($this->raw())->betweenFirst('---', '---');

            try {
                $variables = Yaml::parse($yaml);
            } catch (\Throwable) {

            }

            return Arr::get($variables, $key);
        });
    }

    /**
     * @return string|null
     */
    public function content(): ?string
    {
        return once(function () {
            return Str::of($this->raw())
                ->replace('{{version}}', $this->version)
                ->after('---')
                ->after('---')
                ->markdown(extensions: [
                    new \Laravelsu\Highlight\CommonMark\HighlightExtension,
                    new UnfencedExtension,
                ])
                ->toString();
        });
    }

    /**
     * Get the rendered view of a documentation page.
     *
     * @param string $view The view name.
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     *
     * @return \Illuminate\Contracts\View\View The rendered view of the documentation page.
     */
    public function view(string $view)
    {
        $data = Cache::flexible(
            'doc-file-view-data'.$this->path,
            [now()->addMinutes(30), now()->addHours(2)],
            fn () => collect()->merge($this->variables())->merge([
                'docs'    => $this,
                'content' => $this->content(),
                'edit'    => $this->getEditUrl(),
            ]));

        return view($view, $data);
    }

    /**
     * Get the menu array for the documentation index page.
     *
     * @return array The menu array.
     */
    public function getMenu(): array
    {
        return Cache::flexible(
            'doc-navigation-'.$this->version,
            [now()->addMinutes(30), now()->addHours(2)],
            function () {
                $page = Storage::disk('docs')->get($this->version.'/documentation.md');

                $html = Str::of($page)
                    ->after('---')
                    ->after('---')
                    ->replace('{{version}}', $this->version)
                    ->markdown()
                    ->toString();

                return $this->docsToArray($html);
            });
    }

    /**
     * Get the title of the documentation page.
     *
     * @return string|null The title of the documentation page.
     */
    public function title(): ?string
    {
        $crawler = new Crawler;
        $crawler->addHtmlContent($this->content());

        $title = $crawler->filterXPath('//h1');

        return count($title) ? $title->text() : null;
    }

    /**
     * @return string|null
     */
    public function description(): ?string
    {
        $crawler = new Crawler;
        $crawler->addHtmlContent($this->content());

        $firstParagraph = collect($crawler->filter('p'))
            ->take(5)
            ->filter(fn (\DOMElement $paragraph) => ! empty($paragraph->textContent))
            ->first();

        return $firstParagraph !== null
            ? Str::of($firstParagraph->textContent)->words()->toString()
            : null;
    }

    /**
     * Convert the HTML string to an array.
     *
     * @param string $html The HTML string.
     *
     * @return array The converted array.
     */
    public function docsToArray(string $html): array
    {
        $crawler = new Crawler;
        $crawler->addContent($html);

        $menu = [];

        $crawler->filter('body > ul > li')->each(function (Crawler $node) use (&$menu) {
            $subList = $node->filter('ul > li')->each(fn (Crawler $subNode) => [
                'title' => $subNode->filter('a')->text(),
                'href'  => url($subNode->filter('a')->attr('href')),
            ]);

            if (empty($subList)) {
                $menu[] = [
                    'title' => $node->filter('a')->text(),
                    'href'  => url($node->filter('a')->attr('href')),
                ];
            } else {
                $menu[] = [
                    'title' => $node->filter('h2')->text(),
                    'list'  => $subList,
                ];
            }
        });

        return $menu;
    }

    /**
     * Get all the versions of the documentation.
     *
     * @param string $version The version of the Laravel documentation.
     *
     * @return \Illuminate\Support\Collection A collection of Docs instances.
     */
    public static function every(string $version): Collection
    {
        $files = Storage::disk('docs')->allFiles($version);

        return collect($files)
            ->filter(fn (string $path) => Str::of($path)->endsWith('.md'))
            ->filter(fn (string $path) => ! Str::of($path)->endsWith(['readme.md', 'license.md']))
            ->map(fn (string $path) => Str::of($path)->after($version.'/')->before('.md'))
            ->map(fn (string $path) => new static($version, $path));
    }

    /**
     * Fetch the number of commits behind the current commit.
     *
     * @return int The number of commits behind.
     */
    public function fetchBehind(): int
    {
        throw_if($this->variables('git') === null, new Exception("The document {$this->path} is missing a Git hash"));

        $response = $this->fetchGitHubDiff();

        return $response
            ->takeUntil(fn ($commit) => $commit['sha'] === $this->variables('git'))
            ->count();
    }

    public function fetchLastCommit(): string
    {
        throw_if($this->variables('git') === null, new Exception("The document {$this->path} is missing a Git hash"));

        $response = $this->fetchGitHubDiff();

        return $response->pluck('sha')->first();
    }

    /**
     * @param string|null $key
     *
     * @return \Illuminate\Support\Collection
     */
    private function fetchGitHubDiff(?string $key = null): Collection
    {
        $hash = sha1($this->content());

        return Cache::flexible(
            "docs-diff-$this->version-$this->file-$hash",
            [now()->addMinutes(30), now()->addHours(2)],
            fn () => Http::withBasicAuth('token', config('services.github.token'))
                ->get("https://api.github.com/repos/laravel/docs/commits?sha={$this->version}&path={$this->file}")
                ->collect($key)
        );
    }

    /**
     * Get the URL to edit the page on GitHub.
     *
     * @return string The URL to edit the page on GitHub.
     */
    public function getEditUrl(): string
    {
        return "https://github.com/laravelRus/docs/edit/$this->path";
    }

    /**
     * Get the URL to the original Laravel documentation page.
     *
     * @return string The URL to the original Laravel documentation page.
     */
    public function getOriginalUrl(): string
    {
        $urlPart = Str::of($this->path)->remove('.md');

        return "https://laravel.com/docs$urlPart";
    }

    /**
     * @param string $version
     * @param string $hash
     *
     * @return string
     */
    public static function compareLink(string $version, string $hash): string
    {
        $compactHash = Str::of($hash)->limit(7, '')->toString();

        return "https://github.com/laravel/docs/compare/$compactHash..$version";
    }

    /**
     * Get the Document model for the documentation page.
     *
     * @return \App\Models\Document The Document model.
     */
    public function getModel(): Document
    {
        if ($this->model === null) {
            $this->model = Document::firstOrNew([
                'version' => $this->version,
                'file'    => $this->file,
            ]);
        }

        return $this->model;
    }

    /**
     * @return int|null
     */
    public function behind(): ?int
    {
        return $this->getModel()->behind;
    }

    /**
     * @return bool
     */
    public function isOlderVersion(): bool
    {
        return version_compare($this->version, static::DEFAULT_VERSION) < 0;
    }

    /**
     * Update the Document model with the latest information.
     *
     * @return void
     */
    public function update()
    {
        $this->getModel()->fill([
            'behind'         => $this->fetchBehind(),
            'last_commit'    => $this->fetchLastCommit(),
            'current_commit' => $this->variables('git'),
        ])->save();

        $this->updateSections();
    }

    /**
     * Разбивает markdown файл на разделы по заголовкам.
     *
     * @return Collection<array-key, array> Массив разделов с заголовками и содержимым
     */
    public function getSections(): Collection
    {
        $content = Str::of($this->content());

        // Разбиваем HTML содержимое на разделы по заголовкам
        preg_match_all('/<h(\d)>(.+)<\/h\d>(.*)/sU', $content->toString(), $matches, PREG_SET_ORDER);

        // Массив для хранения разделов
        $sections = collect();
        $titlePage = $this->title();

        foreach ($matches as $index => $match) {
            $tag = $match[0];
            $level = (int) $match[1];
            $sectionTitle = $match[2];

            if ($level === 1) {
                $titlePage = $sectionTitle;
            }

            $sectionContent = $content->after($tag);

            // Если есть следующий заголовок - обрезаем контент до него
            if (isset($matches[$index + 1])) {
                $sectionContent = $sectionContent->before($matches[$index + 1][0]);
            }

            $sections->push([
                'title_page' => $titlePage,
                'title'      => $sectionTitle,
                'slug'       => Str::of($sectionTitle)->slug()->toString(),
                'content'    => $sectionContent,
                'file'       => $this->file,
                'version'    => $this->version,
                'id'         => Str::uuid(),
                'level'      => $level,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return $sections;
    }

    /**
     * @return void
     */
    public function updateSections()
    {
        if ($this->file === 'documentation.md') {
            return;
        }

        DocumentationSection::where('file', $this->file)
            ->where('version', $this->version)
            ->delete();

        DocumentationSection::insert($this->getSections()->toArray());
    }
}
