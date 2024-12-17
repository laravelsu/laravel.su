<?php

declare(strict_types=1);

namespace App\MarkDown\CustomHL\Languages\ExtendedCss\Patterns;

use Tempest\Highlight\IsPattern;
use Tempest\Highlight\Pattern;
use Tempest\Highlight\Tokens\TokenType;
use Tempest\Highlight\Tokens\DynamicTokenType;

final readonly class CssTailwindPattern implements Pattern
{
    use IsPattern;

    public function getPattern(): string
    {
        return '(?<match>\@tailwind)';
    }

    public function getTokenType(): TokenType
    {
        return new DynamicTokenType('hl-keyword');
    }
}
