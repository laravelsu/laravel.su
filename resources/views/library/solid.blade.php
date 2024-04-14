@extends('layout')
@section('title', 'SOLID принципы')
@section('description', 'Мы поочередно познакомимся с каждым принципом, чтобы понять, как SOLID может помочь вам стать лучшим разработчиком.')
@section('content')

    <x-header align="align-items-end">
        <x-slot name="sup">Объектно-ориентированные</x-slot>
        <x-slot name="title">SOLID принципы</x-slot>
        <x-slot name="description">
            Мы поочередно познакомимся с каждым принципом, чтобы понять, как SOLID может помочь вам стать лучшим разработчиком.
        </x-slot>
        <x-slot name="content">
            <figure class="d-none d-md-block">
                <x-icon path="l.dots" class="text-primary opacity-2 d-block mx-auto" height="300" width="300"/>
            </figure>
        </x-slot>
    </x-header>

    @php
        $sections = collect([
        'basics',
        'srp',
        'ocp',
        'lsp',
        'lsp',
        'dip',
    ])
        ->map(fn ($file) => \Illuminate\Support\Str::of($file)->start('solid/'))
        ->map(fn ($file) => new \App\Library($file));
    @endphp

    @include('particles.library-section', ['sections' => $sections])

@endsection
