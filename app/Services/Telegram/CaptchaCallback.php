<?php

namespace App\Services\Telegram;

final readonly class CaptchaCallback
{
    public function __construct(
        public int $checkId,
        public int $from,
        public int $messageId,
        public int $chatId,
    ) {}
}
