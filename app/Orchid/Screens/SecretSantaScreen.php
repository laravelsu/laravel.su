<?php

namespace App\Orchid\Screens;

use App\Models\SecretSantaParticipant;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Components\Cells\DateTimeSplit;
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
            'participants' => SecretSantaParticipant::with(['receiver', 'santa', 'user'])->get(),
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
                TD::make('user.name', 'Пользователь (Санта)'),

                TD::make('receiver', 'Получатель')
                    ->render(fn(SecretSantaParticipant $participant) => $participant->receiver?->user->name ?? 'Не назначен'
                    ),

                /*
                TD::make('santa', 'Санта')
                    ->render(fn(SecretSantaParticipant $participant) => $participant->santa?->user->name ?? 'Не назначен'
                    ),
                */

                TD::make('receiver.address', 'Адрес'),

                TD::make('receiver.telegram', 'Telegram')
                    ->render(fn(SecretSantaParticipant $participant) => $participant->receiver?->telegram
                        ? Link::make($participant->receiver?->telegram)->href("https://t.me/{$participant->receiver?->telegram}")
                        : '—'
                    ),

                TD::make('receiver.tracking_number', 'Трек-номер'),

                TD::make('receiver.phone', 'Номер телефона'),

                TD::make('status', 'Статус')
                ->render(fn(SecretSantaParticipant $participant) => $participant->status === 'done'
                    ? '✅ Завершён'
                    : '⏳ Ожидает'
                ),

                TD::make('updated_at', 'Последнее обновление')
                    ->defaultHidden()
                    ->usingComponent(DateTimeSplit::class)
                    ->align(TD::ALIGN_RIGHT)
                    ->sort(),
            ]),
        ];
    }
}
