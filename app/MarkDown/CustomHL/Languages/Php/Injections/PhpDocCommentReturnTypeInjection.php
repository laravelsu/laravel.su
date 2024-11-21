<?php

declare(strict_types=1);

namespace App\MarkDown\CustomHL\Languages\Php\Injections;

use Tempest\Highlight\Highlighter;
use Tempest\Highlight\Injection;
use Tempest\Highlight\IsInjection;
//use App\MarkDown\CustomHL\Languages\Php\PhpDocCommentLanguage;
use Tempest\Highlight\Escape;
use Tempest\Highlight\Tokens\DynamicTokenType;
use App\MarkDown\CustomHL\Languages\Php\PhpConst;

final readonly class PhpDocCommentReturnTypeInjection implements Injection
{
    use IsInjection;

    public function getPattern(): string
    {
        return '\@(return|throws|var)(\s)+(?<match>.*?)(\*\/|$|\R)';
    }

    public function parseContent(string $content, Highlighter $highlighter): string
    {
        $keywords = PhpConst::SYS_KEYWORDS;
        
        $types = explode('|', trim($content));
        
        $theme = $highlighter->getTheme(); 
        
        foreach ($types as $type) {
            preg_match('/(?<match>array[\s]*\<.*?\>)/', $type, $matches);
            if (key_exists('match', $matches))
            {
                $content = preg_replace(
                    '/\barray[\b]*/',
                    Escape::tokens($theme->before(new DynamicTokenType('hl-php-keyword')))
                    . 'array'
                    . Escape::tokens($theme->after(new DynamicTokenType('hl-php-keyword'))),
                    $content,
                );
                
                preg_match('/array[\s]*\<(?<match>.*?)\>/', $type, $matches);
                
                if (key_exists('match', $matches))
                {
                    $tks = explode(',', $matches['match']);
                    
                    foreach ($tks as $tk) {
                        $tk = trim($tk);
                        
                        $t = explode('\\', $tk);
                        $tk = $t[count($t) - 1];
                    
                        $token = (in_array($tk, $keywords)) ? 'hl-php-keyword' : 'hl-php-type';
                        
                        $content = preg_replace(
                            '/\b' . $tk . '[\b]*/',
                            Escape::tokens($theme->before(new DynamicTokenType($token)))
                            . $tk
                            . Escape::tokens($theme->after(new DynamicTokenType($token))),
                            $content,
                        );
                    }
                }
            }
            else
            {
                $t = explode('\\', $type);
                $type = $t[count($t) - 1];
            
                $token = (in_array($type, $keywords)) ? 'hl-php-keyword' : 'hl-php-type';
            
                $content = preg_replace(
                    '/\b' . $type . '[\b]*/',
                    Escape::tokens($theme->before(new DynamicTokenType($token)))
                    . $type
                    . Escape::tokens($theme->after(new DynamicTokenType($token))),
                    $content,
                );
            }
        } 

        return $content;
    }
}
