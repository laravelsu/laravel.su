<?php

declare(strict_types=1);

namespace App\MarkDown\CustomHL\Languages\Blade\Injections;

use Tempest\Highlight\Highlighter;
use Tempest\Highlight\Injection;
use Tempest\Highlight\IsInjection;
use Tempest\Highlight\Escape;

final readonly class BladeRawEchoInjection implements Injection
{
    use IsInjection;

    public function getPattern(): string
    {
        return '({!!)(?<match>.*)(!!})';
    }

    public function parseContent(string $content, Highlighter $highlighter): string
    {
        $clear_content = Escape::terminal($content);
        return $highlighter->parse($clear_content, 'php');
    }
}
