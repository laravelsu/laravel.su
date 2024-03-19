<?php

namespace App\Http\Controllers;

use App\Services\TelegramBot;
use Illuminate\Http\Request;

class WebHookController extends Controller
{
    /**
     * Handle incoming Telegram webhook requests.
     *
     * @param \Illuminate\Http\Request  $request
     * @param \App\Services\TelegramBot $telegramBot
     *
     * @return void
     */
    public function telegram(Request $request, TelegramBot $telegramBot): void
    {
        $text = $request->input('message.text');
        $messageId = $request->input('message.message_id');
        $chatId = $request->input('message.chat.id');
        $from = $request->input('message.from.id');

        // Если сообщение - ответ на другое сообщение, то скорее всего это не спам.
        // Давайте не прерывать дискуссию и игнорируем его
        if ($request->has('message.reply_to_message') || $request->boolean('message.from.is_bot')) {
            return;
        }

        if (empty($text)) {
            $telegramBot->deleteMessage($chatId, $messageId);
            return;
        }

        if (! $telegramBot->isSpam($text)) {
            return;
        }

        $telegramBot->deleteMessage($chatId, $messageId);
        $telegramBot->muteUserInGroup($chatId, $from);
    }
}
