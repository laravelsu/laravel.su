<?php

declare(strict_types=1);

namespace App\MarkDown\CustomHL\Languages\Json;

use App\MarkDown\CustomHL\Languages\CustomBase\CustomBaseLanguage;
//use Tempest\Highlight\Languages\Json\Patterns\JsonAccoladesPattern;
//use Tempest\Highlight\Languages\Json\Patterns\JsonArrayBracketsPattern;
use App\MarkDown\CustomHL\Languages\Json\Patterns\JsonDoubleQuoteValuePattern;
use App\MarkDown\CustomHL\Languages\Json\Patterns\JsonPropertyPattern;
use App\MarkDown\CustomHL\Languages\Json\Injections\JsonArrayInjection;
use App\MarkDown\CustomHL\Languages\Json\Patterns\DigitsValuePattern;
use App\MarkDown\CustomHL\Languages\CommonPatterns\KeywordPattern;

class JsonLanguage extends CustomBaseLanguage
{
    public function getName(): string
    {
        return 'json';
    }

    public function getInjections(): array
    {
        return [
            ...parent::getInjections(),
            new JsonArrayInjection(),
        ];
    }

    public function getPatterns(): array
    {
        return [
            ...parent::getPatterns(),
            
            new KeywordPattern('null', 'hl-slug'),
            new KeywordPattern('true', 'hl-slug'),
            new KeywordPattern('false', 'hl-slug'),
            
            new JsonPropertyPattern(),
            //new JsonAccoladesPattern(),
            //new JsonArrayBracketsPattern(),
            new JsonDoubleQuoteValuePattern(),
            new DigitsValuePattern(),
        ];
    }
}
