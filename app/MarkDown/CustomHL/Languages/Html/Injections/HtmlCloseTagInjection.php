<?php

declare(strict_types=1);

namespace App\MarkDown\CustomHL\Languages\Html\Injections;

use Tempest\Highlight\Highlighter;
use Tempest\Highlight\IsInjection;
use Tempest\Highlight\Injection;
use Tempest\Highlight\Escape;
use Tempest\Highlight\Tokens\DynamicTokenType;
use App\MarkDown\CustomHL\Languages\Html\HtmlConst;

final readonly class HtmlCloseTagInjection implements Injection
{
    use IsInjection;

    public function getPattern(): string
    {
        return '<\/(?!x\-)(?<match>[\w\-]+)';
    }

    public function parseContent(string $content, Highlighter $highlighter): string
    {
        $keywords = HtmlConst::TAGS;
        //dd($content);
        $theme = $highlighter->getTheme(); 
        
        //foreach ($types as $type) {
            $token = (in_array($content, $keywords)) ? 'hl-xml-tag' : 'hl-xml-attr';
            $content = preg_replace(
                '/\b' . $content . '[\b]*/',
                Escape::tokens($theme->before(new DynamicTokenType($token)))
                . $content
                . Escape::tokens($theme->after(new DynamicTokenType($token))),
                $content,
            );
        //} 

        return $content;
    }
}
