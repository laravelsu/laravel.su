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

            <div class="bg-primary bg-opacity-10 rounded p-4 p-xl-5 position-relative overflow-hidden mb-4 ratio-1x1 ratio-md-auto">
                <img src="/img/bird.svg" class="position-absolute w-50 bottom-0 end-0">
                <div class="row">
                    <div class="col-sm-7">
                        <h3 class="mb-3 fw-bold text-balance">Простые правила для вашего кода</h3>
                        <p class="mb-4 fw-light mb-md-auto text-balance">
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
                <div class="col-lg-6 mb-4 mb-lg-0 ratio-1x1">
                    <div class="bg-primary bg-opacity-10 rounded p-5 h-100 position-relative overflow-hidden">
                        <div class="d-flex flex-column position-relative h-100">
                            <h3 class="mb-3 fw-bold text-balance">Советы по безопасности</h3>
                            <p class="mb-auto fw-light text-balance">
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
                <div class="col-lg-6 ratio-1x1">

                    <div class="bg-secondary bg-opacity-10 rounded p-5 h-100 position-relative overflow-hidden">
                        <div class="d-flex flex-column position-relative h-100">
                            <h3 class="mb-3 fw-bold text-balance">Коллекции</h3>
                            <p class="mb-auto fw-light text-balance">
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
                        <a href="{{ route('library.how-to-ask') }}" class="link-body-emphasis link-underline-opacity-0 text-balance">
                            <p class="mb-0 lead fw-bolder text-balance">Как задавать вопросы?</p>
                            <small>Правильно заданный вопрос содержит половину ответа</small>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('library.upgrade') }}" class="link-body-emphasis link-underline-opacity-0 text-balance">
                            <p class="mb-0 lead fw-bolder text-balance">Почему нужно обновляться?</p>
                            <small>У программного обеспечения есть жизненный цикл, которого необходимо придерживаться</small>
                        </a>
                    </li>

                </ul>

            </div>
        </div>


        <div class="col-md-6 col-lg-4">
            <div class="bg-secondary bg-opacity-10 rounded p-5 h-100 position-relative overflow-hidden ratio-1x1">
                <div class="d-flex flex-column position-relative h-100">
                    <h3 class="mb-3 fw-bold">Действия</h3>
                    <p class="mb-auto fw-light text-balance">
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

        <div class="col-md-8">
            <div class="bg-primary bg-opacity-10 rounded p-4 p-xl-5 position-relative overflow-hidden ratio-1x1 ratio-md-auto h-100">
                <img src="/img/ui/book.svg" class="position-absolute w-50 bottom-0 end-0 z-n1 ps-xl-4">
                <div class="row">
                    <div class="col-sm-7">
                        <h3 class="mb-3 fw-bold text-balance">Архитектура сложных веб-приложений.</h3>
                        <p class="mb-4 fw-light mb-md-auto text-balance">
                            Проекты очень разные. В этой книге Адель Файзрахманов, автор Laravel Idea, объясняет, как выбирать лучшие решения в Laravel.
                        </p>
                        <a href="https://github.com/adelf/acwa_book_ru" target="_blank" class="link-body-emphasis text-decoration-none icon-link icon-link-hover stretched-link mt-4">
                            Читать книгу
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="bi" width="1em" height="1em" role="img" fill="currentColor" path="i.arrow-right" componentname="icon">
                                <path d="m13,15c-.26,0-.51-.1-.71-.29-.39-.39-.39-1.02,0-1.41l3.29-3.29-3.29-3.29c-.39-.39-.39-1.02,0-1.41s1.02-.39,1.41,0l4,4c.39.39.39,1.02,0,1.41l-4,4c-.2.2-.45.29-.71.29Z"></path>
                                <path d="m17,11H3c-.55,0-1-.45-1-1s.45-1,1-1h14c.55,0,1,.45,1,1s-.45,1-1,1Z"></path>
                            </svg>

                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="bg-secondary bg-opacity-10 rounded p-5 h-100 position-relative overflow-hidden ratio-1x1">
                <div class="d-flex flex-column position-relative h-100">
                    <h3 class="mb-3 fw-bold">SOLID</h3>
                    <p class="mb-auto fw-light text-balance">
                        Когда ты понимаешь эти принципы, ты можешь писать код, который легко изменять и дополнять.
                    </p>

                    <a href="{{ route('library.solid') }}"
                       class="link-body-emphasis text-decoration-none icon-link icon-link-hover stretched-link mt-4">
                        Начать читать
                        <x-icon path="i.arrow-right" class="bi" />
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="bg-secondary bg-opacity-10 rounded p-5 h-100 position-relative overflow-hidden ratio-1x1">
                <div class="d-flex flex-column position-relative h-100">
                    <h3 class="mb-3 fw-bold">Геттеры и сеттеры</h3>
                    <p class="mb-auto fw-light text-balance">
                        Почему важно проектировать объекты с поведением, а не только с данными.
                    </p>

                    <a href="{{ route('library.getters-and-setters') }}"
                       class="link-body-emphasis text-decoration-none icon-link icon-link-hover stretched-link mt-4">
                        Начать читать
                        <x-icon path="i.arrow-right" class="bi" />
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="bg-secondary bg-opacity-10 rounded p-5 h-100 position-relative overflow-hidden ratio-1x1">
                <div class="d-flex flex-column position-relative h-100">
                    <h3 class="mb-3 fw-bold">Мифы, в которые пора перестать верить</h3>
                    <p class="mb-auto fw-light text-balance">
                        Некоторые выбирают себе религию по цвету иконки.
                    </p>
                    <a href="{{ route('library.fallacies') }}"
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
