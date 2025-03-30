<?php

namespace App\Http\Controllers;

use App\Jobs\TelegramMessage;
use App\Services\Telegram\CaptchaCallback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
