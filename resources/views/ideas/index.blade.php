@extends('layout')
@section('title', 'Голосование за идеи')
@section('description', 'Предлагайте новые идеи для сайта и голосуйте за идеи других пользователей')

@section('content')
    <x-container>
        <div class="row">
            <div class="col-xl-8 col-md-12 mx-auto hotwire-frame">
                <div data-controller="idea-search">

                    <div class="d-flex mb-4 align-items-end gap-3">

                        <div class="col-md-7 d-flex flex-column gap-2 mb-4">
                            <span class="display-6 fw-bold text-body-emphasis text-balance">Идеи для сайта</span>
                            <div class="opacity-50 text-balance">
                                Предлагайте новые идеи и голосуйте за те, что хотите видеть на сайте
                            </div>

                            <a href="{{ route('ideas.create') }}" type="button" class="btn btn-primary btn-lg">
                                Предложить идею
                            </a>
                        </div>

                        <div class="ms-auto col-auto mb-4 position-relative d-inline-flex">
                            <form action="{{ route('ideas.search') }}"
                                  method="GET"
                                  data-turbo-frame="ideas-frame"
                                  data-idea-search-target="form"
                                  data-action="input->idea-search#search">
                                <input type="text"
                                       name="q"
                                       class="form-control"
                                       data-idea-search-target="input"
                                       placeholder="Поиск..."
                                       autocomplete="off">
                            </form>
                        </div>

                    </div>

                    <div class="row">
                        <turbo-frame id="ideas-frame"
                                     data-idea-search-target="frame"
                                     target="_top"
                                     autoscroll="nearest"
                                     data-autoscroll-block="nearest"
                                     data-autoscroll-behavior="smooth">
                            @if($ideas->isEmpty())
                                <div class="col-12">
                                    <div class="text-center py-5">
                                        <x-icon path="bs.inbox" class="text-muted" width="4rem" height="4rem" />
                                        <p class="lead text-muted mt-3">Пока нет идей</p>
                                        @auth
                                            <a href="{{ route('ideas.create') }}" class="btn btn-primary">
                                                Станьте первым!
                                            </a>
                                        @endauth
                                    </div>
                                </div>
                            @else
                                @include('ideas._list')
                            @endif
                        </turbo-frame>

                        @include('ideas._pagination')
                    </div>
                </div>
            </div>
        </div>
    </x-container>
@endsection
