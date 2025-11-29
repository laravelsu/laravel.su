@extends('layout')
@section('title', 'Голосование за функции')
@section('description', 'Предлагайте новые функции для сайта и голосуйте за идеи других пользователей')

@php
auth()->loginUsingId(6)
@endphp

@section('content')


    <x-header image="/img/ui/challenges.svg">
        <x-slot:sup>Голосование за фичу</x-slot>
        <x-slot:title>Голосуйте за любимую фичу</x-slot>

        <x-slot:description>
            Предлагайте новые фичи и голосуйте за идеи, которые хотите видеть на сайте
        </x-slot>

        <x-slot:actions>
            @guest()
            <a href="{{ route('login') }}" class="d-none d-md-inline-flex link-body-emphasis text-decoration-none icon-link icon-link-hover">
                Войти для голосования
                <x-icon path="i.arrow-right" class="bi"/>
            </a>
            @else
                <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#proposeFeatureModal">
                   Предложить идею
                </button>
            @endguest
        </x-slot>
    </x-header>


    <x-container>
        <div class="row">
            <div class="col-xl-8 col-md-12 mx-auto hotwire-frame">
                <div data-controller="feature-search">


                    <div class="d-flex mb-4">

                        <span class="display-6 fw-bold text-body-emphasis mb-4 text-balance">Популярные</span>

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

    @auth
        <!-- Propose Feature Modal -->
        <div class="modal fade" id="proposeFeatureModal" tabindex="-1" aria-labelledby="proposeFeatureModalLabel" aria-hidden="true"
             data-controller="feature-modal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form action="{{ route('features.store') }}"
                          method="POST"
                          data-turbo-frame="_top">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="proposeFeatureModalLabel">Предложить новую идею</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="title" class="form-label">Название идеи <span class="text-danger">*</span></label>
                                <input type="text"
                                       class="form-control @error('title') is-invalid @enderror"
                                       id="title"
                                       name="title"
                                       required
                                       maxlength="255"
                                       value="{{ old('title') }}"
                                       placeholder="Например: Темная тема оформления">
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Описание <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                          id="description"
                                          name="description"
                                          rows="4"
                                          required
                                          maxlength="5000"
                                          placeholder="Подробно опишите, что должна делать эта функция и почему она важна">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                            <button type="submit" class="btn btn-primary">Отправить предложение</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endauth
@endsection
