<?php

declare(strict_types=1);

namespace App\MarkDown\CustomHL\Languages\Shell;

//use App\MarkDown\CustomHL\Languages\Bash\BashLanguage;
//use App\MarkDown\CustomHL\Languages\Shell\Patterns\ShellWordPattern;
use App\MarkDown\CustomHL\Languages\CustomBase\CustomBaseLanguage;
use App\MarkDown\CustomHL\Languages\Shell\Patterns\ShellKeyPattern;
use App\MarkDown\CustomHL\Languages\Shell\Patterns\DoubleQuoteValuePattern;
use App\MarkDown\CustomHL\Languages\Shell\Patterns\QuoteValuePattern;
use App\MarkDown\CustomHL\Languages\Shell\Patterns\DelimeterPattern;
use App\MarkDown\CustomHL\Languages\Shell\Patterns\SinglelineCommentPattern;

class ShellLanguage extends CustomBaseLanguage
{
    public function getName(): string
    {
        return 'shell';
    }

    public function getAliases(): array
    {
        return [
            'sh'
        ];
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
            new ShellKeyPattern(),
            new DoubleQuoteValuePattern(),
            new QuoteValuePattern(),
            new DelimeterPattern('(cd|\|)'),
            new SinglelineCommentPattern(),
        ];
    }
}