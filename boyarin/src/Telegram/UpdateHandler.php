<?php

declare(strict_types=1);

namespace Boyarin\Telegram;

interface UpdateHandler
{
    /**
     * Process an incoming Telegram update.
     *
     * @param  array<string, mixed>  $update
     */
    public function process(array $update): void;
}
