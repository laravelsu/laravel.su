<?php

declare(strict_types=1);

namespace App\MarkDown\CustomHL\Languages\Blade;

use App\MarkDown\CustomHL\Languages\Blade\Injections\BladeEchoInjection;
//use App\Tempest\Highlight\Languages\Blade\Injections\BladeKeywordInjection;
use App\MarkDown\CustomHL\Languages\Blade\Injections\BladePhpInjection;
use App\MarkDown\CustomHL\Languages\Blade\Injections\BladeRawEchoInjection;
use App\MarkDown\CustomHL\Languages\Blade\Patterns\BladeCommentPattern;
use App\MarkDown\CustomHL\Languages\Blade\Patterns\BladeComponentCloseTagPattern;
use App\MarkDown\CustomHL\Languages\Blade\Patterns\BladeComponentOpenTagPattern;
use App\MarkDown\CustomHL\Languages\Blade\Patterns\BladeKeywordPattern;
use App\MarkDown\CustomHL\Languages\Html\HtmlLanguage;
use App\MarkDown\CustomHL\Languages\Blade\Patterns\DelimeterPattern;
use App\MarkDown\CustomHL\Languages\Blade\Patterns\KeywordPattern;
use App\MarkDown\CustomHL\Languages\Blade\Injections\SingleQuoteValueInjection;
use App\MarkDown\CustomHL\Languages\Blade\Patterns\GenericPattern;
use App\MarkDown\CustomHL\Languages\Blade\Patterns\EscapeSymbolPattern;
use App\MarkDown\CustomHL\Languages\Blade\Injections\BladeKeywordParametersInjection;

class BladeLanguage extends HtmlLanguage
{
    public function getName(): string
    {
        return 'blade';
    }

    public function getAliases(): array
    {
        return ['html'];
    }

    public function getInjections(): array
    {
        return [
            ...parent::getInjections(),
            //new BladeKeywordInjection(),
            new BladePhpInjection(),
            new BladeKeywordParametersInjection(),
            new BladeEchoInjection(),
            new BladeRawEchoInjection(),
            new SingleQuoteValueInjection(),
        ];
    }

    public function getPatterns(): array
    {
        return [
            ...parent::getPatterns(),
            //new BladeComponentOpenTagPattern(),
            //new BladeComponentCloseTagPattern(),
            new BladeKeywordPattern(),
            new BladeCommentPattern(),
            
            new DelimeterPattern('({!!|!!}|=\>)'),  // {{|}}|   // /(?<match>({!!|!!}|\<[^!](\/)?|\<(?!\!\--)|\>|=\>))/
            new GenericPattern('/(?<match>({{(?!\-\-)))/', 'hl-blade-delimeter'),  // /(?<match>({{(?!\-\-)|[^\-]}}|{!!|!!}))/
            new GenericPattern('/[^\-](?<match>(}}))/', 'hl-blade-delimeter'),  // /(?<match>({{(?!\-\-)|[^\-]}}|{!!|!!}))/
            new GenericPattern('/(?<match>(\<(\/)?))[^!]/', 'hl-blade-delimeter'),  // /(?<match>({{(?!\-\-)|[^\-]}}|{!!|!!}))/
            new GenericPattern('/[^\-](?<match>(\>))/', 'hl-blade-delimeter'),  // /(?<match>({{(?!\-\-)|[^\-]}}|{!!|!!}))/
            new EscapeSymbolPattern(),
            
            new KeywordPattern('as'),
        ];
    }
}
