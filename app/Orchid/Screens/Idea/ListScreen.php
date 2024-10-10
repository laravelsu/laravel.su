<?php

namespace App\Orchid\Screens\Idea;

use App\Models\IdeaKey;
use App\Models\IdeaRequest;
use App\Orchid\Layouts\BasicIndicators;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Components\Cells\Boolean;
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
        $start = Carbon::now()->subMonth();
        $end = Carbon::now();

        return [
            'charts'       => [
                IdeaRequest::countByDays($start, $end)->toChart('Запросы'),
                IdeaKey::where('activated', 1)->countByDays($start, $end, 'updated_at')->toChart('Одобрения'),
            ],
            'ideaRequests' => IdeaRequest::with(['user', 'key'])
                ->defaultSort('created_at', 'desc')
                ->filters()
                ->paginate(),
            'metrics'      => [
                'used_keys_month' => IdeaKey::where('activated', 1)->whereDate('updated_at', '>', $start)->count(),
                'unused_keys'     => IdeaKey::where('activated', 0)->count(),
                'used_keys'       => IdeaKey::where('activated', 1)->count(),
            ],
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Запросы ключей';
    }

    /**
     * A description of the screen to be displayed in the header.
     *
     * @return string|null
     */
    public function description(): ?string
    {
        return 'Вы можете просмотреть детали каждого запроса и статус выдачи ключа.';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make('Добавить ключи')
                ->modal('addKeys')
                ->method('addKeys')
                ->icon('file-earmark-arrow-up'),
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::split([
                BasicIndicators::make('charts')
                    ->description('Отслеживайте динамику запросов и выдачи ключей. Старайтесь не откладывать выдачу на последний день — это отражается на пиках на графике.')
                    ->title('Статистика за последние 30 дней')
                    ->height(285),

                Layout::metrics([
                    'Выдано за месяц:'                => 'metrics.used_keys_month',
                    'Общее количество выданных:'      => 'metrics.used_keys',
                    'Неактивированные ключи:'         => 'metrics.unused_keys',
                ]),
            ])->ratio('80/20'),

            Layout::table('ideaRequests', $this->getIdeaRequestsTableColumns()),

            Layout::modal('addKeys', Layout::rows([
                Input::make('file')
                    ->type('file')
                    ->accept('.txt')
                    ->required()
                    ->title('Выберите файл с ключами для Laravel Idea')
                    ->help('Пожалуйста, выберите файл формата .txt, содержащий ключи, где каждый ключ на новой строчке.'),
            ]))->title('Загрузка ключей'),
        ];
    }

    /**
     * Get the columns for the idea requests table.
     *
     * @throws \ReflectionException
     *
     * @return TD[]
     */
    private function getIdeaRequestsTableColumns(): array
    {
        return [
            TD::make('Пользователь')
                ->cantHide()
                ->width(230)
                ->render(fn (IdeaRequest $ideaRequest) => new Persona($ideaRequest->user->presenter())),

            TD::make('message', 'Сообщение')
                ->alignLeft()
                ->render(fn (IdeaRequest $ideaRequest) => Str::of($ideaRequest->message)->trim()->words(10)
                    .Link::make()->class('hidden')
                        ->route('platform.idea.request', $ideaRequest->id)
                        ->stretched())
                ->width(400),

            TD::make('city', 'Город')
                ->width(120)
                ->align(TD::ALIGN_RIGHT),

            TD::make('key', 'Статус')
                ->width(50)
                ->align(TD::ALIGN_RIGHT)
                ->render(function (IdeaRequest $ideaRequest) {
                    return \Orchid\Support\Blade::renderComponent(Boolean::class, ['value' => $ideaRequest->key()->exists()]);
                }),

            TD::make('created_at', 'Создано')
                ->width(120)
                ->usingComponent(DateTimeSplit::class)
                ->align(TD::ALIGN_RIGHT)
                ->sort(),

            TD::make('updated_at', 'Последнее обновление')
                ->width(120)
                ->usingComponent(DateTimeSplit::class)
                ->defaultHidden()
                ->align(TD::ALIGN_RIGHT)
                ->sort(),
        ];
    }

    /**
     * Add keys from a file to the database.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return void
     */
    public function addKeys(Request $request): void
    {
        // Validate the incoming request.
        $request->validate([
            'file' => 'required|file|mimes:txt',
        ]);

        // Read the keys from the uploaded file.
        $keys = file($request->file('file')->getRealPath(), FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        // Prepare keys for insertion into the database.
        $keysForInsertion = collect($keys)
            ->map(fn ($key) => ['id' => Str::uuid(), 'key' => $key])
            ->toArray();

        // Insert keys into the database, ignoring duplicates.
        DB::table('idea_keys')->insertOrIgnore($keysForInsertion);

        // Display a success message.
        Toast::info('Ключи успешно добавлены! Теперь они доступны для использования.');
    }
}
