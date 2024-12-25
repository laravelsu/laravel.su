<?php

namespace App\Achievements\Events;

use App\Achievements\Achievement;

class SecretSanta implements Achievement
{
    /**
     * Получить название достижения.
     *
     * @return string
     */
    public function name(): string
    {
        return 'Тайный Дед Мороз';
    }

    /**
     * Получить URL изображения достижения.
     *
     * @return string
     */
    public function image(): string
    {
        return asset('/img/achievements/gift.svg');
    }

    /**
     * Получить описание достижения.
     *
     * @return string
     */
    public function description(): string
    {
        return 'Подарил радость отправив анонимный подарок коллеге 🎁';
    }
}
