<?php

declare(strict_types=1);

namespace App\MarkDown\CustomHL\Languages\Html\Injections;

use Tempest\Highlight\Highlighter;
use Tempest\Highlight\Injection;
use Tempest\Highlight\IsInjection;

final readonly class JavaScriptInHtmlInjection implements Injection
{
    use IsInjection;

    public function getPattern(): string
    {
        return '<script>(?<match>(.|\n|\r)*)<\/script>';
    }

    public function parseContent(string $content, Highlighter $highlighter): string
    {
        return $highlighter->parse($content, 'js');
    }
}
