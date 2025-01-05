@extends('layout')
@section('title', 'Чистый код')
@section('description', 'Код должен быть понятен всем членам команды и легко читаем для разработчиков,  которые могут внести изменения в него')
@section('content')

@php
$markdownExampleCode = '
```php
// Получаем инсайты трендов для маркетинговой кампании
$trendInsights = $this->getTrendInsights();

// Запускаем кампанию с полученными данными
$campaignResults = $this->executeCampaign($trendInsights);

// Возвращаем результаты кампании
return response()->json([
    \'status\'          => Status::SUCCESS,
    \'campaignResults\' => $campaignResults
]);
```
'
@endphp

    <x-header align="align-items-end">
        <x-slot name="sup">Чистый код</x-slot>
        <x-slot name="title">Простые правила для вашего кода</x-slot>
        <x-slot name="description">
            Код должен быть понятен всем членам команды и легко читаем для разработчиков, которые могут внести изменения в него.
        </x-slot>
        <x-slot name="content">
            <div class="position-relative">
                <div class="rounded position-relative overflow-hidden bg-body p-1 text-white border border-dashed code-marketing-snipped" style="
    transform: rotate(350deg);">
                    <x-posts.content :content="$markdownExampleCode"/>
                </div>
            </div>
        </x-slot>
    </x-header>


@php
$sections = collect([
    'basics',
    'code-style',
    'abbr',
    'numbers',
    'else',
    'happy-path'
])
    ->map(fn ($file) => \Illuminate\Support\Str::of($file)->start('clear-code/'))
    ->map(fn ($file) => new \App\Library($file));
// TODO Один уровень вложености
@endphp

    @include('particles.library-section', ['sections' => $sections])
@endsection
