@extends('layout')
@section('title', 'Один класс — одна задача')
@section('description', 'Каждый класс в приложении должен отвечать только за одну конкретную задачу или функциональность.')
@section('content')

    <x-header align="align-items-end">
        <x-slot name="sup">Чистота и порядок</x-slot>
        <x-slot name="title">Один класс — одна задача</x-slot>
        <x-slot name="description">
            Каждый класс в приложении должен отвечать только за одну конкретную задачу или функциональность.
        </x-slot>
        <x-slot name="content">
            <img src="/img/gusli.svg" class="img-fluid d-block mx-auto">
        </x-slot>
    </x-header>

    @php
        $sections = collect([
            'basics',
            'focus',
            'conventions',
            'tests',
        ])
            ->map(fn ($file) => \Illuminate\Support\Str::of($file)->start('actions/'))
            ->map(fn ($file) => new \App\Library($file));
    @endphp

    @include('particles.library-section', ['sections' => $sections])
@endsection
