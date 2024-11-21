<?php

declare(strict_types=1);

namespace App\MarkDown\CustomHL\Languages\Vue\Patterns;

use Tempest\Highlight\IsPattern;
use Tempest\Highlight\Pattern;
use Tempest\Highlight\Tokens\TokenType;
use Tempest\Highlight\Tokens\DynamicTokenType;

final readonly class FunctionCallPattern implements Pattern
{
    use IsPattern;

    public function getPattern(): string
    {
        return '\b(?<match>[a-z][\w]+)\(';
    }

    public function getTokenType(): TokenType
    {
        return new DynamicTokenType('hl-vue-function');
    }
}
