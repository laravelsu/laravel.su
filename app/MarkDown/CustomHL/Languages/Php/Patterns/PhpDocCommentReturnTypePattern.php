<?php

declare(strict_types=1);

namespace App\Tempest\Highlight\Languages\Php\Patterns;

use App\Tempest\Highlight\IsPattern;
use App\Tempest\Highlight\Pattern;
use App\Tempest\Highlight\PatternTest;
use App\Tempest\Highlight\Tokens\TokenTypeEnum;

#[PatternTest(input: '@return array|string', output: 'array|string')]
#[PatternTest(input: '@return \\Foo', output: '\\Foo')]
final readonly class PhpDocCommentReturnTypePattern implements Pattern
{
    use IsPattern;

    public function getPattern(): string
    {
        return '\@return(\s)+(?<match>.*?)(\*\/|$|\R)';
    }

    public function getTokenType(): TokenTypeEnum
    {
        return TokenTypeEnum::TYPE;
    }
}
