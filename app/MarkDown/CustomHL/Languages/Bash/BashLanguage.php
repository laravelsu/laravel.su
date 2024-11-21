<?php

declare(strict_types=1);

namespace App\MarkDown\CustomHL\Languages\Bash;

use App\MarkDown\CustomHL\Languages\Shell\ShellLanguage;
//use App\MarkDown\CustomHL\Languages\Bash\Patterns\BashKeyPattern;
//use App\MarkDown\CustomHL\Languages\Bash\Patterns\DoubleQuoteValuePattern;

class BashLanguage extends ShellLanguage
{
    public function getName(): string
    {
        return 'bash';
    }

    public function getInjections(): array
    {
        return [
            ...parent::getInjections(),
        ];
    }

    public function getPatterns(): array
    {
        return [
            ...parent::getPatterns(),
            //new BashKeyPattern(),
            //new DoubleQuoteValuePattern(),
        ];
    }
}