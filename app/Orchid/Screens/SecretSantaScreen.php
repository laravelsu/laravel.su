<?php

namespace App\Orchid\Screens;

use App\Models\SecretSantaParticipant;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;

class SecretSantaScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'participants' => SecretSantaParticipant::with(['receiver', 'santa', 'user'])->all(),
        ];
    }

    /**
     * Display heading of the screen.
     *
     * @var string
     */
    public function name(): string
    {
        return 'Участники Тайного Санты';
    }

    /**
     * Screen description.
     *
     * @var string
     */
    public function description(): string
    {
        return 'Список участников и управление связями между ними';
    }

    /**
     * Screen layout.
     *
     * @return array
     */
    public function layout(): array
    {
        return [
            Layout::table('participants', [
                TD::make('user.name', 'Пользователь')
                    ->width('20%'),

                TD::make('receiver', 'Получатель')
                    ->width('20%')
                    ->render(fn(SecretSantaParticipant $participant) => $participant->receiver?->user->name ?? 'Не назначен'
                    ),

                TD::make('santa', 'Санта')
                    ->width('20%')
                    ->align(TD::ALIGN_CENTER)
                    ->render(fn(SecretSantaParticipant $participant) => $participant->santa?->user->name ?? 'Не назначен'
                    ),

                TD::make('address', 'Адрес')
                    ->width('25%'),

                TD::make('telegram', 'Telegram')
                    ->width('15%')
                    ->render(fn(SecretSantaParticipant $participant) => $participant->telegram
                        ? Link::make($participant->telegram)->href("https://t.me/{$participant->telegram}")
                        : '—'
                    ),

                TD::make('tracking_number', 'Трек-номер')
                    ->width('20%'),
            ])
        ];
    }
}
