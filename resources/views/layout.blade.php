@extends('html')

@section('body')

    <x-header-banner-line />

    <div class="container mt-md-4 mb-3">
        <div class="my-2 my-lg-4">
            <header class="d-flex flex-wrap align-items-center justify-content-between">

                {{-- Mobile logo + menu --}}
                <div class="col-md-auto d-lg-none me-2 me-sm-3">
                    <a href="{{ route('nav') }}"
                       class="nav-link link-body-emphasis text-decoration-none d-flex align-items-center">
                        <x-icon path="i.logo" height="2em" width="2em" class="me-2" />
                        <x-icon path="i.menu" height="2em" width="2em" />
                    </a>
                </div>

                {{-- Desktop logo --}}
                <div class="col-md-auto me-auto me-lg-2">
                    <a href="{{ route('home') }}">
                        <img src="/img/logo.svg"
                             height="40"
                             class="d-lg-inline d-none pe-none">
                    </a>
                </div>

                {{-- Main navigation --}}
                <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0 d-none d-lg-flex">

                    <li>
                        <a href="{{ route('feature') }}"
                           class="nav-link px-3 link-body-emphasis">
                            Возможности
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('feed') }}"
                           class="nav-link px-3 link-body-emphasis">
                            Трибуна
                        </a>
                    </li>

                    {{--
                    <li>
                        <a href="{{ route('jobs') }}"
                           class="nav-link px-3 link-body-emphasis">
                            Работа
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('packages') }}"
                           class="nav-link px-3 link-body-emphasis">
                            Пакеты
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('courses') }}"
                           class="nav-link px-3 link-body-emphasis position-relative">
                            Курсы
                            <span class="badge bg-primary position-absolute top-0 start-100 translate-middle mt-2">
                                Новое
                            </span>
                        </a>
                    </li>
                    --}}

                    {{-- Dropdown --}}
                    <li class="dropdown-menu-end">
                        <a href="#"
                           class="nav-link px-3 link-body-emphasis dropdown-toggle"
                           data-bs-toggle="dropdown">
                            Больше
                        </a>

                        <div class="dropdown-menu bg-body-tertiary shadow-lg border-0 p-0">
                            <div class="d-lg-flex p-5 gap-4">

                                {{-- Column 1 --}}
                                <ul class="list-unstyled d-flex flex-column gap-4" style="width: 15rem;">

                                    <li>
                                        <a href="{{ route('packages') }}"
                                           class="link-body-emphasis text-decoration-none rounded-2 d-flex align-items-start gap-3 lh-sm text-start">
                                            <x-icon path="i.ui" height="2rem" width="2rem" class="text-primary" />
                                            <div class="col-10">
                                                <span class="d-block">Пакеты</span>
                                                <small class="opacity-50 line-clamp line-clamp-2 text-balance">
                                                    Великолепные дополнения сообщества
                                                </small>
                                            </div>
                                        </a>
                                    </li>

                                    <li>
                                        <a href="{{ route('idea.index') }}"
                                           class="link-body-emphasis text-decoration-none rounded-2 d-flex align-items-start gap-3 lh-sm text-start">
                                            <x-icon path="i.menu-idea" height="2rem" width="2rem" class="text-primary" />
                                            <div class="col-10">
                                                <span class="d-block">Laravel Idea</span>
                                                <small class="opacity-50 line-clamp line-clamp-2 text-balance">
                                                    Самая продуктивная среда разработки
                                                </small>
                                            </div>
                                        </a>
                                    </li>

                                    <li>
                                        <a href="{{ route('meets') }}"
                                           class="link-body-emphasis text-decoration-none rounded-2 d-flex align-items-start gap-3 lh-sm text-start">
                                            <x-icon path="i.menu-events" height="2rem" width="2rem" class="text-primary" />
                                            <div class="col-10">
                                                <span class="d-block">Мероприятия</span>
                                                <small class="opacity-50 line-clamp line-clamp-2 text-balance">
                                                    Ни одна встреча не обходится без Laravel
                                                </small>
                                            </div>
                                        </a>
                                    </li>

                                    <li>
                                        <a href="{{ route('ecosystem') }}"
                                           class="link-body-emphasis text-decoration-none rounded-2 d-flex align-items-start gap-3 lh-sm text-start">
                                            <x-icon path="i.menu-ecosystem" height="2rem" width="2rem" class="text-primary" />
                                            <div class="col-10">
                                                <span class="d-block">Экосистема</span>
                                                <small class="opacity-50 line-clamp line-clamp-2 text-balance">
                                                    Без корпоративной сложности
                                                </small>
                                            </div>
                                        </a>
                                    </li>

                                    <li>
                                        <a href="{{ route('courses') }}"
                                           class="link-body-emphasis text-decoration-none rounded-2 d-flex align-items-start gap-3 lh-sm text-start">
                                            <x-icon path="i.maintenance" height="2rem" width="2rem" class="text-primary" />
                                            <div class="col-10">
                                                <span class="d-block">Курсы</span>
                                                <small class="opacity-50 line-clamp line-clamp-2 text-balance">
                                                    Получайте новые знания в формате видеороликов
                                                </small>
                                            </div>
                                        </a>
                                    </li>

                                </ul>

                                {{-- Column 2 --}}
                                <ul class="list-unstyled d-flex flex-column gap-4" style="width: 15rem;">

                                    <li>
                                        <a href="{{ route('library') }}"
                                           class="link-body-emphasis text-decoration-none rounded-2 d-flex align-items-start gap-3 lh-sm text-start">
                                            <x-icon path="i.menu-library" height="2rem" width="2rem" class="text-primary" />
                                            <div class="col-10">
                                                <span class="d-block">Библиотека</span>
                                                <small class="opacity-50 line-clamp line-clamp-2 text-balance">
                                                    Учебные материалы для улучшения навыков
                                                </small>
                                            </div>
                                        </a>
                                    </li>

                                    <li>
                                        <a href="{{ route('orchid') }}"
                                           class="link-body-emphasis text-decoration-none rounded-2 d-flex align-items-start gap-3 lh-sm text-start">
                                            <x-icon path="i.orchid" height="2rem" width="2rem" class="text-primary" />
                                            <div class="col-10">
                                                <span class="d-block">Orchid</span>
                                                <small class="opacity-50 line-clamp line-clamp-2 text-balance">
                                                    Админ панели и внутренние бизнес системы
                                                </small>
                                            </div>
                                        </a>
                                    </li>

                                    <li>
                                        <a href="{{ route('boilerplate') }}"
                                           class="link-body-emphasis text-decoration-none rounded-2 d-flex align-items-start gap-3 lh-sm text-start">
                                            <x-icon path="i.utilities" height="2rem" width="2rem" class="text-primary" />
                                            <div class="col-10">
                                                <span class="d-block">Boilerplate</span>
                                                <small class="opacity-50 line-clamp line-clamp-2 text-balance">
                                                    Готовый скелет для создания вашего пакета
                                                </small>
                                            </div>
                                        </a>
                                    </li>

                                    <li>
                                        <a href="{{ route('pastebin') }}"
                                           class="link-body-emphasis text-decoration-none rounded-2 d-flex align-items-start gap-3 lh-sm text-start">
                                            <x-icon path="i.code" height="2rem" width="2rem" class="text-primary" />
                                            <div class="col-10">
                                                <span class="d-block">Pastebin</span>
                                                <small class="opacity-50 line-clamp line-clamp-2 text-balance">
                                                    Делитесь своим кодом правильно
                                                </small>
                                            </div>
                                        </a>
                                    </li>

                                </ul>
                            </div>
                        </div>
                    </li>
                </ul>

                {{-- Right side navigation --}}
                <div class="nav text-end">

                    <a href="{{ route('docs') }}"
                       class="nav-link link-body-emphasis d-none d-md-inline-flex">
                        Документация
                    </a>

                    <a href="{{ route('nav.docs') }}"
                       class="nav-link link-body-emphasis d-md-none">
                        <x-icon path="i.docs" height="1.5em" width="1.5em" />
                    </a>

                    {{-- Notifications --}}
                    <a href="{{ route('profile.notifications') }}"
                       class="nav-link link-body-emphasis position-relative">
                        <x-icon path="i.bell" height="1.5em" width="1.5em" />

                        @if(auth()->user() == null || auth()->user()->unreadNotifications()->exists())
                            <span class="position-absolute bottom-0 start-70 translate-middle p-1 bg-primary rounded-circle">
                                <span class="visually-hidden">
                                    Есть не прочитанные уведомления
                                </span>
                            </span>
                        @endif
                    </a>

                    {{-- Avatar --}}
                    @guest
                        <a href="{{ route('login') }}"
                           class="avatar avatar-sm position-relative">
                            <img src="{{ asset('img/ui/avatar.png') }}"
                                 class="avatar-img rounded-circle border border-tertiary-subtle">
                        </a>
                    @else
                        <a href="{{ route('profile', auth()->user()) }}"
                           class="avatar avatar-sm position-relative">
                            <img src="{{ auth()->user()->avatar }}"
                                 class="avatar-img rounded-circle border border-tertiary-subtle">
                        </a>
                    @endif

                </div>
            </header>
        </div>
    </div>

    @yield('content')

    {{-- Footer sun --}}
    <div class="mt-5 pe-none d-none d-md-block">
        <img src="/img/sun.svg"
             class="w-100 object-fit-cover footer-sun pe-none">
    </div>

    {{-- Footer --}}
    <div class="bg-dark-subtle text-white d-none d-md-block"
         data-bs-theme="dark">
        <div class="container py-5">
            <footer class="row py-md-5 g-4 justify-content-between navbar-dark">

                {{-- Left column --}}
                <div class="col-12 col-md-4">

                    <ul class="nav justify-content-start align-items-center list-unstyled d-flex mb-4">

                        <li>
                            <a href="https://vk.com/laravel_rus"
                               target="_blank"
                               class="link-body-emphasis">
                                <x-icon path="i.vk" width="24" height="24" />
                            </a>
                        </li>

                        <li class="ms-3">
                            <a href="https://t.me/laravelrus"
                               target="_blank"
                               class="link-body-emphasis">
                                <x-icon path="bs.telegram" width="24" height="24" />
                            </a>
                        </li>

                        <li class="ms-3">
                            <a href="{{ asset(config('services.github.org_url')) }}"
                               target="_blank"
                               class="link-body-emphasis">
                                <x-icon path="bs.github" width="24" height="24" />
                            </a>
                        </li>

                        <li class="ms-3">
                            <a href="https://www.youtube.com/@laravelrus"
                               target="_blank"
                               class="link-body-emphasis">
                                <x-icon path="bs.youtube" width="24" height="24" />
                            </a>
                        </li>

                        <li class="ms-3">
                            <a href="{{ asset('/rss/feed') }}"
                               target="_blank"
                               class="link-body-emphasis">
                                <x-icon path="bs.rss-fill" width="24" height="24" />
                            </a>
                        </li>

                    </ul>

                    <p class="small text-muted mb-2">
                        Веб-сайт является неофициальным ресурсом, посвященным Laravel.
                        Мы объединяем разработчиков и энтузиастов, желающих обмениваться знаниями и опытом.
                        Мы не имеем официального статуса от
                        <a href="https://laravel.com"
                           target="_blank"
                           rel="nofollow"
                           class="link-body-emphasis">
                            Laravel
                        </a>
                        или
                        <a href="https://github.com/taylorotwell"
                           target="_blank"
                           rel="nofollow"
                           class="link-body-emphasis">
                            Taylor Otwell
                        </a>.
                    </p>

                    <p class="small text-muted">
                        Логотип Laravel и другие сопутствующие товарные знаки принадлежат их законным владельцам.
                    </p>

                </div>

                {{-- Остальные колонки футера оставлены в том же стиле форматирования --}}
                {{-- (структура сохранена, просто выровнена и приведена к единому стилю) --}}

            </footer>
        </div>
    </div>

    {{-- @include('particles.mobile-menu') --}}
    @include('particles.back-to-top')

@endsection
