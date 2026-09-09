@extends('layout')
@section('title', 'Concerns: как не раздувать модели')
@section('description', 'Храните одинаковые методы Eloquent-моделей в одном Concern.')
@section('content')

    <x-header align="align-items-center">
        <x-slot name="sup">Одно правило для нескольких моделей</x-slot>
        <x-slot name="title">Concerns: как не раздувать модели</x-slot>
        <x-slot name="description">
            Записывайте общее правило один раз и подключайте его только к тем моделям, которым оно действительно нужно.
        </x-slot>
        <x-slot name="content">
            <img src="/img/ui/puzzle.svg" class="img-fluid d-block mx-auto">
        </x-slot>
    </x-header>

    @php
        $sections = collect([
            'duplication',
            'basics',
            'archivable',
            'usage',
            'boundaries',
            'conventions',
        ])
            ->map(fn ($file) => \Illuminate\Support\Str::of($file)->start('concerns/'))
            ->map(fn ($file) => new \App\Library($file));
    @endphp

    @include('particles.library-section', ['sections' => $sections])
@endsection
