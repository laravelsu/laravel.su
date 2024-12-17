<?php

declare(strict_types=1);

namespace App\MarkDown\CustomHL\Languages\ExtendedCss;

use Tempest\Highlight\Languages\Css\CssLanguage;
use App\MarkDown\CustomHL\Languages\ExtendedCss\Patterns\CssConfigPattern;
use App\MarkDown\CustomHL\Languages\ExtendedCss\Patterns\CssTailwindPattern;

class ExtendedCssLanguage extends CssLanguage
{
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
            new CssConfigPattern(),
            new CssTailwindPattern(),
        ];
    }
}
