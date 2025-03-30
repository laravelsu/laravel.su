<?php

namespace App\Http\Controllers;

use App\Services\TelegramBot;
use Illuminate\Http\Request;

class GithubWebHookController extends Controller
{
    /**
     * Handle incoming Telegram webhook requests.
     *
     * @param \Illuminate\Http\Request $request
     * @param TelegramBot              $telegramBot
     *
     * @throws \Throwable
     *
     * @return void
     */
    public function release(Request $request, TelegramBot $telegramBot)
    {
        $payload = $request->all();

        if ($payload['action'] === 'published') {
            $release = $payload['release'];
            $repo = $payload['repository'];

            $message = view('telegram.github-release-notification', [
                'repo'    => $repo['full_name'],
                'version' => $release['tag_name'],
                'title'   => $release['name'],
                'body'    => $release['body'],
                'url'     => $release['html_url'],
            ])->render();

            collect(config('telegram.chats'))
                ->where('orchid_release', true)
                ->each(fn ($subscriber) => $telegramBot->sendMessageToChat($subscriber['id'], $message));
        }
    }
}
