<?php

declare(strict_types=1);

namespace App\MarkDown\CustomHL\Languages\Php\Injections;

use Tempest\Highlight\Highlighter;
use Tempest\Highlight\Injection;
use Tempest\Highlight\IsInjection;
use Tempest\Highlight\Escape;
use Tempest\Highlight\Tokens\DynamicTokenType;
use Illuminate\Support\Str;

final readonly class ClassResolutionInjection implements Injection
{
    use IsInjection;

    public function getPattern(): string
    {
        return '/\:\:(?<match>[\w]+?)\b[^\(]/';
    }

    public function parseContent(string $content, Highlighter $highlighter): string
    {
        $theme = $highlighter->getTheme(); 

        $tokenType = Str::upper($content) == 'CLASS' ? 'hl-php-keyword' : 'hl-php-constant';
        
        return Escape::injection(
            Escape::tokens($theme->before(new DynamicTokenType($tokenType)))
            . $content
            . Escape::tokens($theme->after(new DynamicTokenType($tokenType)))
        ); 
    }
}
