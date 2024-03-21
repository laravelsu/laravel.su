<?php

namespace App\Http\Controllers;

use App\Jobs\TelegramMessage;
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
        TelegramMessage::dispatch(
            $request->collect('message'),
            $telegramBot,
        );
    }
}
