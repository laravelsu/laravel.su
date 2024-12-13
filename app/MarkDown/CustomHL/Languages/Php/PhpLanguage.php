<?php

declare(strict_types=1);

namespace App\MarkDown\CustomHL\Languages\Php;

use App\MarkDown\CustomHL\Languages\CustomBase\CustomBaseLanguage;
//use App\Tempest\Highlight\Languages\Php\Injections\PhpAttributeInstanceInjection;
//use App\Tempest\Highlight\Languages\Php\Injections\PhpAttributePlainInjection;

use App\MarkDown\CustomHL\Languages\Php\Injections\PhpDocCommentInjection;

//use App\Tempest\Highlight\Languages\Php\Injections\PhpHeredocInjection;
//use App\Tempest\Highlight\Languages\Php\Patterns\AttributeTypePattern;
use App\MarkDown\CustomHL\Languages\Php\Patterns\CatchTypePattern;
use App\MarkDown\CustomHL\Languages\Php\Patterns\ClassNamePattern;
//use App\Tempest\Highlight\Languages\Php\Patterns\ClassPropertyPattern;
//use App\Tempest\Highlight\Languages\Php\Patterns\ConstantPropertyPattern;
//use App\Tempest\Highlight\Languages\Php\Patterns\ConstantTypesPattern;
//use App\Tempest\Highlight\Languages\Php\Patterns\DoubleQuoteValuePattern;
use App\MarkDown\CustomHL\Languages\Php\Patterns\EnumBackedTypePattern;
use App\MarkDown\CustomHL\Languages\Php\Patterns\EnumCasePattern;

use App\MarkDown\CustomHL\Languages\Php\Patterns\ExtendsPattern;

//use App\Tempest\Highlight\Languages\Php\Patterns\FunctionNamePattern;
//use App\Tempest\Highlight\Languages\Php\Patterns\GroupedTypePattern;
use App\MarkDown\CustomHL\Languages\Php\Patterns\ImplementsPattern;
use App\MarkDown\CustomHL\Languages\Php\Patterns\InstanceOfPattern;

use App\MarkDown\CustomHL\Languages\Php\Patterns\KeywordPattern;

//use App\MarkDown\CustomHL\Languages\Php\Patterns\MultilineSingleDocCommentPattern;
use App\MarkDown\CustomHL\Languages\Php\Patterns\NamedArgumentPattern;
//use App\Tempest\Highlight\Languages\Php\Patterns\NestedFunctionCallPattern;
//use App\MarkDown\CustomHL\Languages\Php\Patterns\NewObjectPattern;
use App\MarkDown\CustomHL\Languages\Php\Patterns\OperatorPattern;

use App\MarkDown\CustomHL\Languages\Php\Patterns\PhpCloseTagPattern;

//use App\Tempest\Highlight\Languages\Php\Patterns\PropertyAccessPattern;
//use App\Tempest\Highlight\Languages\Php\Patterns\PropertyHookGetPattern;
//use App\Tempest\Highlight\Languages\Php\Patterns\PropertyHookSetParameterTypePattern;
//use App\Tempest\Highlight\Languages\Php\Patterns\PropertyHookSetPattern;
//use App\Tempest\Highlight\Languages\Php\Patterns\PropertyTypesPattern;

use App\MarkDown\CustomHL\Languages\Php\Injections\ReturnTypeInjection;

//use App\Tempest\Highlight\Languages\Php\Patterns\ShortFunctionReferencePattern;
//use App\MarkDown\CustomHL\Languages\Php\Patterns\SinglelineCommentPattern;
//use App\MarkDown\CustomHL\Languages\Php\Patterns\SingleQuoteValuePattern;
//use App\Tempest\Highlight\Languages\Php\Patterns\StaticPropertyPattern;
//use App\Tempest\Highlight\Languages\Php\Patterns\UntypedClassPropertyPattern;
//use App\MarkDown\CustomHL\Languages\Php\Patterns\UseAsPattern;
//use App\Tempest\Highlight\Languages\Php\Patterns\UseFunctionNamePattern;
//use App\Tempest\Highlight\Languages\Php\Patterns\UseFunctionPattern;
//use App\Tempest\Highlight\Languages\Php\Patterns\VariablePattern;
//use App\Tempest\Highlight\Tokens\TokenTypeEnum;
use App\MarkDown\CustomHL\Languages\Php\Patterns\DelimeterPattern;
use App\MarkDown\CustomHL\Languages\Php\Patterns\DigitsPattern;
use App\MarkDown\CustomHL\Languages\Php\Patterns\UsePattern;
//use App\MarkDown\CustomHL\Languages\Php\Patterns\UsePatternPath;
use App\MarkDown\CustomHL\Languages\Php\Patterns\StaticClassCallPattern;
//use App\MarkDown\CustomHL\Languages\Php\Patterns\StaticClassCallPathPattern;
use App\MarkDown\CustomHL\Languages\Php\Patterns\FunctionCallPattern;
use App\MarkDown\CustomHL\Languages\Php\Patterns\ConstantNamePattern;
use App\MarkDown\CustomHL\Languages\Php\Injections\PhpFunctionParametersInjection;
use App\MarkDown\CustomHL\Languages\Php\Patterns\PhpOpenTagPattern;
//use App\MarkDown\CustomHL\Languages\Php\Patterns\NamespacePattern;

