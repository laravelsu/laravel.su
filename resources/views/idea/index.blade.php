@extends('layout')
@section('title', 'Laravel Idea')

@section('content')
    <x-header>
        <x-slot:sup>Среда разработки</x-slot>
        <x-slot:title>
            Laravel Idea
        </x-slot>

        <x-slot:description>
            Полезные дополнения для IDE, включая генерацию кода, автодополнение синтаксиса
            Eloquent, автодополнение правил валидации и многое другое.
        </x-slot>

        <x-slot:actions>
            <a href="https://laravel-idea.com/" class="btn btn-primary btn-lg px-4">Перейти на сайт</a>
            <a href="https://plugins.jetbrains.com/plugin/13441-laravel-idea"
               class="link-body-emphasis text-decoration-none link-icon-animation">Маркетплейс
                <x-icon path="bs.arrow-right" />
            </a>
        </x-slot>
    </x-header>


    <x-container>

        <div class="p-4 p-xxl-5 bg-body-secondary rounded-3 position-relative mb-4">
            <div class="row g-5">
                <div class="col">
                    <div class="d-none d-xl-flex row row-cols-1 row-cols-sm-1 g-4">
                        <div class="col d-flex flex-column gap-2">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <div
                                        class="feature-icon-small d-inline-flex align-items-center justify-content-center text-bg-primary bg-gradient fs-4 rounded-3">
                                        <x-icon path="bs.collection"/>
                                    </div>
                                </div>
                                <h4 class="fw-semibold mb-0 text-body-emphasis">Генерация кода</h4>
                            </div>
                            <p class="text-body-secondary">
                                Мощная настраиваемая генерация кода для Laravel, автозаполнение полей и завершение отношений.
                            </p>
                        </div>
                        <div class="col d-flex flex-column gap-2">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <div
                                        class="feature-icon-small d-inline-flex align-items-center justify-content-center text-bg-primary bg-gradient fs-4 rounded-3">
                                        <x-icon path="bs.collection"/>
                                    </div>
                                </div>
                                <h4 class="fw-semibold mb-0 text-body-emphasis">Eloquent завершение</h4>
                            </div>
                            <p class="text-body-secondary">
                                Полное автозаполнение полей и отношений, автоматическое создание фабрики ресурсов и баз данных JSON.
                            </p>
                        </div>
                        <div class="col d-flex flex-column gap-2">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <div
                                        class="feature-icon-small d-inline-flex align-items-center justify-content-center text-bg-primary bg-gradient fs-4 rounded-3">
                                        <x-icon path="bs.collection"/>
                                    </div>
                                </div>
                                <h4 class="fw-semibold mb-0 text-body-emphasis">Полезные помощники</h4>
                            </div>
                            <p class="text-body-secondary">
                                Сотни полезных помощников, включая маршруты, валидацию, настройки и переводы, завершение имен шлюзов, поддержка Blade и многое другое.
                            </p>
                        </div>
                    </div>

                    <img src="/img/ui/klubok.svg"
                         class="d-none d-xxl-block position-absolute bottom-0 end-0 m-2 pe-none">
                </div>
                <div class="col">
                    <form action="{{ route('idea.store') }}" method="POST">
                        @csrf

                        <p>
                            Русскоязычные участники комьюнити могут подать заявку на бесплатный ключ.
                            Просто заполните нашу форму, как только запрос будет обработан, мы отправим вам ключ.
                        </p>

                        <div class="row">
                            <div class="col mb-3">
                                <label for="first_name" class="form-label">Имя</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" required>
                            </div>

                            <div class="col mb-3">
                                <label for="last_name" class="form-label">Фамилия</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col mb-3">
                                <label for="city" class="form-label">Город</label>
                                <input type="text" class="form-control" id="city" name="city" required>
                            </div>

                            <div class="col mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="message" class="form-label">Расскажите о себе</label>
                            <textarea class="form-control" id="message" name="message" rows="4"></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">Отправить заявку</button>
                    </form>

                </div>
            </div>
        </div>

    </x-container>
@endsection