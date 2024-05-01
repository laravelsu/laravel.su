@extends('html')
@section('title', __('quiz.page.title'))
@section('description', __('quiz.page.description'))

@section('body')

    <div class="container vh-100 d-flex align-content-center align-items-center">
        <div class="col-lg-6 mx-auto">
            <div id="quiz" class="p-4 p-xl-5 bg-body-secondary rounded position-relative">

                <h1 class="user-select-none text-center mb-3">
                    Раскрой секреты своего ремесла
                </h1>

                <div class="d-flex p-0">
                    <img src="{{ asset('/img/ivan.svg') }}" class="w-100 img-fluid">
                </div>

                <div class="px-sm-4 pt-0">
                    <div class="d-flex align-items-center mb-3">
                        <div class="col">
                            <p class="fw-normal mb-0">
                                Получите награду войдя первым в обновлённое сообщество. Событие ограничено временем - не упустите шанс, начните прямо сейчас!
                            </p>
                            <a href="{{route('stream.quiz.start')}}"
                               data-turbo-method="post"
                               class="btn btn-primary m-auto d-flex align-items-center justify-content-center w-100 mt-4">
                                Начать викторину
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="my-4 text-center mb-lg-5">
                <a href="{{ route('home') }}" data-turbo-action="replace" class="link-body-emphasis text-decoration-none opacity-50">
                    Вернуться на главную
                </a>
            </div>
        </div>
    </div>

@endsection
