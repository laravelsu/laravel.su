<?php

namespace App\Http\Controllers;

use App\Services\TelegramBot;
use Illuminate\Http\Request;

class GithubWebHookController extends Controller
{
    /**
     * Handle incoming GitHub webhook requests.
     *
     * @param \Illuminate\Http\Request $request
     * @param TelegramBot              $telegramBot
     *
     * @throws \Throwable
     *
     * @return void
     */
    public function release(Request $request, TelegramBot $telegramBot): void
    {
        abort_if($request->input('action') !== 'published', 400);

        $message = view('telegram.github-release-notification', [
            'repo'    => $request->input('repository.full_name'),
            'version' => $request->input('release.tag_name'),
            'title'   => $request->input('release.name'),
            'body'    => $request->input('release.body'),
            'url'     => $request->input('release.html_url'),
        ])->render();

        collect(config('telegram.chats'))
            ->where('orchid_release', true)
            ->each(fn ($subscriber) => $telegramBot->sendMessageToChat($subscriber['id'], $message));
    }
}
