<?php

declare(strict_types=1);

namespace App\MarkDown\CustomHL\Languages\Shell\Patterns;

use Tempest\Highlight\IsPattern;
use Tempest\Highlight\Pattern;
use Tempest\Highlight\Tokens\TokenType;
use Tempest\Highlight\Tokens\DynamicTokenType;

final readonly class DoubleQuoteValuePattern implements Pattern
{
    use IsPattern;

    public function getPattern(): string
    {
        return '(?<match>".*?")';
    }

    public function getTokenType(): TokenType
    {
        return new DynamicTokenType('hl-shell-value');
    }
}
