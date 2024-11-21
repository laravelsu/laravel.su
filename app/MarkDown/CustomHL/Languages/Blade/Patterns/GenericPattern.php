<?php

declare(strict_types=1);

namespace App\MarkDown\CustomHL\Languages\Blade\Patterns;

use Tempest\Highlight\IsPattern;
use Tempest\Highlight\Pattern;
use Tempest\Highlight\Tokens\TokenType;
use Tempest\Highlight\Tokens\DynamicTokenType;

final readonly class GenericPattern implements Pattern
{
    use IsPattern;

    public function __construct(
        private string $pattern,
        private string $tokenType,
    ) {
    }

    public function getPattern(): string
    {
        return $this->pattern;
    }

    public function getTokenType(): TokenType
    {
        return new DynamicTokenType($this->tokenType);
    }
}
