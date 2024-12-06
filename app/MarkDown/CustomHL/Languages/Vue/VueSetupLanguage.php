<?php

declare(strict_types=1);

namespace App\MarkDown\CustomHL\Languages\Vue;

use App\MarkDown\CustomHL\Languages\CustomBase\CustomBaseLanguage;
use App\MarkDown\CustomHL\Languages\Vue\Patterns\VueKeywordPattern;
use App\MarkDown\CustomHL\Languages\Vue\Patterns\SingleQuoteValuePattern;
use App\MarkDown\CustomHL\Languages\Vue\Patterns\FunctionCallPattern;
use App\MarkDown\CustomHL\Languages\Vue\Patterns\ConstNamePattern;
use App\MarkDown\CustomHL\Languages\Vue\Patterns\OperatorPattern;

class VueSetupLanguage extends CustomBaseLanguage
{
    public function getName(): string
    {
        return 'vuesetup';
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
            new VueKeywordPattern('import'),
            new VueKeywordPattern('from'),
            new VueKeywordPattern('const'),
            
            new FunctionCallPattern(),
            new ConstNamePattern(),
            
            new OperatorPattern('(<|=>|>|=|\*)'),
            
            new SingleQuoteValuePattern(),

        ];
    }
}
