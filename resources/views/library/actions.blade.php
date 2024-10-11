@extends('layout')
@section('title', 'Один класс — одна задача')
@section('description', 'Каждый класс в приложении должен отвечать только за одну конкретную задачу.')
@section('content')

    <x-header align="align-items-center" image="/img/ui/finger.svg">
        <x-slot name="sup">Ясность с первого взгляда</x-slot>
        <x-slot name="title">Один класс — одна задача</x-slot>
        <x-slot name="description">
            Каждый класс в приложении должен сосредоточиться на выполнении одной конкретной задачи или функции
        </x-slot>
    </x-header>

    @php
        $sections = collect([
            'basics',
            'focus',
            'package',
            'conventions',
            'tests',
        ])
            ->map(fn ($file) => \Illuminate\Support\Str::of($file)->start('actions/'))
            ->map(fn ($file) => new \App\Library($file));
    @endphp

    @include('particles.library-section', ['sections' => $sections])
@endsection
