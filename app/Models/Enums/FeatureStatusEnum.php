<?php

namespace App\Models\Enums;

enum FeatureStatusEnum: string
{
    case Proposed = 'proposed';
    case Published = 'published';
    case Rejected = 'rejected';
    case Implemented = 'implemented';

    /**
     * Получить текстовое представление типа Feature.
     *
     * @return string
     */
    public function text(): string
    {
        return match ($this) {
            self::Proposed                  => 'На рассмотреннии',
            self::Published                 => 'Опубликовано',
            self::Rejected                  => 'Отменено',
            self::Implemented               => 'Реализовано',
        };
    }
}
