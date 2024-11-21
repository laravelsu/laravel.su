<?php

declare(strict_types=1);

namespace App\MarkDown\CustomHL;

use Tempest\Highlight\Highlighter;
use Tempest\Highlight\ParsedInjection;

trait IsInjection
{
    abstract public function getPattern(): string;

    abstract public function parseContent(string $content, Highlighter $highlighter): string;

    private function clearContent(string $input):string
    {
        return preg_replace(
            ['/❿(.*?)❿/', '/❷span(.*?)span❸/'],
            '',
            $input,
        );
    }

    public function parse(string $content, Highlighter $highlighter): ParsedInjection
    {
        $pattern = $this->getPattern();

        if (! str_starts_with($pattern, '/')) {
            $pattern = "/{$pattern}/";
        }

        $cc = $this->clearContent($content);
        $result = preg_replace_callback(
            pattern: $pattern,
            callback: function ($matches) use ($highlighter) {
                $content = $matches['match'] ?? '';

                if (! $content) {
                    return $matches[0];
                }

                return str_replace(
                    search: $content,
                    replace: $this->parseContent($content, $highlighter),
                    subject: $matches[0],
                );
            },
            subject: $cc,  //$content,
        );

        return new ParsedInjection($result ?? $content);
    }
}
