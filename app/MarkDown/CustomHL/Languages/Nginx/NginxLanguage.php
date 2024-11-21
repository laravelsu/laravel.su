<?php

declare(strict_types=1);

namespace App\MarkDown\CustomHL\Languages\Nginx;

use App\MarkDown\CustomHL\Languages\CustomBase\CustomBaseLanguage;
use App\MarkDown\CustomHL\Languages\Nginx\Patterns\KeywordPattern;
use App\MarkDown\CustomHL\Languages\Nginx\Patterns\DoubleQuoteValuePattern;
use App\MarkDown\CustomHL\Languages\Nginx\Patterns\VariablesPattern;
use App\MarkDown\CustomHL\Languages\Nginx\Patterns\OperatorPattern;
use App\MarkDown\CustomHL\Languages\Nginx\Patterns\LocationPathPattern;

class NginxLanguage extends CustomBaseLanguage
{
    public function getName(): string
    {
        return 'nginx';
    }

    public function getInjections(): array
    {
        return [
            ...parent::getInjections(),
        ];
    }

    public function getPatterns(): array
    {
        return [
            ...parent::getPatterns(),
            new KeywordPattern('server'),
            new KeywordPattern('listen'),
            new KeywordPattern('server_name'),
            new KeywordPattern('root'),
            new KeywordPattern('add_header'),
            new KeywordPattern('index'),
            new KeywordPattern('charset'),
            new KeywordPattern('location'),
            new KeywordPattern('try_files'),
            new KeywordPattern('access_log'),
            new KeywordPattern('log_not_found'),
            new KeywordPattern('error_page'),
            new KeywordPattern('fastcgi_pass'),
            new KeywordPattern('fastcgi_param'),
            new KeywordPattern('include'),
            new KeywordPattern('fastcgi_hide_header'),
            new KeywordPattern('deny'),
            
            new OperatorPattern('(=|~)'),
        
            new LocationPathPattern(),
            new DoubleQuoteValuePattern(),
            new VariablesPattern(),
        ];
    }
}
