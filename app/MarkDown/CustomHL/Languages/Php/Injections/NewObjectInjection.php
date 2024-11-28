<?php

declare(strict_types=1);

namespace App\MarkDown\CustomHL\Languages\Php\Injections;

use Tempest\Highlight\Before;
use Tempest\Highlight\Highlighter;
use Tempest\Highlight\IsInjection;
use Tempest\Highlight\Injection;
use Tempest\Highlight\Escape;
use Tempest\Highlight\Tokens\DynamicTokenType;

#[Before]
final readonly class NewObjectInjection implements Injection
{
    use IsInjection;

    public function getPattern(): string
    {
        //return 'new (?<match>[\w\\\\]+)';
        return '/new [\w\\\\]*\b(?<match>[\w]+)/';
    }

    public function parseContent(string $content, Highlighter $highlighter): string
    {
        $theme = $highlighter->getTheme(); 

        $clear_content = Escape::terminal($content);
        return Escape::injection(
            Escape::tokens($theme->before(new DynamicTokenType('hl-php-type')))
            . $clear_content
            . Escape::tokens($theme->after(new DynamicTokenType('hl-php-type')))
        ); 
    }
}
