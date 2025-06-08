@extends('layout')
@section('title', 'Laravel против заблуждений')
@section('description', 'Laravel это игрушка перед Symfony? Laravel не подходит для высоконагруженных проектов? Разберемся с мифами, которые мешают вам использовать Laravel в своих проектах.')
@section('content')

    <x-header align="align-items-end">
        <x-slot name="sup">Мифы, в которые пора перестать верить</x-slot>
        <x-slot name="title">Laravel против заблуждений</x-slot>
        <x-slot name="description">
            Некоторые выбирают себе религию по цвету иконки или по совету анонима из Telegram чата, который пересказывает чужое мнение.
        </x-slot>
        <x-slot name="content">
            <img src="/img/ui/challenges.svg" class="img-fluid d-block mx-auto">
        </x-slot>
    </x-header>

    @php
        $sections = collect([
            'beginner',
            'foundation',
            'magic',
            'highload',
            'stability',
            'components',
        ])
        ->map(fn ($file) => \Illuminate\Support\Str::of($file)->start('fallacies/'))
        ->map(fn ($file) => new \App\Library($file));
    @endphp

    @include('particles.library-section', ['sections' => $sections])

@endsection
