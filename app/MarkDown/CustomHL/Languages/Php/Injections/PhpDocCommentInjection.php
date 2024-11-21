<?php

declare(strict_types=1);

namespace App\MarkDown\CustomHL\Languages\Php\Injections;

use Tempest\Highlight\After;
use Tempest\Highlight\Highlighter;
use Tempest\Highlight\Injection;
use Tempest\Highlight\IsInjection;
use App\MarkDown\CustomHL\Languages\Php\PhpDocCommentLanguage;
use Tempest\Highlight\Escape;
use Tempest\Highlight\Tokens\DynamicTokenType;

#[After]
final readonly class PhpDocCommentInjection implements Injection
{
    use IsInjection;

    public function getPattern(): string
    {
        return '/(?<match>\/\*\*(.|\n)*?\*\/)/m';
    }

    public function parseContent(string $content, Highlighter $highlighter): string
    {
        $theme = $highlighter->getTheme(); 
        //return Escape::tokens('<span class="hl-php-comment">') . $highlighter->parse($content, new PhpDocCommentLanguage()) . Escape::tokens('</span>'); 
        
        $clear_content = Escape::terminal($content);
        return Escape::injection(
            Escape::tokens($theme->before(new DynamicTokenType('hl-php-comment')))
            . $highlighter->parse($clear_content, new PhpDocCommentLanguage())
            . Escape::tokens($theme->after(new DynamicTokenType('hl-php-comment')))
        ); 
    }
}
