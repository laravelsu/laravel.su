<?php

declare(strict_types=1);

namespace App\MarkDown\CustomHL\Languages\Xml;

use App\MarkDown\CustomHL\Languages\CustomBase\CustomBaseLanguage;
use App\MarkDown\CustomHL\Languages\Xml\Patterns\XmlAttributePattern;
use App\MarkDown\CustomHL\Languages\Xml\Patterns\XmlAttributeValuePattern;
use App\MarkDown\CustomHL\Languages\Xml\Patterns\XmlAttributeDelimeterPattern;
use App\MarkDown\CustomHL\Languages\Xml\Patterns\XmlCloseTagPattern;
use App\MarkDown\CustomHL\Languages\Xml\Patterns\XmlCommentPattern;
use App\MarkDown\CustomHL\Languages\Xml\Patterns\XmlOpenTagPattern;
use App\MarkDown\CustomHL\Languages\Xml\Patterns\XmlStartOpenTagPattern;
use App\MarkDown\CustomHL\Languages\Xml\Patterns\XmlEndCloseTagPattern;
use App\MarkDown\CustomHL\Languages\Xml\Patterns\XmlDoctypePattern;
use App\MarkDown\CustomHL\Languages\Xml\Patterns\XmlDoctypeTypePattern;

class XmlLanguage extends CustomBaseLanguage
{
    public function getName(): string
    {
        return 'xml';
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
            new XmlDoctypePattern(),
            new XmlDoctypeTypePattern(),
            new XmlOpenTagPattern(),
            new XmlCloseTagPattern(),
            new XmlAttributePattern(),
            new XmlAttributeValuePattern(),
            new XmlAttributeDelimeterPattern(),
            new XmlCommentPattern(),

            new XmlStartOpenTagPattern(),
            new XmlEndCloseTagPattern(),
        ];
    }
}
