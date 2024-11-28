<?php

declare(strict_types=1);

namespace App\MarkDown\CustomHL\Tokens;

use Tempest\Highlight\Tokens\TokenType;

final readonly class QuotedValueTokenType implements TokenType
{
    public function __construct(
            private string $value = 'hl-json-value'
    ) {}

    public function getValue(): string
    {
        return $this->value;
    }

    public function canContain(TokenType $other): bool
    {
        return false;
    }
}
