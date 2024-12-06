<?php

declare(strict_types=1);

namespace App\MarkDown\CustomHL\Languages\Php\Injections;

use Tempest\Highlight\After;
use Tempest\Highlight\Highlighter;
use Tempest\Highlight\Injection;
use Tempest\Highlight\IsInjection;
use Tempest\Highlight\Escape;
use Tempest\Highlight\Tokens\DynamicTokenType;

#[After]
final readonly class MultilineSingleDocCommentInjection implements Injection
{
    use IsInjection;

    public function getPattern(): string
    {
        return '/(?<match>\/\*(?!\*)(.|\n)*?\*\/)/m';  //(\r\n)*?
    }
    
    public function parseContent(string $content, Highlighter $highlighter): string
    {
        preg_match_all('/(?<match>❷\/span❸)/', $content, $m1, PREG_OFFSET_CAPTURE);
        preg_match_all('/(?<match>❷span)/', $content, $m2, PREG_OFFSET_CAPTURE);
        if (count($m1['match']) != count($m2['match'])) {
            return $content;
        }
        //dd($content, $m1, $m2);
        $theme = $highlighter->getTheme(); 
        //return Escape::tokens('<span class="hl-php-comment">') . $highlighter->parse($content, new PhpDocCommentLanguage()) . Escape::tokens('</span>'); 
        $clear_content = str_replace("\r", '', Escape::terminal($content));
        
        //$clear_content = $this->clearContent($content);
        
        //$t = Escape::injection(
        //    Escape::tokens($theme->before(new DynamicTokenType('hl-php-comment')))
        //    . $clear_content
        //    . Escape::tokens($theme->after(new DynamicTokenType('hl-php-comment')))
        //);
        
        //if (strpos($content, 'throttle') > 0) { dd($content, $clear_content, $t); }
        return Escape::injection(
            Escape::tokens($theme->before(new DynamicTokenType('hl-php-comment')))
            . $clear_content  //$content  //$clear_content
            . Escape::tokens($theme->after(new DynamicTokenType('hl-php-comment')))
        ); 
    }
}
