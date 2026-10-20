<?php

declare(strict_types=1);

namespace Boyarin\Telegram;

use React\Promise\PromiseInterface;

interface TelegramGateway
{
    /**
     * Determine whether CAS identifies the Telegram user as a spammer.
     *
     * @return PromiseInterface<bool>
     */
    public function isKnownSpammer(int $userId): PromiseInterface;

    /**
     * Delete a message from a Telegram chat.
     *
     * @return PromiseInterface<mixed>
     */
    public function deleteMessage(int $chatId, int $messageId): PromiseInterface;

    /**
     * Temporarily prevent a Telegram user from sending messages.
     *
     * @return PromiseInterface<mixed>
     */
    public function muteUser(int $chatId, int $userId): PromiseInterface;
}
