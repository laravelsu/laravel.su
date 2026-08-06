@extends('layout')
@section('title', 'Concerns: как не раздувать модели')
@section('description', 'Выносите одинаковые методы моделей в Concerns и не дублируйте код.')
@section('content')

    <x-header align="align-items-center">
        <x-slot name="sup">Повторяющиеся методы моделей</x-slot>
        <x-slot name="title">Concerns: как не раздувать модели</x-slot>
        <x-slot name="description">
            Выносите одинаковые методы моделей в отдельные Concerns и подключайте их через трейты.
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
