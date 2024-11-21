<?php

declare(strict_types=1);

namespace App\MarkDown\CustomHL\Languages\Php\Patterns;

use Tempest\Highlight\IsPattern;
use Tempest\Highlight\Pattern;
use Tempest\Highlight\Tokens\TokenType;
use Tempest\Highlight\Tokens\DynamicTokenType;

final class OperatorPattern implements Pattern
{
    use IsPattern;

    public function __construct(private string $operator)
    {
    }

    public function getPattern(): string
    {
        return "/\s(?<!\\$)(?<match>{$this->operator})(\s|\()/";
    }

    public function getTokenType(): TokenType
    {
        return new DynamicTokenType('hl-php-operator');
    }
}
