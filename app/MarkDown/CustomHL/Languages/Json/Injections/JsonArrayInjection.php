<?php

declare(strict_types=1);

namespace App\MarkDown\CustomHL\Languages\Json\Injections;

use Tempest\Highlight\Highlighter;
use Tempest\Highlight\Injection;
use Tempest\Highlight\IsInjection;
use Tempest\Highlight\Escape;
use Tempest\Highlight\Tokens\DynamicTokenType;

final readonly class JsonArrayInjection implements Injection
{
    use IsInjection;

    public function getPattern(): string
    {
        return '\[(?<match>(.|\n)*?)\]';
    }

    public function parseContent(string $content, Highlighter $highlighter): string
    {
        preg_match_all('/\"(?<match>.*?)\"/', $content, $match, PREG_OFFSET_CAPTURE);
        
        if (! $match) {
            return $content;
        }

        $theme = $highlighter->getTheme();

        foreach($match['match'] as $val) {
            $content = preg_replace(
                '/\b' . $val[0] . '[\b]*/',
                Escape::tokens($theme->before(new DynamicTokenType('hl-json-value')))
                . $val[0]
                . Escape::tokens($theme->after(new DynamicTokenType('hl-json-value'))),
                $content,
            );
        }

        return $content;
    }
}
