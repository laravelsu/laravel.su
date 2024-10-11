@extends('layout')
@section('title', 'Библиотека знаний')
@section('description', 'Здесь собраны разнообразные материалы, которые помогут вам стать любимым коллективом.')

@section('content')

<x-header image="/img/ui/tutorials.svg">
    <x-slot:sup>Для каждого</x-slot>
    <x-slot:title>Джентльменский набор знаний</x-slot>

    <x-slot:description>
        Здесь собраны разнообразные материалы, которые помогут вам стать любимым коллективом.
    </x-slot>
</x-header>


<x-container>

    <div class="row g-4">
        <div class="col-md-6 col-lg-8">

            <div class="bg-primary bg-opacity-10 rounded p-4 p-xl-5 position-relative overflow-hidden mb-4">
                <img src="/img/bird.svg" class="position-absolute w-50 bottom-0 end-0">
                <div class="row">
                    <div class="col-sm-7">
                        <h3 class="mb-3 fw-bold">Простые правила для вашего кода</h3>
                        <p class="mb-4 fw-light mb-md-auto">
                            Код должен быть понятен всем членам команды и легко читаем для разработчиков, которые могут внести изменения в него
                        </p>

                        <a href="{{ route('library.clear-code') }}"
                           class="link-body-emphasis text-decoration-none icon-link icon-link-hover stretched-link mt-4">
                            Начать читать
                            <x-icon path="i.arrow-right" class="bi" />
                        </a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="bg-primary bg-opacity-10 rounded p-5 h-100 position-relative overflow-hidden">
                        <div class="d-flex flex-column position-relative h-100">
                            <h3 class="mb-3 fw-bold">Советы по безопасности</h3>
                            <p class="mb-auto fw-light">
                                Распространенные ошибки в коде, приводящие к уязвимостям безопасности в приложениях на Laravel
                            </p>

                            <a href="{{ route('library.security') }}"
                               class="link-body-emphasis text-decoration-none icon-link icon-link-hover stretched-link mt-4">
                                Начать читать
                                <x-icon path="i.arrow-right" class="bi" />
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">

                    <div class="bg-secondary bg-opacity-10 rounded p-5 h-100 position-relative overflow-hidden">
                        <div class="d-flex flex-column position-relative h-100">
                            <h3 class="mb-3 fw-bold">Коллекции</h3>
                            <p class="mb-auto fw-light">
                                Перестаньте использовать громоздкие примитивные массивы и начните использовать коллекции.
                            </p>

                            <a href="{{ route('library.collection') }}"
                               class="link-body-emphasis text-decoration-none icon-link icon-link-hover stretched-link mt-4">
                                Начать читать
                                <x-icon path="i.arrow-right" class="bi" />
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="bg-secondary bg-opacity-10 rounded p-5 h-100 position-relative overflow-hidden">
                <img src="/img/sign.svg" class="position-absolute w-100 bottom-0 end-0 d-none d-lg-block">
                <ul class="d-grid gap-5 list-unstyled text-balance">
                    <li>
                        <a href="{{ route('library.how-to-ask') }}" class="link-body-emphasis link-underline-opacity-0">
                            <p class="mb-0 lead fw-bolder">Как задавать вопросы?</p>
                            <small>Правильно заданный вопрос содержит половину ответа</small>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('library.upgrade') }}" class="link-body-emphasis link-underline-opacity-0">
                            <p class="mb-0 lead fw-bolder">Почему нужно обновляться?</p>
                            <small>У программного обеспечения есть жизненный цикл, которого необходимо придерживаться</small>
                        </a>
                    </li>

                </ul>

            </div>
        </div>


        <div class="col-md-6 col-lg-4">
            <div class="bg-secondary bg-opacity-10 rounded p-5 h-100 position-relative overflow-hidden">
                <div class="d-flex flex-column position-relative h-100">
                    <h3 class="mb-3 fw-bold">Действия</h3>
                    <p class="mb-auto fw-light">
                       Класс должен сосредоточиться на выполнении одной конкретной задачи
                    </p>

                    <a href="{{ route('library.actions') }}"
                       class="link-body-emphasis text-decoration-none icon-link icon-link-hover stretched-link mt-4">
                        Начать читать
                        <x-icon path="i.arrow-right" class="bi" />
                    </a>
                </div>
            </div>
        </div>

    </div>

</x-container>


<x-call-to-action link="{{ route('donate') }}" text="Сделать пожертвование">
    <x-slot:title>Поддержите развитие сообщества</x-slot>

    <x-slot:description>
        Наш проект существует благодаря энтузиазму участников и спонсорской поддержке.
        Мы будем благодарны любой помощи, которая позволит нам проводить больше мероприятий и активнее развивать сообщество.
    </x-slot>
</x-call-to-action>

@endsection
