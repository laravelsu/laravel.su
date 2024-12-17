<?php

declare(strict_types=1);

namespace App\MarkDown\CustomHL\Languages\JavaScript\Injections;

use Tempest\Highlight\After;
use Tempest\Highlight\Highlighter;
use Tempest\Highlight\Injection;
use Tempest\Highlight\IsInjection;

#[After]
final readonly class JsHTMLInjection implements Injection
{
    use IsInjection;

    public function getPattern(): string
    {
        return "/(?<match>(.|\n)*)/";
    }

    public function parseContent(string $content, Highlighter $highlighter): string
    {
        return $highlighter->parse($content, 'html'); 
    }
}
