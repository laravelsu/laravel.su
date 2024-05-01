<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use NotificationChannels\Telegram\TelegramMessage;
use Orchid\Support\Facades\Toast;

class ConsultantController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('pages.consultants');
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'    => 'required|string|min:2',
            'contact' => 'required|string|min:2',
            'message' => 'required|string|min:10',
        ]);

        TelegramMessage::create()
            ->to(config('services.telegram-bot-api.chat_id'))
            ->line('*🛟 Запрос на консультацию*')
            ->line('`')
            ->escapedLine($request->input('name'))
            ->escapedLine($request->input('contact'))
            ->line('`')
            ->line('`')
            ->escapedLine($request->input('message'))
            ->line('`')
            ->send();

        Toast::success('Отлично! Постараемся подобрать лучшего специалиста который свяжется как можно быстрее.')
            ->disableAutoHide();

        return redirect()->route('home');
    }
}
