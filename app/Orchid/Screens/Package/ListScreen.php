<?php

namespace App\Orchid\Screens\Package;

use App\Casts\PackageTypeEnum;
use App\Casts\PostTypeEnum;
use App\Models\Package;
use App\Models\User;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Components\Cells\Boolean;
use Orchid\Screen\Components\Cells\DateTimeSplit;
use Orchid\Screen\Components\Cells\Number;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\Switcher;
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
            'approved' => Package::with('author')
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
        return 'Управление пакетами';
    }

    /**
     * A description of the screen to be displayed in the header.
     *
     * @return string|null
     */
    public function description(): ?string
    {
        return 'Этот раздел позволяет вам управлять каталогом пакетов, которые будут отображаться на веб-сайте.';
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
     * @return \Orchid\Screen\Layout[]|string[]
     * @throws \ReflectionException
     */
    public function layout(): iterable
    {
        return [
            Layout::table('approved', [

                TD::make('Название')
                    ->width(200)
                    ->cantHide()
                    ->render(function (Package $package) {
                        return "<strong class='d-block'>$package->name</strong><span class='text-muted'>{$package->type->text()}</span>";
                    }),

                TD::make('description','Описание')
                    ->width(300),

                /*
                TD::make('Статистика')
                    ->width(120)
                    ->render(function (Package $package) {
                        return "<strong class='d-block'>⭐ $package->stars</strong><span class='text-muted'>✈️ $package->downloads</span>";
                    }),
                */


                TD::make('approved', 'Статус')
                    ->width(130)
                    ->usingComponent(Boolean::class, true: ' Утвержден', false: ' Ожидает'),

                TD::make('Участник')
                    ->sort()
                    ->cantHide()
                    ->render(fn(Package $package) => new Persona($package->author->presenter())),

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
                    ->render(fn(Package $package) => DropDown::make()
                        ->icon('bs.three-dots-vertical')
                        ->list([
                            Link::make("Веб-сайт")
                                ->href($package->website)
                                ->target('_blank')
                                ->icon('bs.box-arrow-up-right'),

                            ModalToggle::make(__('Edit'))
                                ->icon('bs.pencil')
                                ->modal('editModal')
                                ->modalTitle($package->name)
                                ->method('update', [
                                    'package' => $package,
                                ])
                                ->asyncParameters([
                                    'package' => $package->id,
                                ]),

                            Button::make(__('Delete'))
                                ->icon('bs.trash3')
                                ->confirm(__('Once the account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.'))
                                ->method('remove', [
                                    'id' => $package->id,
                                ]),
                        ])),
            ]),

            Layout::modal('editModal', Layout::rows([
                Input::make('package.name')
                    ->title('Имя пакета')
                    ->placeholder('Имя пакета')
                    ->help('Имя пакета которое зарегистрировано на Packagist, например orchid/platform, также используется в файле composer.json вашего проекта.'),

                Select::make('package.type')
                    ->title('Укажите категорию')
                    ->fromEnum(PackageTypeEnum::class, 'text')
                    ->help('Выберите наиболее подходящую категорию для вашего пакета.'),

                Switcher::make('package.approved')
                    ->sendTrueOrFalse()
                    ->title('Статус')
                    ->placeholder('Одобренный')
                    ->help('Одобренные пакеты будут видны на сайте и получать автоматическое обновление'),
            ]))
                ->async('asyncGetData')
                ->title('Информация')
                ->applyButton('Обновить'),
        ];
    }

    /**
     * @param string $welcome
     *
     * @return array
     */
    public function asyncGetData(Package $package): array
    {
        return [
            'package' => $package,
        ];
    }

    /**
     * @param \App\Models\Package      $package
     * @param \Illuminate\Http\Request $request
     *
     * @return void
     */
    public function update(Package $package, Request $request): void
    {
        $package->forceFill($request->input('package'))->save();

        Toast::info("Информация обновлена");
    }

    /**
     * @param \App\Models\Package $package
     *
     * @return void
     */
    public function remove(Package $package): void
    {
        $package->delete();

        Toast::info("Пакет удален");
    }
}