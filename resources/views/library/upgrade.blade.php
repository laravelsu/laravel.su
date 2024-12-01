@extends('layout')
@section('title', 'Почему нужно обновляться?')
@section('description', 'У любого программного обеспечения есть жизненный цикл, которого необходимо придерживаться.')
@section('content')

    <x-header>
        <x-slot:sup>Поддерживаемые версии</x-slot>
        <x-slot:title>Почему нужно обновляться?</x-slot>

        <x-slot:description>
            У любого программного обеспечения есть жизненный цикл,
            которого необходимо придерживаться.
        </x-slot>

        <x-slot:content>
            <div class="d-none d-md-flex align-items-baseline lead fw-bold mx-md-auto text-md-center">
                @php
                    $version = \Str::of(\App\Docs::DEFAULT_VERSION)->before('.')->toString();
                @endphp

                @foreach(collect()->range(0,3)->reverse() as $index)
                    <span @class([
                    'd-inline-block mx-2 mx-sm-3',
                    'display-' . ($index + 1),
                    'opacity-' . [100, 75, 50, 25][$index],
                    'border-bottom border-primary' => $index === 0,
                ])>
                    {{ $version - $index }}.x
                </span>
                @endforeach
            </div>
        </x-slot:content>
    </x-header>


@php
    $sections = collect([
        "security",
        "labor-costs",
        "performance",
        "competitiveness",
])
    ->map(fn ($file) => \Illuminate\Support\Str::of($file)->start('upgrade/'))
    ->map(fn ($file) => new \App\Library($file));
@endphp

@include('particles.library-section', ['sections' => $sections])
@endsection
