<?php

declare(strict_types=1);

namespace App\MarkDown\CustomHL\Languages\JavaScript;

use App\MarkDown\CustomHL\Languages\CustomBase\CustomBaseLanguage;
//use App\Tempest\Highlight\Languages\JavaScript\Injections\JsDocInjection;
//use App\Tempest\Highlight\Languages\JavaScript\Patterns\JsClassNamePattern;
//use App\Tempest\Highlight\Languages\JavaScript\Patterns\JsDoubleQuoteValuePattern;
use App\MarkDown\CustomHL\Languages\JavaScript\Patterns\JsKeywordPattern;
use App\MarkDown\CustomHL\Languages\JavaScript\Patterns\JsMethodPattern;
//use App\Tempest\Highlight\Languages\JavaScript\Patterns\JsMultilineCommentPattern;
//use App\Tempest\Highlight\Languages\JavaScript\Patterns\JsNewObjectPattern;
//use App\Tempest\Highlight\Languages\JavaScript\Patterns\JsObjectPropertyPattern;
use App\MarkDown\CustomHL\Languages\JavaScript\Patterns\JsPropertyPattern;
//use App\Tempest\Highlight\Languages\JavaScript\Patterns\JsSinglelineCommentPattern;
use App\MarkDown\CustomHL\Languages\JavaScript\Patterns\JsSingleQuoteValuePattern;
//use App\Tempest\Highlight\Languages\JavaScript\Patterns\JsStaticClassPattern;
//use App\Tempest\Highlight\Languages\JavaScript\Patterns\JsStaticPropertyPattern;
use App\MarkDown\CustomHL\Languages\JavaScript\Patterns\DigitsPattern;

class JavaScriptLanguage extends CustomBaseLanguage
{
    public function getName(): string
    {
        return 'js';
    }

    public function getAliases(): array
    {
        return [
            'javascript',
            'node',
        ];
    }

    public function getInjections(): array
    {
        return [
            ...parent::getInjections(),
            //new JsDocInjection(),
        ];
    }

    public function getPatterns(): array
    {
        return [
            ...parent::getPatterns(),
            new JsKeywordPattern('default'),
            new JsKeywordPattern('export'),
            new JsKeywordPattern('from'),
            new JsKeywordPattern('import'),
            new JsKeywordPattern('var'),
            //new JsKeywordPattern('set'),
            //new JsKeywordPattern('of'),
            //new JsKeywordPattern('get'),
            //new JsKeywordPattern('eval'),
            //new JsKeywordPattern('async'),
            //new JsKeywordPattern('as'),
            //new JsKeywordPattern('break'),
            //new JsKeywordPattern('case'),
            //new JsKeywordPattern('catch'),
            //new JsKeywordPattern('class'),
            //new JsKeywordPattern('const'),
            //new JsKeywordPattern('continue'),
            //new JsKeywordPattern('debugger'),
            //new JsKeywordPattern('delete'),
            //new JsKeywordPattern('do'),
            //new JsKeywordPattern('else'),
            //new JsKeywordPattern('extends'),
            //new JsKeywordPattern('false'),
            //new JsKeywordPattern('finally'),
            //new JsKeywordPattern('for'),
            //new JsKeywordPattern('function'),
            //new JsKeywordPattern('if'),
            //new JsKeywordPattern('in'),
            //new JsKeywordPattern('instanceof'),
            //new JsKeywordPattern('new'),
            //new JsKeywordPattern('null'),
            //new JsKeywordPattern('return'),
            //new JsKeywordPattern('super'),
            //new JsKeywordPattern('switch'),
            //new JsKeywordPattern('this'),
            //new JsKeywordPattern('throw'),
            //new JsKeywordPattern('true'),
            //new JsKeywordPattern('try'),
            //new JsKeywordPattern('typeof'),
            //new JsKeywordPattern('void'),
            //new JsKeywordPattern('while'),
            //new JsKeywordPattern('with'),
            //new JsKeywordPattern('let'),
            //new JsKeywordPattern('static'),
            //new JsKeywordPattern('yield'),
            //new JsKeywordPattern('await'),
            //new JsKeywordPattern('enum'),
            //new JsKeywordPattern('implements'),
            //new JsKeywordPattern('interface'),
            //new JsKeywordPattern('package'),
            //new JsKeywordPattern('private'),
            //new JsKeywordPattern('protected'),
            //new JsKeywordPattern('public'),
            //new JsKeywordPattern('constructor'),
            //new JsKeywordPattern('this'),

            // COMMENTS
            //new JsMultilineCommentPattern(),
            //new JsSinglelineCommentPattern(),

            // TYPES
            //new JsClassNamePattern(),
            //new JsNewObjectPattern(),
            //new JsStaticClassPattern(),

            // PROPERTIES
            //new JsPropertyPattern(),
            //new JsObjectPropertyPattern(),
            new JsMethodPattern(),
            //new JsStaticPropertyPattern(),

            // VALUES
            new JsSingleQuoteValuePattern(),
            //new JsDoubleQuoteValuePattern(),
            
            new DigitsPattern(),
        ];
    }
}
