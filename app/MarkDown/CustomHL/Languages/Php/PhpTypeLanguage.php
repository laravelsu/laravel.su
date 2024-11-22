<?php

declare(strict_types=1);

namespace App\MarkDown\CustomHL\Languages\Php;

use App\MarkDown\CustomHL\Languages\CustomBase\CustomBaseLanguage;
//use Tempest\Highlight\Languages\Php\Injections\PhpAttributeInstanceInjection;
//use Tempest\Highlight\Languages\Php\Injections\PhpAttributePlainInjection;
use App\MarkDown\CustomHL\Languages\Php\Injections\PhpDocCommentInjection;
//use Tempest\Highlight\Languages\Php\Patterns\ClassPropertyPattern;
use App\MarkDown\CustomHL\Languages\Php\Patterns\KeywordPattern;
//use App\Tempest\Highlight\Languages\Php\Patterns\MultilineSingleDocCommentPattern;
//use App\Tempest\Highlight\Languages\Php\Patterns\NewObjectPattern;
//use App\Tempest\Highlight\Languages\Php\Patterns\SinglelineCommentPattern;
//use App\MarkDown\CustomHL\Languages\Php\Patterns\TypeForVariablePattern;
//use Tempest\Highlight\Languages\Php\Patterns\VariablePattern;
use App\MarkDown\CustomHL\Languages\Php\Patterns\OperatorPattern;
use App\MarkDown\CustomHL\Languages\Php\Injections\TypeForVariableInjection;

final class PhpTypeLanguage extends CustomBaseLanguage
{
    public function getName(): string
    {
        return 'phptype';
    }

    public function getInjections(): array
    {
        return [
            ...parent::getInjections(),
            //new PhpAttributePlainInjection(),
            //new PhpAttributeInstanceInjection(),
            new PhpDocCommentInjection(),
            new TypeForVariableInjection(),
        ];
    }

    public function getPatterns(): array
    {
        return [
            ...parent::getPatterns(),

            // COMMENTS
            //new MultilineSingleDocCommentPattern(),
            //new SinglelineCommentPattern(),

            //new TypeForVariablePattern(),

            new OperatorPattern('(<|=>|>|=|\*)'),
            //new KeywordPattern('array'),
            //new KeywordPattern('bool'),
            new KeywordPattern('public'),
            //new KeywordPattern('private'),
            new KeywordPattern('protected'),
            new KeywordPattern('null', 'hl-php-constant'),
            new KeywordPattern('true', 'hl-php-constant'),
            new KeywordPattern('false', 'hl-php-constant'),
            //new KeywordPattern('new'),
            //new KeywordPattern('readonly'),

            //new ClassPropertyPattern(),
            //new NewObjectPattern(),
            
            // VARIABLES
            //new VariablePattern(),
        ];
    }
}
