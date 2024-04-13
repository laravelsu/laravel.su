@extends('layout')
@section('title', 'Высокий уровень через коллекцию')
@section('description', 'Перестаньте использовать громоздкие примитивные массивы и начните использовать коллекции.')
@section('content')

    <x-header align="align-items-end">
        <x-slot name="sup">Декларативный стиль</x-slot>
        <x-slot name="title">Высокий уровень через коллекцию</x-slot>
        <x-slot name="description">
            Опирайтесь на высокие абстракции вместо применения низкоуровневых конструкций.
        </x-slot>
        <x-slot name="content">
            <img src="/img/gusli.svg" class="img-fluid d-block mx-auto">
        </x-slot>
    </x-header>


    @php
        $sections = collect([
            'basics',
            'consistency',
            'foreach',
            'thinking-in-steps',
            'dont-primitives',
        ])
            ->map(fn ($file) => \Illuminate\Support\Str::of($file)->start('collection/'))
            ->map(fn ($file) => new \App\Library($file));
    @endphp

    @include('particles.library-section', ['sections' => $sections])
@endsection
