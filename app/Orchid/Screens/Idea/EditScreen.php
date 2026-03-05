<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Idea;

use App\Models\Enums\IdeaStatusEnum;
use App\Models\Idea;
use App\Notifications\IdeaNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class EditScreen extends Screen
{
    public $idea;

    private bool $isExisting = false;

    public function query(?Idea $idea = null): iterable
    {
        $idea = $idea ?? new Idea;
        $this->idea = $idea;
        $this->isExisting = $idea->exists;

        if ($idea->exists) {
            $idea->load(['author']);
        }

        if ($idea->exists) {
            $attributes = $idea->getAttributes();
            $idea->setRawAttributes(array_merge($attributes, [
                'status' => $idea->status->value,
            ]), true);
        }

        return [
            'idea' => $idea,
        ];
    }

    public function name(): ?string
    {
        return $this->isExisting ? 'Редактирование идеи' : 'Создание идеи';
    }

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

    public function commandBar(): iterable
    {
        return [
            Button::make('Удалить')
                ->icon('bs.trash3')
                ->confirm('Вы уверены, что хотите удалить эту идею?')
                ->method('remove')
                ->canSee($this->isExisting),
        ];
    }

    public function layout(): iterable
    {
        return [
            Layout::block(
                Layout::rows([
                    Input::make('idea.title')
                        ->title('Название')
                        ->placeholder('Краткое название идеи')
                        ->required(),

                    TextArea::make('idea.description')
                        ->title('Описание')
                        ->rows(5)
                        ->placeholder('Подробное описание идеи')
                        ->required(),

                    Select::make('idea.status')
                        ->title('Статус')
                        ->fromEnum(IdeaStatusEnum::class, 'text')
                        ->required(),
                ]))
                ->title('Идея')
                ->description('Управление идеей')
                ->commands(
                    Button::make($this->idea->exists ? 'Сохранить изменения' : 'Создать')
                        ->type(Color::SUCCESS)
                        ->icon('bs.check-circle')
                        ->method('save')
                ),
        ];
    }

    public function save(Request $request): RedirectResponse
    {
        $idea = $request->route('idea');

        if ($idea === null) {
            $idea = new Idea;
        }

        $request->validate([
            'idea.title'       => ['required', 'string', 'max:255'],
            'idea.description' => ['required', 'string'],
            'idea.status'      => ['required', 'string'],
        ]);

        $data = $request->input('idea');

        if (! $idea->exists) {
            $data['user_id'] = $request->user()->id;
        }

        $idea->forceFill($data)->save();

        if ($idea->isPublished() || $idea->isRejected() || $idea->isImplemented()) {
            $idea->author->notify(new IdeaNotification($idea));
        }

        Toast::info($idea->wasRecentlyCreated ? 'Идея создана' : 'Информация обновлена');

        return redirect()->route('platform.ideas');
    }

    public function remove(Request $request): RedirectResponse
    {
        $idea = $request->route('idea');

        if ($idea instanceof Idea) {
            $idea->delete();
            $idea->author->notify(new IdeaNotification($idea));
        }

        Toast::info('Идея удалена');

        return redirect()->route('platform.ideas');
    }
}
