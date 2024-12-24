<?php

namespace App\Orchid\Screens;

use App\Models\SecretSantaParticipant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Components\Cells\DateTimeSplit;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

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
            Layout::modal('edit-participant', Layout::rows([
                Input::make('participant.id')
                    ->title('Номер участника')
                    ->placeholder('Автоматически заполнено')
                    ->readonly()
                    ->help('Уникальный идентификатор участника. Поле только для чтения.'),

                Input::make('participant.tracking_number')
                    ->title('Трек-номер')
                    ->placeholder('Введите трек-номер посылки')
                    ->help('Используйте трек-номер для отслеживания посылки.'),

                Select::make('participant.status')
                    ->title('Статус участника')
                    ->options([
                        'new' => 'Новый',
                        'pending' => 'Ожидает',
                        'in_progress' => 'В процессе',
                        'done' => 'Завершён',
                    ])
                    ->empty('Выберите статус')
                    ->help('Укажите текущий статус участия.'),
            ]))
                ->method('update')
                ->deferred('loadParticipant'),


            Layout::table('participants', [
                TD::make('user.name', 'Пользователь (Санта)')
                    ->render(fn(SecretSantaParticipant $participant) => ModalToggle::make($participant->user->name)
                        ->modalTitle($participant->user->name)
                        ->modal('edit-participant', [
                            'participant' => $participant
                        ])
                    ),

                TD::make('receiver', 'Получатель')
                    ->render(fn(SecretSantaParticipant $participant) => $participant->receiver?->user->name ?? 'Не назначен'
                    ),

                /*
                TD::make('santa', 'Санта')
                    ->render(fn(SecretSantaParticipant $participant) => $participant->santa?->user->name ?? 'Не назначен'
                    ),
                */

                TD::make('receiver.address', 'Адрес')
                    ->width(200),

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

    /**
     * @param SecretSantaParticipant $participant
     * @return SecretSantaParticipant[]
     */
    public function loadParticipant(SecretSantaParticipant $participant)
    {
        return [
            'participant' => $participant
        ];
    }


    public function update(Request $request, SecretSantaParticipant $participant): void
    {
        $participant->fill($request->input('participant'))->save();

        Toast::info("Информация обновлена");
    }
}
