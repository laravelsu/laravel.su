<?php

declare(strict_types=1);

namespace App\MarkDown\CustomHL\Languages\CustomBase;

use Tempest\Highlight\Languages\Base\BaseLanguage;
use App\MarkDown\CustomHL\Languages\CustomBase\Injections\AdditionTLInjection;
use App\MarkDown\CustomHL\Languages\CustomBase\Injections\AdditionTL2Injection;
use App\MarkDown\CustomHL\Languages\CustomBase\Injections\AdditionTL3Injection;
use App\MarkDown\CustomHL\Languages\CustomBase\Injections\DeletionTLInjection;
use App\MarkDown\CustomHL\Languages\CustomBase\Injections\DeletionTL2Injection;
use App\MarkDown\CustomHL\Languages\CustomBase\Injections\AdditionTLMultilineStartInjection;
use App\MarkDown\CustomHL\Languages\CustomBase\Injections\AdditionTLMultilineEndInjection;

abstract class CustomBaseLanguage extends BaseLanguage
{
    public function getInjections(): array
    {
        return [
            ...parent::getInjections(),
            new AdditionTLInjection(),
            new AdditionTL2Injection(),
            new AdditionTL3Injection(),
            new DeletionTLInjection(),
            new DeletionTL2Injection(),
            new AdditionTLMultilineStartInjection(),
            new AdditionTLMultilineEndInjection(),
        ];
    }

    public function getPatterns(): array
    {
        return [
            ...parent::getPatterns(),
        ];
    }
}
