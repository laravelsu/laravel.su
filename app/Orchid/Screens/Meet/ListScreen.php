<?php

namespace App\Orchid\Screens\Meet;

use App\Models\Meet;
use App\Notifications\SimpleMessageNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Components\Cells\Boolean;
use Orchid\Screen\Components\Cells\DateTimeSplit;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\SimpleMDE;
use Orchid\Screen\Fields\Switcher;
use Orchid\Screen\Layouts\Modal;
use Orchid\Screen\Layouts\Persona;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class ListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'approved' => Meet::with('author')
                ->filters()
                ->defaultSort('approved')
                ->paginate(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Мероприятия';
    }

    /**
     * A description of the screen to be displayed in the header.
     *
     * @return string|null
     */
    public function description(): ?string
    {
        return '';
    }

    public function permission(): ?iterable
    {
        return [
            'site.content',
        ];
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [];
    }

    /**
     * The screen's layout elements.
     *
     * @throws \ReflectionException
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::table('approved', [

                TD::make('name', 'Название')
                    ->width(200)
                    ->sort()
                    ->cantHide()
                    ->render(function (Meet $meet) {
                        return "<strong class='d-block'>".e($meet->name).'</strong>';
                    })->filter(Input::make()),

                TD::make('location', 'Адрес')
                    ->width(200)
                    ->filter(Input::make()),

                TD::make('start_date', 'Начало')
                    ->usingComponent(DateTimeSplit::class)
                    ->width(150)
                    ->sort(),

                TD::make('online', 'Онлайн')
                    ->width(100)
                    ->render(function (Meet $meet) {
                        if ($meet->online == 1) {
                            return Blade::render('<x-icon path="bs.check" height="1.5em" width="1.5em" />');
                        }

                        return '-';
                    })
                    ->sort(),

                TD::make('approved', 'Статус')
                    ->width(130)
                    ->usingComponent(Boolean::class, true: ' Утвержден', false: ' Ожидает')
                    ->sort(),

                TD::make('Добавил')
                    ->sort()
                    ->cantHide()
                    ->render(fn (Meet $meet) => new Persona($meet->author->presenter())),

                TD::make('created_at', __('Created'))
                    ->usingComponent(DateTimeSplit::class)
                    ->align(TD::ALIGN_RIGHT)
                    ->defaultHidden()
                    ->sort(),

                TD::make('updated_at', 'Последнее обновление')
                    ->usingComponent(DateTimeSplit::class)
                    ->align(TD::ALIGN_RIGHT)
                    ->sort(),

                TD::make(__('Actions'))
                    ->align(TD::ALIGN_CENTER)
                    ->width('100px')
                    ->render(fn (Meet $meet) => DropDown::make()
                        ->icon('bs.three-dots-vertical')
                        ->list([
                            Link::make('Веб-сайт')
                                ->href($meet->link ?? '')
                                ->target('_blank')
                                ->icon('bs.box-arrow-up-right'),

                            ModalToggle::make(__('Edit'))
                                ->icon('bs.pencil')
                                ->modal('editModal', [
                                    'meet' => $meet->id,
                                ])
                                ->modalTitle($meet->name)
                                ->method('update', [
                                    'meet' => $meet,
                                ]),

                            Button::make(__('Delete'))
                                ->icon('bs.trash3')
                                ->confirm(__('Once the account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.'))
                                ->method('remove', [
                                    'meet' => $meet->id,
                                ]),
                        ])),
            ]),

            Layout::modal('editModal', Layout::rows([

                Group::make([
                    Input::make('meet.name')
                        ->title('Название мероприятия')
                        ->placeholder('Название мероприятия'),

                    Input::make('meet.link')
                        ->type('url')
                        ->title('Ссылка'),
                ]),

                Group::make([
                    Input::make('meet.location')
                        ->title('Место проведения')
                        ->placeholder('Укажите адрес')
                        ->help('Если мероприятие проводится только онлайн, оставьте поле пустым.'),

                    Switcher::make('meet.online')
                        ->sendTrueOrFalse()
                        ->title('Онлайн'),
                ]),

                Group::make([
                    DateTimer::make('meet.start_date')
                        ->enableTime()
                        ->title('Дата и время начала'),
                    Switcher::make('meet.approved')
                        ->sendTrueOrFalse()
                        ->title('Статус')
                        ->placeholder('Одобренный')
                        ->help('Одобренные мероприятия будут отображаться на сайте'),
                ]),

                SimpleMDE::make('meet.description')
                    ->title('Описание')
                    ->placeholder('Добавьте краткое описание'),
            ]))
                ->size(Modal::SIZE_LG)
                ->async('asyncGetData')
                ->title('Информация')
                ->applyButton('Обновить'),
        ];
    }

    /**
     * @param Meet $meet
     *
     * @return array
     */
    public function asyncGetData(Meet $meet): array
    {
        return [
            'meet' => $meet,
        ];
    }

    /**
     * @param Meet    $meet
     * @param Request $request
     *
     * @return void
     */
    public function update(Meet $meet, Request $request): void
    {
        $meet->forceFill($request->input('meet'))->save();

        Toast::info('Информация обновлена');
    }

    /**
     * @param Meet $meet
     *
     * @return void
     */
    public function remove(Meet $meet): void
    {
        $meet->delete();

        $meet->author->notify(new SimpleMessageNotification('Событие "'.$meet->name.'" отклонено.'));

        Toast::info('Событие удалено');
    }
}
