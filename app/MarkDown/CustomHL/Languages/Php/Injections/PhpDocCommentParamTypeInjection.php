<?php

declare(strict_types=1);

namespace App\MarkDown\CustomHL\Languages\Php\Injections;

use Tempest\Highlight\Highlighter;
use Tempest\Highlight\Injection;
use Tempest\Highlight\IsInjection;
use Tempest\Highlight\Escape;
use Tempest\Highlight\Tokens\DynamicTokenType;
use App\MarkDown\CustomHL\Languages\Php\PhpConst;

final readonly class PhpDocCommentParamTypeInjection implements Injection
{
    use IsInjection;

    public function getPattern(): string
    {
        return '\@param(\s)+(?<match>.*?) \\$';
    }

    public function parseContent(string $content, Highlighter $highlighter): string
    {
        $keywords = PhpConst::SYS_KEYWORDS;

        $types_tm = explode('|', trim($content));
        
        $types = array();
        
        foreach($types_tm as $type) {
            $tm = explode(':', trim($type));
            foreach($tm as $t) {
        //if (strpos($content, ':') == 0){
        //    dd($content, $t);
        //}
                //preg_match_all('/(?<match>[\w\\\\\-]*?)\(([\w\\\\\-]*?)(\s)*?\)/', $t, $match, PREG_OFFSET_CAPTURE);
                preg_match_all('/(?<match>[\w\\\\\-]+)(\(([\w\\\\\-]+)(\s)*?\))*/', $t, $match, PREG_OFFSET_CAPTURE);

                if (! $match) {
                    continue;
                }

                $k = $match['match'];
                
                array_walk($k, function($val, $key) use (&$types) {
                    if (!empty(trim($val[0]))) {
                        array_push($types, trim($val[0]));
                    }
                });

                //preg_match_all('/([\w\\\\\-]*?)\((?<match>[\w\\\\\-]*?)(\s)*?\)/', $t, $match, PREG_OFFSET_CAPTURE);
                preg_match_all('/([\w\\\\\-]+)(\((?<match>[\w\\\\\-]+)(\s)*?\))*/', $t, $match, PREG_OFFSET_CAPTURE);

                if (! $match) {
                    continue;
                }

                $k = $match['match'];
                
                array_walk($k, function($val, $key) use (&$types) {
                    if (!empty(trim($val[0]))) {
                        array_push($types, trim($val[0]));
                    }
                });

            }
        }
        //dd($content, $types, $tm);
        
        $theme = $highlighter->getTheme(); 
        
        foreach ($types as $type) {
            $t = explode('\\', $type);
            $type = $t[count($t) - 1];
            //dd($type);
            $token = (in_array($type, $keywords)) ? 'hl-php-keyword' : 'hl-php-type';
            $content = preg_replace(
                '/\b' . $type . '[\b]*/',
                Escape::tokens($theme->before(new DynamicTokenType($token)))
                . $type
                . Escape::tokens($theme->after(new DynamicTokenType($token))),
                $content,
            );
        } 
        //dd($content);

        return $content;
    }
}
