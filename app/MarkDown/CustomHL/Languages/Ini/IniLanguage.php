<?php

declare(strict_types=1);

namespace App\MarkDown\CustomHL\Languages\Ini;

use App\MarkDown\CustomHL\Languages\CustomBase\CustomBaseLanguage;
use App\MarkDown\CustomHL\Languages\Ini\Patterns\ConstantPattern;
use App\MarkDown\CustomHL\Languages\Ini\Patterns\DoubleQuoteValuePattern;

class IniLanguage extends CustomBaseLanguage
{
    public function getName(): string
    {
        return 'ini';
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
            new ConstantPattern(),
            new DoubleQuoteValuePattern(),
        ];
    }
}