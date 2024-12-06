<?php

declare(strict_types=1);

namespace App\MarkDown\CustomHL\Languages\Shell\Patterns;

use Tempest\Highlight\IsPattern;
use Tempest\Highlight\Pattern;
use Tempest\Highlight\Tokens\TokenType;
use Tempest\Highlight\Tokens\DynamicTokenType;

class DelimeterPattern implements Pattern {
    use IsPattern;

    public function __construct(
            private string $delimeter,
            private string $tokenType = 'hl-shell-delimeter'
    ) {
    }

    public function getPattern(): string
    {
        return "/(\s)*(?<match>{$this->delimeter})(\s)*/";
    }

    public function getTokenType(): TokenType
    {
        return new DynamicTokenType($this->tokenType);
    }

}
