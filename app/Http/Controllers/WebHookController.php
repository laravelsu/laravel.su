<?php

namespace App\Http\Controllers;

use App\Jobs\TelegramMessage;
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
        TelegramMessage::dispatch(
            $request->collect('message'),
        );

    }
}
