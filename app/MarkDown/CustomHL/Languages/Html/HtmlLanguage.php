<?php

declare(strict_types=1);

namespace App\MarkDown\CustomHL\Languages\Html;

//use App\Tempest\Highlight\Languages\Html\Injections\CssAttributeInHtmlInjection;
//use App\Tempest\Highlight\Languages\Html\Injections\CssInHtmlInjection;
use App\MarkDown\CustomHL\Languages\Html\Injections\JavaScriptInHtmlInjection;
//use App\Tempest\Highlight\Languages\Html\Injections\PhpInHtmlInjection;
//use App\Tempest\Highlight\Languages\Html\Injections\PhpShortEchoInHtmlInjection;
use App\MarkDown\CustomHL\Languages\Xml\XmlLanguage;
use App\MarkDown\CustomHL\Languages\Html\Injections\HtmlOpenTagInjection;
use App\MarkDown\CustomHL\Languages\Html\Injections\HtmlCloseTagInjection;

class HtmlLanguage extends XmlLanguage
{
    public function getName(): string
    {
        return 'html';
    }

    public function getInjections(): array
    {
        return [
            ...parent::getInjections(),
            new JavaScriptInHtmlInjection(),
            new HtmlOpenTagInjection(),
            new HtmlCloseTagInjection(),
            //new PhpInHtmlInjection(),
            //new PhpShortEchoInHtmlInjection(),
            //new CssInHtmlInjection(),
            //new CssAttributeInHtmlInjection(),
        ];
    }

    public function getPatterns(): array
    {
        return [
            ...parent::getPatterns(),
        ];
    }
}
