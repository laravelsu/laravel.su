<?php

declare(strict_types=1);

namespace App\MarkDown\CustomHL\Languages\CustomBase\Injections;

use Tempest\Highlight\Before;
use Tempest\Highlight\Highlighter;
use Tempest\Highlight\Injection;
use Tempest\Highlight\ParsedInjection;
use Tempest\Highlight\Escape;

#[Before]
final readonly class DeletionTL2Injection implements Injection
{
    public function parse(string $content, Highlighter $highlighter): ParsedInjection
    {
        preg_match_all('/(?<match>(.)*\<\!\-\- \[tl\! remove\] \-\-\>)/', $content, $matches, PREG_OFFSET_CAPTURE);

        foreach ($matches['match'] as $match) {
            $matchedContent = $match[0];

            $open = '{-';
            $close = '-}';  //'          -}';

            $parsedMatchedContent = $open . str_replace(  // Escape::INJECTION_TOKEN . 
                '<!-- [tl! remove] -->',
                $close,  // Escape::INJECTION_TOKEN . 
                $matchedContent,
            );

            $content = str_replace($matchedContent, $parsedMatchedContent, $content);
        }

        return new ParsedInjection($content);
    }
}
