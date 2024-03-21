<?php

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Cache;
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
     * Check if a user is spammer via Combot Anti-Spam (CAS)
     *
     * @param $userId
     *
     * @return bool
     */
    public function checkByCAS($userId): bool
    {
        return Cache::remember('cas-user-'.$userId, now()->addHours(5), function () use ($userId) {
            return Http::get('https://api.cas.chat/check', [
                'user_id' => $userId,
            ])->json('ok', true) === false;
        });
    }

    /**
     * Check if a message is spam.
     *
     * @param string|null $message
     * @param null        $userId
     *
     * @return bool
     */
    public function isSpam(?string $message, $userId = null): bool
    {
        if (empty($message)) {
            return false;
        }

        if ($userId !== null && $this->checkByCAS($userId)) {
            return true;
        }

        $detector = new SpamDetector($message);

        return $detector->isSpam();
    }
}
