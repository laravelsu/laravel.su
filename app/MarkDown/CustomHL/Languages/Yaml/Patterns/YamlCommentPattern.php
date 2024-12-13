<?php

declare(strict_types=1);

namespace App\MarkDown\CustomHL\Languages\Yaml\Patterns;

use Tempest\Highlight\IsPattern;
use Tempest\Highlight\Pattern;
use Tempest\Highlight\PatternTest;
use Tempest\Highlight\Tokens\TokenType;
use Tempest\Highlight\Tokens\DynamicTokenType;

#[PatternTest(input: '# comment', output: '# comment')]
final readonly class YamlCommentPattern implements Pattern
{
    use IsPattern;

    public function getPattern(): string
    {
        return '(?<match>\#(.)*)';
    }

    public function getTokenType(): TokenType
    {
        return new DynamicTokenType('hl-yaml-comment');
    }
}
