<?php

declare(strict_types=1);

namespace App\MarkDown\CustomHL\Languages\Php;

use App\MarkDown\CustomHL\Languages\CustomBase\CustomBaseLanguage;
//use Tempest\Highlight\Languages\Php\Injections\PhpGenericTypeInjection;
//use Tempest\Highlight\Languages\Php\Patterns\PhpDocCommentGenericTypePattern;
//use App\MarkDown\CustomHL\Languages\Php\Patterns\PhpDocCommentParamTypePattern;
use App\MarkDown\CustomHL\Languages\Php\Injections\PhpDocCommentParamTypeInjection;
use App\MarkDown\CustomHL\Languages\Php\Injections\PhpDocCommentReturnTypeInjection;
//use App\MarkDown\CustomHL\Languages\Php\Patterns\PhpDocCommentReturnTypePattern;
//use App\MarkDown\CustomHL\Languages\Php\Patterns\PhpDocCommentReturnTypeSingleLinePattern;
//use Tempest\Highlight\Languages\Php\Patterns\PhpDocCommentTemplateTypePattern;
//use Tempest\Highlight\Languages\Php\Patterns\PhpDocCommentVariablePattern;
//use App\MarkDown\CustomHL\Languages\Php\Patterns\PhpDocCommentVarTypePattern;
use App\MarkDown\CustomHL\Languages\Php\Patterns\PhpDocCommentTagPattern;

class PhpDocCommentLanguage extends CustomBaseLanguage
{
    public function getName(): string
    {
        return 'phpdoc';
    }

    public function getInjections(): array
    {
        return [
            ...parent::getInjections(),
            //new PhpGenericTypeInjection(),
            new PhpDocCommentParamTypeInjection(),
            new PhpDocCommentReturnTypeInjection(),
        ];
    }

    public function getPatterns(): array
    {
        return [
            ...parent::getPatterns(),
            new PhpDocCommentTagPattern(),
            ////new PhpDocCommentParamTypePattern(),
            //new PhpDocCommentVarTypePattern(),
            //new PhpDocCommentReturnTypeSingleLinePattern(),
            ////new PhpDocCommentReturnTypePattern(),
            //new PhpDocCommentTemplateTypePattern(),
            //new PhpDocCommentGenericTypePattern(),
            //new PhpDocCommentVariablePattern(),
        ];
    }
}
