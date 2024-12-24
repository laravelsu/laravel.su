<?php

namespace App\Orchid\Screens;

use App\Models\SecretSantaParticipant;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Components\Cells\DateTimeSplit;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
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
            'participants' => SecretSantaParticipant::with(['receiver', 'santa', 'user'])
                ->filters()
                ->get(),
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
                        'new'         => 'Новый',
                        'in_progress' => 'В процессе',
                        'done'        => 'Завершён',
                    ])
                    ->empty('Выберите статус')
                    ->help('Укажите текущий статус участия.'),
            ]))
                ->method('update')
                ->deferred('loadParticipant'),

            Layout::table('participants', [
                TD::make('user.name', 'Санта')
                    ->width(100)
                    ->render(fn (SecretSantaParticipant $participant) => ModalToggle::make($participant->user->name)
                        ->modalTitle($participant->user->name)
                        ->method('update')
                        ->modal('edit-participant', [
                            'participant' => $participant->id,
                        ])
                    ),

                TD::make('receiver', 'Получатель')
                    ->width(100)
                    ->render(fn (SecretSantaParticipant $participant) => $participant->receiver?->user->name ?? 'Не назначен'
                    ),

                TD::make('receiver.address', 'Адрес')
                    ->defaultHidden()
                    ->width(200),

                TD::make('receiver.telegram', 'Контакты')
                    ->width(200)
                    ->render(function (SecretSantaParticipant $participant) {
                        return sprintf(
                            "<strong class='d-block'>%s</strong><a href='tel:%s'>%s</a>",
                            e($participant->receiver?->telegram),
                            e($participant->receiver?->phone),
                            e($participant->receiver?->phone)
                        );
                    }),

                TD::make('tracking_number', 'Трек-номер')->sort(),

                TD::make('status', 'Статус')
                    ->width(150)
                    ->sort()
                    ->render(fn (SecretSantaParticipant $participant) => $participant->status === 'done'
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
     *
     * @return SecretSantaParticipant[]
     */
    public function loadParticipant(SecretSantaParticipant $participant)
    {
        return [
            'participant' => $participant,
        ];
    }

    public function update(Request $request, SecretSantaParticipant $participant): void
    {
        $participant->forceFill($request->input('participant'))->save();

        Toast::info('Информация обновлена');
    }
}
