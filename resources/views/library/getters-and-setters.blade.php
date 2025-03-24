@extends('layout')
@section('title', 'Геттеры, сеттеры и DTO')
@section('description', 'Разрушают инкапсуляцию, увеличивают связанность кода и приводят к процедурному стилю программирования.')
@section('content')

    <x-header align="align-items-center">
        <x-slot name="sup">Объектно-ориентированное программирование</x-slot>
        <x-slot name="title">Геттеры, сеттеры и DTO</x-slot>
        <x-slot name="description">
            Разрушают инкапсуляцию, увеличивают связанность кода и приводят к процедурному стилю программирования.
        </x-slot>
        <x-slot name="content">
            <img src="/img/ui/bone.svg" class="img-fluid d-block mx-auto">
        </x-slot>
    </x-header>

    @php
        $sections = collect([
            'basics',
            'getters-and-setters',
            'tell-dont-ask',
        ])
            ->map(fn ($file) => \Illuminate\Support\Str::of($file)->start('getters-and-setters/'))
            ->map(fn ($file) => new \App\Library($file));
    @endphp

    @include('particles.library-section', ['sections' => $sections])
@endsection
