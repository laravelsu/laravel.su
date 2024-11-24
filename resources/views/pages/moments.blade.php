@extends('layout')
@section('title', 'Особенные события сообщества')
@section('description', 'Уникальные моменты. Особенные даты. Всё, что делает сообщество ярче.')

@section('content')

    <x-header image="/img/ui/carpet.svg">
        <x-slot:sup>Вперёд к новым вершинам</x-slot>
        <x-slot:title>Особенные события сообщества</x-slot>

        <x-slot:description>
            Уникальные моменты. Особенные даты. Всё, что делает сообщество ярче.
        </x-slot>
    </x-header>

    <x-container>
        <div class="row g-4">
            <div class="col-md-6 col-lg-8 mb-4">

                <div class="bg-primary bg-opacity-10 rounded p-4 p-xl-5 position-relative overflow-hidden mb-4">
                    <img src="/img/ui/vostok/vostok2.svg" class="position-absolute w-50 bottom-0 end-0">
                    <div class="row">
                        <div class="col-sm-7">
                            <h3 class="mb-3 fw-bold">С Днём Космонавтики!</h3>
                            <p class="mb-4 fw-light mb-md-auto">
                                Пусть эта дата вдохновляет нас на новые открытия и смелые шаги в неизведанные просторы Вселенной.
                            </p>

                            <a href="{{ route('vostok') }}"
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

                                <div class="d-flex align-items-center gap-3 mb-auto">
                                    <div class="col-11">
                                        <h3 class="mb-3 fw-bold">Секреты ремесла</h3>
                                        <p class="mb-auto fw-light">
                                            Загадки и тайны - это то, что тебя ждет. Получите награду разгадав все загадки!
                                        </p>
                                    </div>
                                    <div class="col-5 me-auto">
                                        <img src="/img/ui/chest.svg" class="img-fluid">
                                    </div>
                                </div>

                                <a href="{{ route('quiz.open') }}"
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
                                <h3 class="mb-3 fw-bold">На дне</h3>
                                <p class="fw-light">
                                    Отгадывать слова связанные с разработкой,
                                    чтобы избежать погружения в мрак адских технологий.
                                </p>

                                <div class="gap-4 d-flex text-primary mb-auto opacity-50">
                                    <x-icon path="i.heart-fill" width="2rem" height="2rem"/>
                                    <x-icon path="i.heart" width="2rem" height="2rem"/>
                                    <x-icon path="i.heart" width="2rem" height="2rem"/>
                                    <x-icon path="i.heart" width="2rem" height="2rem"/>
                                </div>

                                <a href="{{ route('hangman') }}"
                                   class="link-body-emphasis text-decoration-none icon-link icon-link-hover stretched-link mt-4">
                                    Начать читать
                                    <x-icon path="i.arrow-right" class="bi" />
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4 mb-4">
                <div class="p-4 p-xl-5 bg-body-tertiary rounded d-flex flex-column h-100 position-relative d-flex bg-opacity-75 opacity-50 align-items-center">
                    <div class="text-decoration-none d-block text-center my-auto text-balance">

                        <span class="d-block mb-3">
                             <x-icon path="i.sun" width="3rem" height="3rem"></x-icon>
                        </span>

                        Здесь скоро появится новое событие. Следите за обновлениями!
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
