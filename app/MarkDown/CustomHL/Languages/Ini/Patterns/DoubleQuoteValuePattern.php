<?php

declare(strict_types=1);

namespace App\MarkDown\CustomHL\Languages\Ini\Patterns;

use Tempest\Highlight\IsPattern;
use Tempest\Highlight\Pattern;
use Tempest\Highlight\Tokens\TokenType;
//use Tempest\Highlight\Tokens\DynamicTokenType;
use App\MarkDown\CustomHL\Tokens\CanNotContainTokenType;

final readonly class DoubleQuoteValuePattern implements Pattern
{
    use IsPattern;

    public function getPattern(): string
    {
        return '(?<match>"(.|\n)*?")';
    }

    public function getTokenType(): TokenType
    {
        return new CanNotContainTokenType('hl-ini-value');
    }
}
