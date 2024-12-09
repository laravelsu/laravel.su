<?php

use App\Docs;
use App\Jobs\UpdateStatusPackages;
use App\Models\CodeSnippet;
use App\Models\Package;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('app:checkout-latest-docs', function () {
    $docsUrl = config('services.github.docs_repo_url');

    // Update existing clones
    collect(Docs::SUPPORT_VERSIONS)
        ->filter(fn (string $version) => Storage::disk('docs')->exists($version))
        ->every(fn (string $version) => Process::path(storage_path('docs/'.$version))->run('git reset --hard HEAD && git pull'));

    // Clone a new version if not already present
    collect(Docs::SUPPORT_VERSIONS)
        ->filter(fn (string $version) => ! Storage::disk('docs')->exists($version))
        ->every(fn (string $version) => Process::path(storage_path('docs'))
            ->run("git clone --single-branch --branch '$version' $docsUrl '$version'"));

})->purpose('Checkout the latest Laravel docs');

Artisan::command('app:update-packages', function () {
    Package::approved()->chunk(100, function (Collection $packages) {
        $packages->each(fn ($package) => UpdateStatusPackages::dispatch($package));
    });
})->purpose('Update information about users packages');


Artisan::command('santa:start', function () {
    $participants = \App\Models\SecretSantaParticipant::inRandomOrder()->get();

    foreach ($participants as $index => $participant) {
        $nextIndex = ($index + 1) % $participants->count();

        // Если участник остается без пары, выводим информацию
        if ($participants->count() % 2 !== 0 && $index === $participants->count() - 1) {
            $participant->receiver_id = null;
            $this->info("👤 Участник без пары: {$participant->user->name} (ID: {$participant->id})");
        } else {
            $participant->receiver_id = $participants[$nextIndex]->user_id;
        }

        $participant->save();
    }

    $this->info("🎁 Пары успешно назначены!");
})->purpose('Назначение пар для участников Тайного Санты');


/*
|--------------------------------------------------------------------------
| Schedule
|--------------------------------------------------------------------------
|
| Here you may define your schedule. This is where you can define a list
|
*/

// Ежедневные задачи
Facades\Schedule::command('app:checkout-latest-docs')->daily();
Facades\Schedule::command('app:compare-document')->daily();
Facades\Schedule::command('app:update-packages')->daily();

// Ежедневная очистка логов и просмотров
Facades\Schedule::command('activitylog:clean')->daily();
Facades\Schedule::command('telescope:prune')->daily();

// Ежедневная очистка моделей, таких как CodeSnippet
Facades\Schedule::command('model:prune', [
    '--model' => [
        CodeSnippet::class,
    ],
])->daily();

// Оптимизация SQLite смотри https://www.sqlite.org/pragma.html#pragma_optimize
Facades\Schedule::command('sqlite:optimize')->everyFourHours();
Facades\Schedule::command('sqlite:vacuum')->everyFourHours();

// Перевод достижений каждую неделю по выходным
Facades\Schedule::command('app:achievements-translation')
    ->weeklyOn([Schedule::SATURDAY, Schedule::SUNDAY]);

// Ежедневное создание и очистка резервных копий в определенное время
Facades\Schedule::command('backup:clean')->daily()->at('01:00');
Facades\Schedule::command('backup:run')->daily()->at('01:30');
