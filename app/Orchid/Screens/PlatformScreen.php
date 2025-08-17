<?php

declare(strict_types=1);

namespace App\Orchid\Screens;

use App\Models\CodeSnippet;
use App\Models\Comment;
use App\Models\Document;
use App\Models\IdeaKey;
use App\Models\IdeaRequest;
use App\Models\Meet;
use App\Models\Package;
use App\Models\Position;
use App\Models\Post;
use App\Models\User;
use App\Orchid\Layouts\BasicIndicators;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class PlatformScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        $start = Carbon::now()->subDays(30);
        $end = Carbon::now();

        return [
            'basicIndicators' => [
                User::countByDays($start, $end)->toChart('Пользователи'),
                Comment::countByDays($start, $end)->toChart('Комментарии'),
                CodeSnippet::countByDays($start, $end)->toChart('Pastebin'),
            ],
            'content'         => [
                Post::countByDays($start, $end)->toChart('Посты'),
                Meet::countByDays($start, $end)->toChart('Мероприятия'),
                Package::countByDays($start, $end)->toChart('Пакеты'),
                Position::countByDays($start, $end)->toChart('Вакансии'),
            ],
            'docs'            => Document::selectRaw('version, SUM(behind) as behind')
                ->groupBy('version')
                ->pluck('behind', 'version')
                ->mapWithKeys(fn ($value, $key) => [Str::of($key)->replace('.', '')->toString() => $value.' изменений']),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     */
    public function name(): ?string
    {
        return 'Статистика';
    }

    /**
     * Display header description.
     */
    public function description(): ?string
    {
        return '';
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
     * @return \Orchid\Screen\Layout[]
     */
    public function layout(): iterable
    {
        return [
            Layout::metrics([
                'Документация 10.x отстаёт' => 'docs.10x',
                'Документация 11.x отстаёт' => 'docs.11x',
            ]),

            BasicIndicators::make('basicIndicators')
                ->description('График отображает количество зарегистрированных пользователей и оставленных комментариев по дням')
                ->title('Вовлеченность пользователей'),

            BasicIndicators::make('content')
                ->title('Контент')
                ->description('Количество новых стаей,пакетов,мероприятий и вакансий по дням'),
        ];
    }
}
