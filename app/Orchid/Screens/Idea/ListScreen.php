<?php

namespace App\Orchid\Screens\Idea;

use App\Models\Idea;
use App\Notifications\IdeaNotification;
use Illuminate\Http\Request;
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
    public function query(): iterable
    {
        return [
            'ideas' => Idea::with('author')
                ->filters()
                ->defaultSort('votes_count', 'desc')
                ->paginate(),
        ];
    }

    public function name(): ?string
    {
        return 'Идеи';
    }

    public function description(): ?string
    {
        return 'Управление идеями от пользователей';
    }

    public function commandBar(): iterable
    {
        return [
            Link::make('Создать')
                ->icon('bs.plus-circle')
                ->route('platform.ideas.create'),
        ];
    }

    public function permission(): ?iterable
    {
        return [
            'site.content',
        ];
    }

    public function layout(): iterable
    {
        return [
            Layout::table('ideas', [
                TD::make('title', 'Название')
                    ->width(200)
                    ->sort()
                    ->cantHide()
                    ->render(function (Idea $idea) {
                        return "<strong class='d-block'>".e($idea->title).'</strong>';
                    })->filter(Input::make()),

                TD::make('description', 'Описание')
                    ->width(300)
                    ->cantHide()
                    ->render(function (Idea $idea) {
                        return Str::of($idea->description)->words(15);
                    })->filter(Input::make()),

                TD::make('votes_count', 'Голоса')
                    ->sort()
                    ->render(function (Idea $idea) {
                        $class = $idea->votes_count > 0 ? 'text-success' : ($idea->votes_count < 0 ? 'text-danger' : '');

                        return "<span class='badge fs-5 {$class}'>{$idea->votes_count}</span>";
                    }),

                TD::make('status', 'Статус')
                    ->sort()
                    ->render(function (Idea $idea) {
                        $badges = [
                            'proposed'    => 'secondary',
                            'published'   => 'success',
                            'rejected'    => 'danger',
                            'implemented' => 'info',
                        ];
                        $statusValue = is_object($idea->status) ? $idea->status->value : $idea->status;
                        $badge = $badges[$statusValue] ?? 'secondary';

                        return "<span class='badge bg-{$badge}'>{$statusValue}</span>";
                    }),

                TD::make('Автор')
                    ->align(TD::ALIGN_CENTER)
                    ->cantHide()
                    ->render(fn (Idea $idea) => new Persona($idea->author->presenter())),

                TD::make('created_at', 'Создано')
                    ->usingComponent(DateTimeSplit::class)
                    ->align(TD::ALIGN_RIGHT)
                    ->sort(),

                TD::make('Действия')
                    ->align(TD::ALIGN_CENTER)
                    ->width('100px')
                    ->render(fn (Idea $idea) => DropDown::make()
                        ->icon('bs.three-dots-vertical')
                        ->list([
                            Link::make('Редактировать')
                                ->route('platform.ideas.edit', $idea->id)
                                ->icon('bs.pencil'),

                            Button::make('Удалить')
                                ->icon('bs.trash3')
                                ->confirm('Вы уверены, что хотите удалить эту идею?')
                                ->method('remove', [
                                    'idea' => $idea->id,
                                ]),
                        ])),
            ]),
        ];
    }

    public function remove(Request $request): void
    {
        $idea = Idea::findOrFail($request->input('idea'));

        $idea->delete();

        $idea->author->notify(new IdeaNotification($idea));

        Toast::info('Идея удалена');
    }
}
