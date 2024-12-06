<?php

declare(strict_types=1);

namespace App\MarkDown\CustomHL\Languages\Vue;

//use App\MarkDown\CustomHL\Languages\Blade\Injections\BladeEchoInjection;
//use App\Tempest\Highlight\Languages\Blade\Injections\BladeKeywordInjection;
//use App\MarkDown\CustomHL\Languages\Blade\Injections\BladePhpInjection;
//use App\Tempest\Highlight\Languages\Blade\Injections\BladeRawEchoInjection;
//use App\Tempest\Highlight\Languages\Blade\Patterns\BladeCommentPattern;
//use App\Tempest\Highlight\Languages\Blade\Patterns\BladeComponentCloseTagPattern;
//use App\MarkDown\CustomHL\Languages\Blade\Patterns\BladeComponentOpenTagPattern;
//use App\MarkDown\CustomHL\Languages\Blade\Patterns\BladeKeywordPattern;
use App\MarkDown\CustomHL\Languages\Html\HtmlLanguage;
//use App\MarkDown\CustomHL\Languages\Blade\Patterns\DelimeterPattern;
//use App\MarkDown\CustomHL\Languages\Blade\Patterns\KeywordPattern;
use App\MarkDown\CustomHL\Languages\Vue\Patterns\VueScriptSetupPattern;
use App\MarkDown\CustomHL\Languages\Vue\Injections\VueScriptSetupInjection;

class VueLanguage extends HtmlLanguage
{
    public function getName(): string
    {
        return 'vue';
    }

    public function getInjections(): array
    {
        return [
            ...parent::getInjections(),
            new VueScriptSetupInjection(),
            //new BladeKeywordInjection(),
            //new BladePhpInjection(),
            //new BladeEchoInjection(),
            //new BladeRawEchoInjection(),
        ];
    }

    public function getPatterns(): array
    {
        return [
            ...parent::getPatterns(),
            new VueScriptSetupPattern(),
            
            //new BladeComponentOpenTagPattern(),
            ////new BladeComponentCloseTagPattern(),
            //new BladeKeywordPattern(),
            ////new BladeCommentPattern(),
            
            //new DelimeterPattern('({{|}})'),
            
            //new KeywordPattern('as'),
        ];
    }
}
