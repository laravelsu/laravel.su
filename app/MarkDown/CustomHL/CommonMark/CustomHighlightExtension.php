<?php

declare(strict_types=1);

namespace App\MarkDown\CustomHL\CommonMark;

use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Extension\CommonMark\Node\Block\FencedCode;
use League\CommonMark\Extension\CommonMark\Node\Block\IndentedCode;
use League\CommonMark\Extension\CommonMark\Node\Inline\Code;
use League\CommonMark\Extension\ExtensionInterface;
use Tempest\Highlight\Highlighter;
use Tempest\Highlight\Themes\InlineTheme;
use Tempest\Highlight\CommonMark\CodeBlockRenderer;
use Tempest\Highlight\CommonMark\InlineCodeBlockRenderer;
use App\MarkDown\CustomHL\Languages\Php\PhpLanguage;
use App\MarkDown\CustomHL\Languages\Shell\ShellLanguage;
use App\MarkDown\CustomHL\Languages\Bash\BashLanguage;
use App\MarkDown\CustomHL\Languages\Ini\IniLanguage;
use App\MarkDown\CustomHL\Languages\Blade\BladeLanguage;
use App\MarkDown\CustomHL\Languages\Vue\VueLanguage;
use App\MarkDown\CustomHL\Languages\Nginx\NginxLanguage;
use App\MarkDown\CustomHL\Languages\JavaScript\JavaScriptLanguage;
//use App\MarkDown\CustomHL\Languages\Html\HtmlLanguage;
use App\MarkDown\CustomHL\Languages\Xml\XmlLanguage;

final class CustomHighlightExtension implements ExtensionInterface
{
    public function __construct(
        private ?Highlighter $highlighter = new Highlighter(new InlineTheme(__DIR__ . '/../style.css')),
    ) {
        $this->highlighter->addLanguage(new PhpLanguage());
        $this->highlighter->addLanguage(new ShellLanguage());
        $this->highlighter->addLanguage(new BashLanguage());
        $this->highlighter->addLanguage(new IniLanguage());
        $this->highlighter->addLanguage(new BladeLanguage());
        $this->highlighter->addLanguage(new VueLanguage());
        $this->highlighter->addLanguage(new NginxLanguage());
        $this->highlighter->addLanguage(new JavaScriptLanguage());
//        $this->highlighter->addLanguage(new HtmlLanguage());
        $this->highlighter->addLanguage(new XmlLanguage());
    }

    public function register(EnvironmentBuilderInterface $environment): void
    {
        $environment
            ->addRenderer(FencedCode::class, new CodeBlockRenderer($this->highlighter), 10)
            ->addRenderer(IndentedCode::class, new IndentedCodeBlockRenderer($this->highlighter), 10)
            //->addRenderer(Code::class, new InlineCodeBlockRenderer($this->highlighter), 10)
        ;
    }
}
