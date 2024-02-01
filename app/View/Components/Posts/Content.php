<?php

namespace App\View\Components\Posts;

use App\View\Modifications\BladeComponentModifier;
use App\View\Modifications\ImageAltModifier;
use App\View\Modifications\BlockquoteColorModifier;
use App\View\Modifications\ResponsiveTableModifier;
use App\View\Modifications\TestModifier;
use App\View\Modifications\TypografModifier;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Pipeline\Pipeline;
use Illuminate\View\Component;

class Content extends Component implements Htmlable
{
    /**
     * @var string
     */
    protected $content;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(string $content)
    {
        $this->content = $content;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @throws \DOMException
     *
     * @return \App\View\Components\DocsContent
     */
    public function render()
    {
        return $this;
    }

    /**
     * @return string
     */
    public function toHtml(): string
    {
        $content = \Illuminate\Support\Str::of($this->content)
            ->stripTags(['x-github', 'x-youtube', 'code'])
            ->markdown([
                'allow_unsafe_links' => false,
                //'html_input'         => 'escape',
                'max_nesting_level'  => 20,
            ])
            ->toString();


        return app(Pipeline::class)
            ->send($content)
            ->through([
                TestModifier::class,
                BlockquoteColorModifier::class, // Применяет цвет к блокам цитат (Например предупреждение)
                ResponsiveTableModifier::class, // Добавляет к таблице класс table-responsive
                BladeComponentModifier::class, // Применяет компоненты blade
                ImageAltModifier::class, // Добавляет alt к картинкам
                TypografModifier::class, // Применяет типограф к тексту
            ])
            ->thenReturn();
    }
}