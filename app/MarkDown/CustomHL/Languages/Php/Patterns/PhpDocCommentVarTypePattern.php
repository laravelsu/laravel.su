<?php

declare(strict_types=1);

namespace App\MarkDown\CustomHL\Languages\Php\Patterns;

use Tempest\Highlight\IsPattern;
use Tempest\Highlight\Pattern;
use Tempest\Highlight\Tokens\TokenType;
use Tempest\Highlight\Tokens\DynamicTokenType;

final readonly class PhpDocCommentVarTypePattern implements Pattern
{
    use IsPattern;

    public function getPattern(): string
    {
        return '\@var(\s)+(?<match>.*?)( \$|\*|$|\n)';
    }

    public function getTokenType(): TokenType
    {
        return new DynamicTokenType('hl-php-type');
    }
}
