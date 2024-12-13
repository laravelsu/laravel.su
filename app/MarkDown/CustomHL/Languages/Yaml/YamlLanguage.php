<?php

declare(strict_types=1);

namespace App\MarkDown\CustomHL\Languages\Yaml;

use App\MarkDown\CustomHL\Languages\CustomBase\CustomBaseLanguage;
//use Tempest\Highlight\Languages\Yaml\Patterns\YamlArrayBracketsPattern;
//use Tempest\Highlight\Languages\Yaml\Patterns\YamlColonPattern;
use App\MarkDown\CustomHL\Languages\Yaml\Patterns\YamlCommentPattern;
//use Tempest\Highlight\Languages\Yaml\Patterns\YamlDashPattern;
//use Tempest\Highlight\Languages\Yaml\Patterns\YamlDoubleAccoladesValuePattern;
//use Tempest\Highlight\Languages\Yaml\Patterns\YamlDoubleQuoteValuePattern;
//use Tempest\Highlight\Languages\Yaml\Patterns\YamlObjectBracketsPattern;
//use Tempest\Highlight\Languages\Yaml\Patterns\YamlPipePattern;
use App\MarkDown\CustomHL\Languages\Yaml\Patterns\YamlPropertyPattern;
//use Tempest\Highlight\Languages\Yaml\Patterns\YamlSingleQuoteValuePattern;
//use Tempest\Highlight\Languages\Yaml\Patterns\YamlVariablePattern;

use App\MarkDown\CustomHL\Languages\CommonPatterns\DigitsPattern;
use App\MarkDown\CustomHL\Languages\CommonPatterns\DoubleQuoteValuePattern;

class YamlLanguage extends CustomBaseLanguage
{
    public function getName(): string
    {
        return 'yaml';
    }

    public function getAliases(): array
    {
        return [
            'yml',
        ];
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
            new YamlPropertyPattern(),
            //new YamlDashPattern(),
            //new YamlColonPattern(),
            //new YamlPipePattern(),
            //new YamlVariablePattern(),
            //new YamlArrayBracketsPattern(),
            //new YamlObjectBracketsPattern(),
            //new YamlDoubleQuoteValuePattern(),
            //new YamlSingleQuoteValuePattern(),
            new YamlCommentPattern(),
            //new YamlDoubleAccoladesValuePattern(),
            
            new DigitsPattern(),
            new DoubleQuoteValuePattern(),
        ];
    }
}
