<?php

declare(strict_types=1);

namespace App\MarkDown\CustomHL\Languages\Php\Injections;

use Tempest\Highlight\Highlighter;
use Tempest\Highlight\Injection;
use Tempest\Highlight\IsInjection;
use Tempest\Highlight\Escape;
use Tempest\Highlight\Tokens\DynamicTokenType;
use App\MarkDown\CustomHL\Languages\Php\PhpConst;

final readonly class TypeForVariableInjection implements Injection
{
    use IsInjection;

    public function getPattern(): string
    {
        return '(?<match>[\w\&\(\)\|\\\\\?]+)\s+(\.*)\\$';
    }

    public function parseContent(string $content, Highlighter $highlighter): string
    {
        $keywords = PhpConst::SYS_KEYWORDS;
        
        $types = explode('|', trim($content));
        
        $theme = $highlighter->getTheme(); 
        
        foreach ($types as $type) {
            $type = str_replace('?', '', $type);
            
            $t = explode('\\', $type);
            $type = $t[count($t) - 1];
            

        //echo '<pre>' . print_r($types) . '</pre>';
            
            $token = (in_array($type, $keywords)) ? 'hl-php-keyword' : 'hl-php-type';
            $content = preg_replace(
                '/\b' . $type . '[\b]*/',
                Escape::tokens($theme->before(new DynamicTokenType($token)))
                . $type
                . Escape::tokens($theme->after(new DynamicTokenType($token))),
                $content,
            );
        } 

        return $content;
    }
}
