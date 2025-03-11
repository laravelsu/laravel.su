<?php

namespace App\Http\Controllers;

use App\Jobs\TelegramMessage;
use App\Services\Telegram\CaptchaCallback;
use Illuminate\Http\Request;

class WebHookController extends Controller
{
    /**
     * Handle incoming Telegram webhook requests.
     *
     * @param \Illuminate\Http\Request           $request
     * @param \App\Services\Telegram\TelegramBot $telegramBot
     *
     * @return void
     */
    public function telegram(Request $request): void
    {
        $captcha = null;
        if ($request->has('callback_query')) {
            $data = $request->collect('callback_query');

            $captcha = new CaptchaCallback(
                checkId: $data->dot()->get('data'),
                from: $data->dot()->get('from.id'),
                messageId: $data->dot()->get('message.message_id'),
                chatId: $data->dot()->get('message.chat.id'),
            );
        }

        TelegramMessage::dispatch(
            $request->collect('message'),
            null,
        );

    }
}
