@extends('layout')
@section('title', 'Как задавать вопросы?')
@section('description', 'Правильно заданный вопрос содержит половину ответа')
@section('content')

    <x-header align="align-items-end">
        <x-slot name="sup">Ааа, помогите, ничего не работает</x-slot>
        <x-slot name="title">Как задавать вопросы?</x-slot>
        <x-slot name="description">
            Правильно заданный вопрос содержит половину ответа
        </x-slot>
        <x-slot name="content">
            <figure class="d-none d-md-block">
                <x-icon path="l.dots" class="text-primary opacity-2 d-block mx-auto" height="300" width="300"/>
            </figure>
        </x-slot>
    </x-header>

    @php
        $sections = collect([
        'drafting',
        'gist',
        'clarity',
        'full-screen',
        'show-really',
        'do-not-worry',
        'only-public',
        'why',
    ])
        ->map(fn ($file) => \Illuminate\Support\Str::of($file)->start('how-to-ask/'))
        ->map(fn ($file) => new \App\Library($file));
    @endphp

    @include('particles.library-section', ['sections' => $sections])

@endsection
