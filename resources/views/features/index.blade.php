@extends('layout')
@section('title', 'Голосование за идеи')
@section('description', 'Предлагайте новые функции для сайта и голосуйте за идеи других пользователей')

@section('content')
    <x-container>
        <div class="row">
            <div class="col-xl-8 col-md-12 mx-auto hotwire-frame">
                <div data-controller="feature-search">


                    <div class="d-flex mb-4 align-items-end gap-3">

                        <div class="col-md-7 d-flex flex-column gap-2 mb-4">
                            <span class="display-6 fw-bold text-body-emphasis text-balance">Идеи для сайта</span>
                            <div class="opacity-50 text-balance">
                                Предлагайте новые идеи и голосуйте за те, что хотите видеть на сайте
                            </div>

                            @guest()
                                <a href="{{ route('login') }}" class="d-none d-md-inline-flex link-body-emphasis text-decoration-none icon-link icon-link-hover">
                                    Войти для голосования
                                    <x-icon path="i.arrow-right" class="bi"/>
                                </a>
                            @else
                                <a href="{{ route("features.create") }}" type="button" class="btn btn-primary btn-lg">
                                    Предложить идею
                                </a>
                            @endguest
                        </div>

                        <div class="ms-auto col-auto mb-4 position-relative d-inline-flex">
                            <form action="{{ route('features.search') }}"
                                  method="GET"
                                  data-turbo-frame="features-frame"
                                  data-feature-search-target="form"
                                  data-action="input->feature-search#search">
                                    <input type="text"
                                           name="q"
                                           class="form-control"
                                           data-feature-search-target="input"
                                           placeholder="Поиск..."
                                           autocomplete="off">
                            </form>
                        </div>

                    </div>

                    <div class="row">
                        <turbo-frame id="features-frame"
                                     data-feature-search-target="frame"
                                     target="_top"
                                     autoscroll="nearest"
                                     data-autoscroll-block="nearest"
                                     data-autoscroll-behavior="smooth">
                            @if($features->isEmpty())
                            <div class="col-12">
                                <div class="text-center py-5">
                                    <x-icon path="bs.inbox" class="text-muted" width="4rem" height="4rem" />
                                    <p class="lead text-muted mt-3">Пока нет идей</p>
                                    @auth
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#proposeFeatureModal">
                                            Станьте первым!
                                        </button>
                                    @endauth
                                </div>
                            </div>
                        @else
                            @include('features._list')
                        @endif
                    </turbo-frame>

                    @include('features._pagination')
                    </div>
                </div>
            </div>
        </div>
    </x-container>
@endsection
