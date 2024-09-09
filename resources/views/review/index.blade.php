@extends('html')
@section('title', 'Тестирование')
@section('description', 'Интерактивные тесты, которые помогут оценить ваш прогресс в изучении Laravel')

@section('body')

    <div class="container min-vh-100 d-flex align-content-center align-items-center">
        <div class="col-lg-6 mx-auto">
            <div id="quiz" class="p-4 p-xl-5 bg-body-secondary rounded position-relative">

                <h1 class="user-select-none text-center mb-3">
                    Раскрой секреты своего ремесла
                </h1>

                <div class="d-flex p-0">
                    <img src="{{ asset('/img/ui/review/quiz-start.svg') }}" class="w-100 img-fluid">
                </div>

                <div class="px-sm-4 pt-0">
                    <div class="d-flex align-items-center mb-3">
                        <div class="col">
                            <p class="fw-normal mb-0">
                                Повседневная практика показывает, что реализация намеченных плановых заданий
                                требуют от нас анализа существенных финансовых и административных условий. Товарищи!
                                консультация с широким активом играет важную роль в формировании модели развития.
                            </p>
                            <a href="{{route('stream.review.start')}}"
                               data-turbo-method="post"
                               rel="nofollow noopener noreferrer"
                               class="btn btn-primary m-auto d-flex align-items-center justify-content-center w-100 mt-4">
                                {{ Auth::check() ? 'Начать викторину' : 'Войдите, чтобы начать' }}
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
