<?php

declare(strict_types=1);

namespace App\MarkDown\CustomHL\Languages\Php\Injections;

use Tempest\Highlight\After;
use Tempest\Highlight\Highlighter;
use Tempest\Highlight\Injection;
use Tempest\Highlight\IsInjection;
use Tempest\Highlight\Escape;
//use Tempest\Highlight\Tokens\DynamicTokenType;
use App\MarkDown\CustomHL\Tokens\QuotedValueTokenType;

#[After]
final readonly class SingleQuoteValueInjection implements Injection
{
    use IsInjection;

    public function getPattern(): string
    {
        return "(?<match>'(?!(s ))(\\\'|.|\n)*?')";
    }

    public function parseContent(string $content, Highlighter $highlighter): string
    {
        $theme = $highlighter->getTheme(); 

        $clear_content = Escape::terminal($content);
        
        return Escape::injection(
            Escape::tokens($theme->before(new QuotedValueTokenType('hl-php-value')))
            . $clear_content
            . Escape::tokens($theme->after(new QuotedValueTokenType('hl-php-value')))
        ); 
    }
}