use App\MarkDown\CustomHL\Languages\Php\Injections\SinglelineCommentInjection;
use App\MarkDown\CustomHL\Languages\Php\Injections\MultilineSingleDocCommentInjection;
use App\MarkDown\CustomHL\Languages\Php\Injections\SingleQuoteValueInjection;
use App\MarkDown\CustomHL\Languages\Php\Injections\DoubleQuoteValueInjection;
use App\MarkDown\CustomHL\Languages\Php\Patterns\GenericPattern;
use App\MarkDown\CustomHL\Languages\Php\Injections\NewObjectInjection;
//use App\MarkDown\CustomHL\Languages\Php\Patterns\UriPathPattern;
use App\MarkDown\CustomHL\Languages\Php\Injections\ClassResolutionInjection;

class PhpLanguage extends CustomBaseLanguage
{
    public function getName(): string
    {
        return 'php';
    }

    public function getAliases(): array
    {
        return ['txt'];
    }

    public function getInjections(): array
    {
        return [
            ...parent::getInjections(),
            ////new PhpHeredocInjection(),
            new PhpDocCommentInjection(),
            ////new PhpAttributePlainInjection(),
            ////new PhpAttributeInstanceInjection(),
            new PhpFunctionParametersInjection(),
            new SingleQuoteValueInjection(),
            new DoubleQuoteValueInjection(),
            new SinglelineCommentInjection(),
            new MultilineSingleDocCommentInjection(),
            new ClassResolutionInjection(),
            
            new ReturnTypeInjection(),
            new NewObjectInjection(),
        ];
    }

