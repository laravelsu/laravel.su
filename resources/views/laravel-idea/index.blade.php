@extends('layout')
@section('title', 'Laravel Idea')
@section('description', 'Полезные дополнения для IDE, включая генерацию кода, автодополнение Eloquent, правил валидации и многое другое.')

@section('content')
    <x-header image="/img/ui/crane-h.svg">
        <x-slot:sup>Среда разработки</x-slot>
            <x-slot:title>
                Laravel Idea для PhpStorm
            </x-slot>

            <x-slot:description>
                Полезные дополнения для IDE, включая генерацию кода, автодополнение
                Eloquent, правил валидации и многое другое.
            </x-slot>

            <x-slot:actions>
                <a href="https://laravel-idea.com/" class="btn btn-primary btn-lg px-4">Перейти на сайт</a>
                <a href="https://plugins.jetbrains.com/plugin/13441-laravel-idea"
                   class="d-none d-md-inline-flex link-body-emphasis text-decoration-none icon-link icon-link-hover">
                        Маркетплейс
                        <x-icon path="i.arrow-right" class="bi"/>
                </a>
            </x-slot>
    </x-header>


    {{--
    <x-container>

        <div class="p-4 p-xl-5 bg-body-tertiary rounded-3 position-relative mb-4">
            <div class="row g-xxl-5 align-items-center">
                <div class="col-xl-6">
                    <div class="d-none d-xl-flex row row-cols-1 row-cols-sm-1 g-4">
                        <div class="col d-flex flex-column gap-2">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <div
                                        class="feature-icon-small d-inline-flex align-items-center justify-content-center border border-primary text-primary fs-4 rounded-3">
                                        <x-icon path="i.idea1"/>
                                    </div>
                                </div>
                                <h4 class="fw-semibold mb-0 text-body-emphasis">Генерация кода</h4>
                            </div>
                            <p class="text-body-secondary">
                                Мощная настраиваемая генерация кода для Laravel, автозаполнение полей и завершение
                                отношений.
                            </p>
                        </div>
                        <div class="col d-flex flex-column gap-2">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <div
                                        class="feature-icon-small d-inline-flex align-items-center justify-content-center border border-primary text-primary fs-4 rounded-3">
                                        <x-icon path="i.idea2"/>
                                    </div>
                                </div>
                                <h4 class="fw-semibold mb-0 text-body-emphasis">Eloquent завершение</h4>
                            </div>
                            <p class="text-body-secondary">
                                Полное автозаполнение полей и отношений, автоматическое создание фабрики ресурсов и баз данных.
                            </p>
                        </div>
                        <div class="col d-flex flex-column gap-2">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <div
                                        class="feature-icon-small d-inline-flex align-items-center justify-content-center border border-primary text-primary fs-4 rounded-3">
                                        <x-icon path="i.idea3"/>
                                    </div>
                                </div>
                                <h4 class="fw-semibold mb-0 text-body-emphasis">Полезные помощники</h4>
                            </div>
                            <p class="text-body-secondary">
                                Сотни полезных помощников, включая маршруты, валидацию, настройки и переводы, завершение
                                имен шлюзов, поддержка Blade и многое другое.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">

                    <div class="p-3 bg-warning-subtle rounded text-balance mb-3">
                        <a href="https://laravel.su/p/laravel-idea-teper-besplaten" class="text-decoration-none">
                            Laravel Idea с 30 июля 2025 года бесплатен для всех пользователей PhpStorm.
                        </a>
                    </div>

                    <form action="{{ route('idea.store') }}" method="POST" disabled class="opacity-25 disabled">
                        @csrf

                        <p>
                            Русскоязычные участники из России, Белоруссии и Украины имеют возможность подать заявку на
                            получение бесплатного ключа. Заполните форму, и после того, как ваш запрос
                            будет обработан, мы отправим вам ключ.
                        </p>

                        <div class="row row-cols-1 row-cols-sm-2">
                            <div class="col mb-3">
                                <label for="first_name" class="form-label">Имя</label>
                                <input type="text" class="form-control" id="first_name" name="first_name"
                                       placeholder="Иван" required>
                            </div>

                            <div class="col mb-3">
                                <label for="last_name" class="form-label">Фамилия</label>
                                <input type="text" class="form-control" id="last_name" name="last_name"
                                       placeholder="Иванов" required>
                            </div>
                        </div>

                        <div class="row row-cols-1 row-cols-sm-2">
                            <div class="col mb-3">
                                <label for="city" class="form-label">Город</label>
                                <input type="text" class="form-control" id="city" name="city" placeholder="Липецк"
                                       required>
                            </div>

                            <div class="col mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email"
                                       placeholder="example@mail.ru" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="message" class="form-label">Расскажите о себе</label>
                            <textarea class="form-control" id="message" name="message"
                                      placeholder="Мы будем рады узнать о ваших целях и мотивации для получения бесплатного ключа."
                                      rows="4"></textarea>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" name="accepted" value="1" required class="form-check-input" id="accepted-private-policy">
                            <label class="form-check-label" for="accepted-private-policy">
                                Я согласен на <a href="{{ route('privacy-policy') }}">обработку персональных данных</a>.
                            </label>
                        </div>

                        <div class="d-block d-sm-inline-block">
                            <button type="submit" class="w-100 btn btn-primary">Отправить заявку</button>
                        </div>


                    </form>

                </div>
            </div>
        </div>
    </x-container>
    --}}

    <x-container class="mt-lg-5 pt-lg-5">
        <span class="text-primary mb-3 d-block text-uppercase fw-semibold ls-xl">Разработчики говорят:</span>

        <div class="row marketing">
            <div class="col-12 col-md-6">
                <div class="d-flex flex-column align-items-md-baseline">
                    <div class="position-relative mb-5">

                        <div class="text-balance bg-body-secondary rounded p-4 p-xl-5 position-relative">
                            <blockquote class="lead">
                                С помощью плагина Laravel Idea вы можете легко создавать представления Blade в PhpStorm.
                                Очень удобно! 👍
                            </blockquote>

                            <div class="d-flex align-items-center">
                                <img alt="image" height="50" class="rounded-circle" src="https://avatars.githubusercontent.com/freekmurze?v4">
                                <div class="ms-3 lh-1">
                                    <div class="fw-bolder mb-1">Freek Van der Herten</div>
                                    <small class="opacity-50">@freekmurze</small>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="position-relative mb-5">

                        <div class="text-balance bg-body-secondary rounded p-4 p-xl-5 position-relative">
                            <blockquote class="lead">
                                Laravel Idea — это необходимый плагин для PhpStorm! Примите участие в розыгрыше — или
                                просто подарите Аделю заслуженные деньги ❤️
                            </blockquote>

                            <div class="d-flex align-items-center">
                                <img alt="image" height="50" class="rounded-circle" src="https://avatars.githubusercontent.com/mpociot?v4">
                                <div class="ms-3 lh-1">
                                    <div class="fw-bolder mb-1">Marcel Pociot</div>
                                    <small class="opacity-50">@marcelpociot</small>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="position-relative mb-5">

                    <div class="text-balance bg-body-secondary rounded p-4 p-xl-5 position-relative">
                        <blockquote class="lead">
                            Лучший плагин для PHPStorm и Laravel. Стоит каждой копейки!
                            Купите его и поддержите его отличную работу для сообщества!
                        </blockquote>

                        <div class="d-flex align-items-center">
                            <img alt="image" height="50" class="rounded-circle" src="https://avatars.githubusercontent.com/stefanbauer?v4">
                            <div class="ms-3 lh-1">
                                <div class="fw-bolder mb-1">Stefan Bauer</div>
                                <small class="opacity-50">@stefanbauerme</small>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="position-relative mb-5">

                    <div class="text-balance bg-body-secondary rounded p-4 p-xl-5 position-relative">
                        <blockquote class="lead">
                            Серьезно, плагин Laravel Idea — один из лучших для PhpStorm 🪄
                        </blockquote>

                        <div class="d-flex align-items-center">
                            <img alt="image" height="50" class="rounded-circle" src="https://avatars.githubusercontent.com/jbrooksuk?v4">
                            <div class="ms-3 lh-1">
                                <div class="fw-bolder mb-1">James Brooks</div>
                                <small class="opacity-50">@jbrooksuk</small>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </x-container>

@endsection
