<?php

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class TelegramBot
{
    private $token;

    /**
     * Construct a new TelegramBot instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->token = config('services.telegram-bot-api.token');
    }

    /**
     * Mute a user in a group chat.
     *
     * @param int $chatId
     * @param int $userId
     * @param int $muteDuration
     *
     * @return \Illuminate\Http\Client\Response
     */
    public function muteUserInGroup($chatId, $userId, $muteDuration = 60): Response
    {
        $url = "https://api.telegram.org/bot{$this->token}/restrictChatMember";

        return Http::post($url, [
            'chat_id'                   => $chatId,
            'user_id'                   => $userId,
            'until_date'                => time() + $muteDuration,
            'can_send_messages'         => false,
            'can_send_media_messages'   => false,
            'can_send_other_messages'   => false,
            'can_add_web_page_previews' => false,
        ]);
    }

    /**
     * Delete a message from a chat.
     *
     * @param int $chatId
     * @param int $messageId
     *
     * @return \Illuminate\Http\Client\Response
     */
    public function deleteMessage($chatId, $messageId): Response
    {
        $url = "https://api.telegram.org/bot{$this->token}/deleteMessage";

        return Http::post($url, [
            'chat_id'    => $chatId,
            'message_id' => $messageId,
        ]);
    }

    /**
     * Check if a message is spam.
     *
     * @param string|null $message
     *
     * @return bool
     */
    public function isSpam(?string $message): bool
    {
        if (empty($message)) {
            return false;
        }

        $detector = new SpamDetector($message);

        return $detector->isSpam();
    }
}