    public function getPatterns(): array
    {
        return [
            ...parent::getPatterns(),

            new PhpOpenTagPattern(),
            new PhpCloseTagPattern(),
            ////new UseFunctionNamePattern(),
            ////new UseFunctionPattern(),
            new ClassNamePattern(),
            new NamedArgumentPattern(),
            ////new OperatorPattern('&&'),
            ////new OperatorPattern('\|\|'),
            new OperatorPattern('(!==|===|==|<=>|<|=>|>|=|\*|\+\+|\+|&&|\?|\|\|)'),
            //new OperatorPattern('instanceof'),
            ////new OperatorPattern('\?'),
            ////new FunctionNamePattern(),
            new DelimeterPattern('(\:\:|->|\.)'),
            //new DelimeterPattern('->'),
            //new DelimeterPattern('[.,(){};\:\[\]]', TokenTypeEnum::PROPERTY),
            
            //new UriPathPattern(),
            
            // KEYWORDS
            new KeywordPattern('null', 'hl-php-constant'),
            new GenericPattern('/(?<match>\$this)(\-|\$|\,|\)|\;|\:|\s|\(|\])/', 'hl-php-this'),
            new GenericPattern('/\->\b(?<match>[\w]+?)\b(?!\()/', 'hl-php-delimeter'),
            new GenericPattern('/\((?<match>(string))\)/', 'hl-php-keyword'),
            new GenericPattern('/protected static (\?)?(?<match>(string)) \$/', 'hl-php-keyword'),
            ////new KeywordPattern('parent'),
            new KeywordPattern('true', 'hl-php-constant'),
            new KeywordPattern('false', 'hl-php-constant'),
            ////new KeywordPattern('__halt_compiler'),
            new KeywordPattern('app', 'hl-php-constant'),
            new KeywordPattern('abstract'),
            ////new KeywordPattern('and'),
            new KeywordPattern('as'),
            new KeywordPattern('array'),
            new KeywordPattern('string'),
            ////new KeywordPattern('break'),
            new KeywordPattern('callable'),
            new KeywordPattern('case'),
            new KeywordPattern('catch'),
            new KeywordPattern('class'),
            ////new KeywordPattern('clone'),
            ////new KeywordPattern('const'),
            ////new KeywordPattern('continue'),
            ////new KeywordPattern('declare'),
            new KeywordPattern('default'),
            ////new KeywordPattern('die'),
            ////new KeywordPattern('do'),
            new KeywordPattern('echo', 'hl-php-constant'),
            new KeywordPattern('name', 'hl-php-constant'),
            new KeywordPattern('count\+\+', 'hl-php-constant'),
            new KeywordPattern('else'),
            new KeywordPattern('elseif'),
            ////new KeywordPattern('empty'),
            new KeywordPattern('enum'),
            ////new KeywordPattern('enddeclare'),
            ////new KeywordPattern('endfor'),
            new KeywordPattern('endforeach'),
            ////new KeywordPattern('endif'),
            ////new KeywordPattern('endswitch'),
            ////new KeywordPattern('endwhile'),
            ////new KeywordPattern('eval'),
            ////new KeywordPattern('exit'),
            new KeywordPattern('extends'),
            ////new KeywordPattern('final'),
            new KeywordPattern('finally'),
            new KeywordPattern('fn'),
            ////new KeywordPattern('for'),
            new KeywordPattern('foreach'),
            new KeywordPattern('function'),
            ////new KeywordPattern('global'),
            ////new KeywordPattern('goto'),
            new KeywordPattern('id', 'hl-php-constant'),
            new KeywordPattern('if'),
            new KeywordPattern('implements'),
            ////new KeywordPattern('include'),
            ////new KeywordPattern('include_once'),
            new KeywordPattern('instanceof', 'hl-php-constant'),
            ////new KeywordPattern('insteadof'),
            new KeywordPattern('interface'),
            ////new KeywordPattern('isset'),
            ////new KeywordPattern('list'),
            new KeywordPattern('label', 'hl-php-constant'),
            new KeywordPattern('match'),
            new KeywordPattern('namespace'),
            new KeywordPattern('new', 'hl-php-operator'),
            ////new KeywordPattern('or'),
            new KeywordPattern('photo', 'hl-php-delimeter'),
            ////new KeywordPattern('print'),
            ////new KeywordPattern('private'),
            new KeywordPattern('protected'),
            new KeywordPattern('public'),
            ////new KeywordPattern('readonly'),
            ////new KeywordPattern('require'),
            ////new KeywordPattern('require_once'),
            new KeywordPattern('return'),
            new KeywordPattern('static'),
            ////new KeywordPattern('switch'),
            new KeywordPattern('throw'),
            ////new KeywordPattern('trait'),
            new KeywordPattern('try'),
            ////new KeywordPattern('unset'),
            new KeywordPattern('use'),
            new KeywordPattern('validate', 'hl-php-constant'),
            new KeywordPattern('value', 'hl-php-constant'),
            new KeywordPattern('while'),
            ////new KeywordPattern('xor'),
            new KeywordPattern('yield'),
            ////new KeywordPattern('yield from'),
            ////new ShortFunctionReferencePattern(),
            ////new PropertyHookSetPattern(),
            ////new PropertyHookGetPattern(),
            new DigitsPattern(),

            // COMMENTS
            //new MultilineSingleDocCommentPattern(),
            //new SinglelineCommentPattern(),

            new ConstantNamePattern(),

            // TYPES
            ////new AttributeTypePattern(),
            new ImplementsPattern(),
            new ExtendsPattern(),
            new UsePattern(),
            //new UsePatternPath(),
            //new NamespacePattern(),
            ////new PropertyTypesPattern(),
            ////new ConstantTypesPattern(),
            new StaticClassCallPattern(),
            //new StaticClassCallPathPattern(),
            //new NewObjectPattern(),
            new InstanceOfPattern(),
            ////new UseAsPattern(),
            new CatchTypePattern(),
            new EnumBackedTypePattern(),
            ////new GroupedTypePattern(),
            ////new PropertyHookSetParameterTypePattern(),

            // PROPERTIES
            ////new ClassPropertyPattern(),
            ////new PropertyAccessPattern(),
            ////new NestedFunctionCallPattern(),
            new FunctionCallPattern(),
            ////new ConstantPropertyPattern(),
            ////new UntypedClassPropertyPattern(),
            new EnumCasePattern(),
            ////new StaticPropertyPattern(),

            // VARIABLES
            //new VariablePattern(),

            // VALUES
            //new SingleQuoteValuePattern(),
            //new DoubleQuoteValuePattern(),
        ];
    }
}
