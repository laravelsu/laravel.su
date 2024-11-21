<?php

declare(strict_types=1);

namespace App\MarkDown\CustomHL\Languages\Php\Patterns;

use Tempest\Highlight\IsPattern;
use Tempest\Highlight\Pattern;
use Tempest\Highlight\PatternTest;
use Tempest\Highlight\Tokens\TokenType;
use Tempest\Highlight\Tokens\DynamicTokenType;

#[PatternTest(input: 'enum Foo: string', output: 'string')]
#[PatternTest(input: 'enum Foo: int', output: 'int')]
final readonly class EnumBackedTypePattern implements Pattern
{
    use IsPattern;

    public function getPattern(): string
    {
        return 'enum [\w]+\:(\s)*(?<match>int|string)';
    }

    public function getTokenType(): TokenType
    {
        return new DynamicTokenType('hl-php-keyword');
    }
}
