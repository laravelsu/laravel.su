<?php

declare(strict_types=1);

namespace App\MarkDown\CustomHL\Languages\CommonPatterns;

use Tempest\Highlight\IsPattern;
use Tempest\Highlight\Pattern;
use Tempest\Highlight\Tokens\TokenType;
use Tempest\Highlight\Tokens\DynamicTokenType;
use App\MarkDown\CustomHL\Tokens\CanNotContainTokenType;

final class SingleQuoteValuePattern implements Pattern
{
    use IsPattern;

    private bool $canNotContain = false;

    public function __construct(
        private string $tokenType = 'hl-value',
    ) {
    }

    public function canNotContain(): self
    {
        $this->canNotContain = true;

        return $this;
    }

    public function getPattern(): string
    {
        return '(?<match>\'.*\'?)';
    }

    public function getTokenType(): TokenType
    {
        if ($this->canNotContain)
        {
            return new CanNotContainTokenType($this->tokenType);
        }
        else
        {
            return new DynamicTokenType($this->tokenType);
        }
    }
}
