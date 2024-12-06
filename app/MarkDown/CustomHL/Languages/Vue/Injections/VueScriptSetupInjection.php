<?php

declare(strict_types=1);

namespace App\MarkDown\CustomHL\Languages\Vue\Injections;

use Tempest\Highlight\Highlighter;
use Tempest\Highlight\Injection;
use Tempest\Highlight\IsInjection;
use App\MarkDown\CustomHL\Languages\Vue\VueSetupLanguage;

final readonly class VueScriptSetupInjection implements Injection
{
    use IsInjection;

    public function getPattern(): string
    {
//        return '/(\<script( )+setup( )*>)(?<match>(.|\n)*?)(\<\/( )*script( )*>)/';
        return '/(setup( )*>)(?<match>(.|\n)*?)(\<\/( )*)/';
    }

    public function parseContent(string $content, Highlighter $highlighter): string
    {
        return $highlighter->parse($content, new VueSetupLanguage());
    }
}
