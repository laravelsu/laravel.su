@extends('html')
@section('title', 'Внутренняя ошибка сервера')

@section('body')
    <x-header image="/img/ui/login.svg">
        <x-slot:sup>Внутренняя ошибка сервера</x-slot>
        <x-slot:title>500</x-slot>

        <x-slot:description>
            Упс! Что-то пошло не так на нашем сервере.
            Пожалуйста, попробуйте позже или вернитесь на домашнюю страницу.
        </x-slot>

        <x-slot:actions>
            <a href="{{ route('home') }}" class="btn btn-primary btn-lg px-4">Вернуться домой</a>
        </x-slot>
    </x-header>
@endsection
