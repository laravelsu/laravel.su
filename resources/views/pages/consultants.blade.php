@extends('layout')
@section('title', 'Консультанты Laravel')
@section('description', 'Передайте проект на аутсорсинг или привлеките экспертов Laravel в свою существующую команду.')

@section('content')
    <x-header image="/img/ui/tutorials.svg">
        <x-slot name="sup">
            Ребята мирового класса
        </x-slot>

        <x-slot name="title">
            Консультанты для вашего проекта
        </x-slot>

        <x-slot name="description">
            Передайте проект на аутсорсинг или привлеките экспертов Laravel в свою существующую команду.
        </x-slot>


        <x-slot name="actions">
            <div class="row w-100 pt-4 border-top  text-balance">
                <div class="col-sm mb-3 mb-lg-0">
                    <h2 class="h1 mb-0 ls-xs">45</h2>
                    <p class="mb-0">Доказанной ценности</p>
                </div>
                <div class="col-lg-5 col-sm mb-3 mb-lg-0">
                    <h2 class="h1 mb-0 ls-xs">10,500+</h2>
                    <p class="mb-0">Доказанной ценности</p>
                </div>
                <div class="col-sm mb-3 mb-lg-0">
                    <h2 class="h1 mb-0 ls-xs">12+</h2>
                    <p class="mb-0">Доказанной ценности</p>
                </div>
            </div>
        </x-slot>
    </x-header>


    <x-container>
            <div class="row g-5  row-cols-md-3">
                <div class="col">
                    <div class="h-100 d-flex flex-md-row flex-column px-4 py-3 py-xl-5 bg-body-secondary rounded position-relative align-items-md-center">
                        <div class="vr bg-primary position-absolute start-0 opacity-100" style="top: 1.5em; bottom: 1.5em;"></div>
                        <div>
                            <div class="d-flex align-items-center mb-3">
                                <p class="fs-4 mb-0">Веб приложение</p>
                            </div>

                            <img src="/img/ui/web.svg" class="img-fluid d-block mx-auto">
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="h-100 d-flex flex-md-row flex-column px-4 py-3 py-xl-5 bg-body-secondary rounded position-relative align-items-md-center">
                        <div class="vr bg-primary position-absolute start-0 opacity-100" style="top: 1.5em; bottom: 1.5em;"></div>
                        <div>
                            <div class="d-flex align-items-center mb-3">
                                <p class="fs-4 mb-0">Веб приложение</p>
                            </div>

                            <img src="/img/ui/api.svg" class="img-fluid d-block mx-auto">
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="h-100 d-flex flex-md-row flex-column px-4 py-3 py-xl-5 bg-body-secondary rounded position-relative align-items-md-center">
                        <div class="vr bg-primary position-absolute start-0 opacity-100" style="top: 1.5em; bottom: 1.5em;"></div>
                        <div>
                            <div class="d-flex align-items-center mb-3">
                                <p class="fs-4 mb-0">Веб приложение</p>
                            </div>
                            <img src="/img/ui/console.svg" class="img-fluid d-block mx-auto">
                        </div>
                    </div>
                </div>
            </div>
    </x-container>


    <x-container>
        <div class="row g-4 g-md-5 justify-content-center align-items-end mb-5">
            <div class="col-lg-8 me-auto">
                <span class="text-primary mb-3 d-block text-uppercase fw-semibold ls-xl">Вам могут понравиться</span>
                <h2 class="display-5 fw-semibold mb-0">Персональные русскоязычные серии</h2>
            </div>
        </div>


        <div class="row g-4 g-md-5 row-cols-3 row-cols-lg-2 justify-content-center">
            @foreach(range(0, 3) as $key)
                <div class="col">
                    <div class="row g-0 rounded bg-body-tertiary mb-5">
                        <div class="col-lg-5 order-lg-first">
                            <x-hero image="/img/community/chernayev.jpeg" text="от 4000 ₽" class="rounded-start"/>
                        </div>
                        <div class="col-lg-7">
                            <div class="p-4 p-xl-5">
                                <h5><strong>Александр Черняев</strong></h5>
                                <p class="opacity-50 small">
                                    Vivamus sit amet eros facilisis, suscipit libero eget, elementum diam. Praesent quam.
                                </p>

                                <p>Могу помочь: </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </x-container>


    <x-container>
        <div class="col-12">
            <div class="row g-4 g-md-5 row-cols-3 row-cols-lg-2 justify-content-center">

                @foreach(range(0, 3) as $key)
                    <div class="col">
                        <div class="d-flex gap-5 text-balance bg-body-secondary rounded p-4 p-xl-5 position-relative">
                            <div class="col">
                                <div class="position-relative">
                                    <img alt="image" class="img-fluid rounded-circle mb-3" src="/img/community/chernayev.jpeg">
                                    <span class="badge bg-primary position-absolute top-0 start-0 translate-middle mt-2 ms-3" title="Достижения на сайте">от 4000 ₽</span>
                                </div>


                            </div>
                            <div class="col-8">
                                <h5 class="mt-2"><strong>Александр Черняев</strong></h5>
                                <p class="opacity-50 small">
                                    Vivamus sit amet eros facilisis, suscipit libero eget, elementum diam. Praesent quam.
                                </p>


                                {{--
                                <x-icon path="i.star-fill" class="me-2 text-warning"/>

                                <p class="small opacity-50">
                                    <mark class="rounded-1">от 4000 ₽</mark>
                                </p>
                                --}}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </x-container>



    <x-call-to-action link="{{ route('jobs') }}" text="  Посмотреть открытые позиции">
        <x-slot name="sup">
            Построй свою карьеру
        </x-slot>
        <x-slot:title>Стань одним из профессионалов</x-slot>

        <x-slot:description>
            Найти работу в хорошей кампаниии работать удаленно из любого места?
        </x-slot>
    </x-call-to-action>
@endsection
