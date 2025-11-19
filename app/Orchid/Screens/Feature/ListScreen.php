<?php

namespace App\Orchid\Screens\Feature;

use App\Models\Feature;
use App\Notifications\FeatureNotification;
use Illuminate\Support\Str;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Components\Cells\DateTimeSplit;
use Orchid\Screen\Fields\Input;
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
            'features' => Feature::with('author')
                ->filters()
                ->defaultSort('votes_count', 'desc')
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
        return 'Предложения функций';
    }

    /**
     * A description of the screen to be displayed in the header.
     *
     * @return string|null
     */
    public function description(): ?string
    {
        return 'Управление предложениями новых функций от пользователей';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Создать')
                ->icon('bs.plus-circle')
                ->route('platform.feature.create'),
        ];
    }

    public function permission(): ?iterable
    {
        return [
            'site.content',
        ];
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
            Layout::table('features', [

                TD::make('title', 'Название')
                    ->width(200)
                    ->sort()
                    ->cantHide()
                    ->render(function (Feature $feature) {
                        return "<strong class='d-block'>".e($feature->title).'</strong>';
                    })->filter(Input::make()),

                TD::make('description', 'Описание')
                    ->width(300)
                    ->cantHide()
                    ->render(function (Feature $feature) {
                        return Str::of($feature->description)->words(15);
                    })->filter(Input::make()),

                TD::make('votes_count', 'Голоса')
                    ->sort()
                    ->render(function (Feature $feature) {
                        $class = $feature->votes_count > 0 ? 'text-success' : ($feature->votes_count < 0 ? 'text-danger' : '');

                        return "<span class='badge fs-5 {$class}'>{$feature->votes_count}</span>";
                    }),

                TD::make('status', 'Статус')
                    ->sort()
                    ->render(function (Feature $feature) {
                        $badges = [
                            'proposed'    => 'secondary',
                            'published'   => 'success',
                            'rejected'    => 'danger',
                            'implemented' => 'info',
                        ];
                        $statusValue = is_object($feature->status) ? $feature->status->value : $feature->status;
                        $badge = $badges[$statusValue] ?? 'secondary';

                        return "<span class='badge bg-{$badge}'>{$statusValue}</span>";
                    }),

                TD::make('Автор')
                    ->align(TD::ALIGN_CENTER)
                    ->cantHide()
                    ->render(fn (Feature $feature) => new Persona($feature->author->presenter())),

                TD::make('created_at', 'Создано')
                    ->usingComponent(DateTimeSplit::class)
                    ->align(TD::ALIGN_RIGHT)
                    ->sort(),

                TD::make('Действия')
                    ->align(TD::ALIGN_CENTER)
                    ->width('100px')
                    ->render(fn (Feature $feature) => DropDown::make()
                        ->icon('bs.three-dots-vertical')
                        ->list([
                            Link::make('Редактировать')
                                ->route('platform.feature.edit', $feature->id)
                                ->icon('bs.pencil'),

                            Button::make('Удалить')
                                ->icon('bs.trash3')
                                ->confirm('Вы уверены, что хотите удалить это предложение?')
                                ->method('remove', [
                                    'feature' => $feature->id,
                                ]),
                        ])),
            ]),

        ];
    }

    /**
     * @param Feature $feature
     *
     * @return void
     */
    public function remove(Feature $feature): void
    {
        $feature->delete();

        $feature->author->notify(new FeatureNotification($feature));

        Toast::info('Предложение удалено');
    }
}
