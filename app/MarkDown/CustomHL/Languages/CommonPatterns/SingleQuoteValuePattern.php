<?php

declare(strict_types=1);

namespace App\MarkDown\CustomHL\Languages\CommonPatterns;

use Tempest\Highlight\IsPattern;
use Tempest\Highlight\Pattern;
use Tempest\Highlight\Tokens\TokenType;
use Tempest\Highlight\Tokens\DynamicTokenType;

final readonly class SingleQuoteValuePattern implements Pattern
{
    use IsPattern;

    public function __construct(
        private string $tokenType = 'hl-value',
    ) {
    }

    public function getPattern(): string
    {
        return '(?<match>\'.*\'?)';
    }

    public function getTokenType(): TokenType
    {
        return new DynamicTokenType($this->tokenType);
    }
}
