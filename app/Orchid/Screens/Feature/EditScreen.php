<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Feature;

use App\Models\Enums\FeatureStatusEnum;
use App\Models\Feature;
use App\Notifications\FeatureNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Orchid\Screen\Action;
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
    /**
     * @var Feature
     */
    public $feature;

    /**
     * @var bool
     */
    private $isExisting = false;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Feature $feature): iterable
    {
        $this->feature = $feature;
        $this->isExisting = $feature->exists;

        $feature->load(['author']);

        // Convert enum to string value for Orchid select field
        if ($feature->exists) {
            // Get raw attributes to bypass enum casting
            $attributes = $feature->getAttributes();
            $feature->setRawAttributes(array_merge($attributes, [
                'status' => $feature->status->value,
            ]), true);
        }

        return [
            'feature' => $feature,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     */
    public function name(): ?string
    {
        return $this->isExisting ? 'Редактирование предложения' : 'Создание предложения';
    }

    /**
     * Display header description.
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
     * @return Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Удалить')
                ->icon('bs.trash3')
                ->confirm('Вы уверены, что хотите удалить это предложение?')
                ->method('remove')
                ->canSee($this->isExisting),
        ];
    }

    /**
     * @return \Orchid\Screen\Layout[]
     */
    public function layout(): iterable
    {
        return [
            Layout::block(
                Layout::rows([
                    Input::make('feature.title')
                        ->title('Название')
                        ->placeholder('Краткое название функции')
                        ->required(),

                    TextArea::make('feature.description')
                        ->title('Описание')
                        ->rows(5)
                        ->placeholder('Подробное описание функции')
                        ->required(),

                    Select::make('feature.status')
                        ->title('Статус')
                        ->fromEnum(FeatureStatusEnum::class, 'text')
                        ->required(),
                ]))
                ->title('Предложение функции')
                ->description('Управление предложением новой функции')
                ->commands(
                    Button::make($this->feature->exists ? 'Сохранить изменения' : 'Создать')
                        ->type(Color::SUCCESS)
                        ->icon('bs.check-circle')
                        ->method('save')
                ),
        ];
    }

    /**
     * @return RedirectResponse
     */
    public function save(Feature $feature, Request $request)
    {
        $request->validate([
            'feature.title'       => ['required', 'string', 'max:255'],
            'feature.description' => ['required', 'string'],
            'feature.status'      => ['required', 'string'],
        ]);

        $data = $request->input('feature');

        // If creating new feature, set user_id
        if (! $feature->exists) {
            $data['user_id'] = $request->user()->id;
        }

        $feature->forceFill($data)->save();

        if ($feature->isPublished() || $feature->isRejected() || $feature->isImplemented()) {
            $feature->author->notify(new FeatureNotification($feature));
        }

        Toast::info($feature->wasRecentlyCreated ? 'Предложение создано' : 'Информация обновлена');

        return redirect()->route('platform.feature');
    }

    /**
     * @throws \Exception
     *
     * @return RedirectResponse
     */
    public function remove(Feature $feature)
    {
        $feature->delete();

        Toast::info('Предложение удалено');

        $feature->author->notify(new FeatureNotification($feature));

        return redirect()->route('platform.feature');
    }
}
