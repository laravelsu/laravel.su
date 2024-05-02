@if ($quiz->isFinish())
    <turbo-stream target="quiz" action="update">
        <template>
            <div class="d-flex p-0">
                <img src="{{ asset('/img/ui/certificate.svg') }}" class="w-100 img-fluid">
            </div>
            <div class="card-body px-sm-4 pt-0">
                <div class="d-flex align-items-center mb-2">
                    <div class="col">
                        <p>
                            Разнообразный и богатый опыт постоянное информационно-пропагандистское обеспечение нашей
                            деятельности требуют определения и уточнения модели развития. Не следует, однако забывать,
                            что реализация намеченных плановых заданий в значительной степени обуславливает создание
                            форм развития.
                        </p>

                        <p>
                            Повседневная практика показывает, что реализация намеченных плановых заданий
                            требуют от нас анализа существенных финансовых и административных условий. Товарищи!
                            консультация с широким активом играет важную роль в формировании модели развития.
                        </p>


                        <a href="{{route('home')}}" data-turbo-action="replace"
                           class="btn btn-primary m-auto d-flex align-items-center justify-content-center w-100">
                            Вернуться на главную
                        </a>
                    </div>
                </div>
            </div>
        </template>
    </turbo-stream>

@elseif($quiz->isDead())
    <turbo-stream target="quiz" action="update">
        <template>
            <div class="d-flex p-0">
                <img src="{{ asset('/img/ui/items.svg') }}" class="w-100 img-fluid">
            </div>
            <div class="card-body px-sm-4 pt-0">
                <div class="my-3">
                    <h1 class="">
                        Провал.
                    </h1>

                    <p>
                        Пожалуйста, примите это сообщение как возможность для улучшения. Мы рекомендуем вам ознакомиться
                        с материалами на нашем сайте, чтобы подготовиться к повторному тестированию.
                    </p>
                </div>
                <a href="{{route('stream.review.start')}}"
                   data-turbo-method="post"
                   class="btn btn-primary d-flex align-items-center">
                    <x-icon path="et.reset" class="ms-auto me-2"/>
                    <span class="me-auto">Попробовать снова</span>
                </a>
            </div>
        </template>
    </turbo-stream>
@else
    <turbo-stream target="quiz" action="update">
        <template>
            <div class="card-body px-sm-4 pt-sm-3">
                <div>
                    @if(!$quiz->displayInfo)
                        <div class="d-flex align-items-baseline">
                            <div class="mb-3 me-2 d-flex flex-column">
                                {!! $currentQuestion->getTitle() !!}
                            </div>

                            <a href="{{route('review')}}"
                               data-turbo-action="replace"
                               title="Попробовать снова"
                               class="ms-auto btn btn-close px-2">
                            </a>
                        </div>
                        <div data-controller="prism">
                            @if($quiz->isIncorrect)
                                <p class="text-center text-muted">
                                    Неверно, попробуйте еще раз.
                                </p>
                            @endif

                            @foreach($currentQuestion->getAnswers() as $answer)
                                <a href="{{ route('stream.review.set-answer', ['answer'=> $answer]) }}"
                                   data-turbo-method="post"
                                   class="btn btn-secondary w-100 mb-3
                                   d-block text-start fw-normal
                                   answer
                                    {{ $currentQuestion->isCorrect($quiz->userAnswer) && $answer === $quiz->userAnswer ? 'btn-success' : '' }}
                                    {{ $quiz->hasIncorrectAnswer($answer) ? 'btn-danger disabled' : '' }}"
                                >
                                    {!! \Illuminate\Support\Str::of($answer)->markdown() !!}
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div>
                            <h5 class="text-center mb-3">Верно!</h5>

                            <div class="alert alert-success mb-3 py-2" role="alert">
                                <strong>{{ __($quiz->userAnswer) }}</strong>
                            </div>

                            <p class="text-muted">
                                Ты ответил правльно! Поздравляю! Вот что я могу тебе рассказать:<br>
                                Повседневная практика показывает, что реализация намеченных плановых заданий
                                требуют от нас анализа существенных финансовых и административных условий. Товарищи!
                                консультация с широким активом играет важную роль в формировании модели развития.
                            </p>
                        </div>
                    @endif
                </div>

                <div class="row align-items-center mb-3 opacity-75">
                    <div class="col-auto text-primary">
                        @foreach(range(1, \App\Quiz\QuizState::LIVE) as $value)
                            <span class="position-relative">
                                <x-icon path="{{ $quiz->live >= $value ? 'bs.heart-fill' : 'bs.heartbreak' }}"
                                        class="{{ $quiz->live >= $value ? '' : 'text-secondary opacity-50' }}"/>
                            </span>
                        @endforeach
                    </div>
                    <div class="col d-flex align-items-center">
                        <div class="col px-2">
                            <div class="progress border border-pro progress-bg-absolute">
                                <div class="progress-bar bg-index" role="progressbar"
                                     style="width:{{ $quiz->currentStepPercent() }}%">
                                </div>
                            </div>
                        </div>
                        <div class="col-auto d-flex align-items-center px-2">
                            <small class="text-muted"> {{ $quiz->step + 1 }}/{{ count($quiz->questions) }}</small>
                        </div>
                    </div>
                </div>


                @if($quiz->step == count($quiz->questions))
                    <div class="">
                        <a href="{{route('stream.review.next')}}"
                           {{ !empty($quiz->userAnswer) ? '' : 'disabled'  }}
                           data-turbo-method="post"
                           class="btn btn-primary w-100" aria-current="true">
                            {{__('quiz.complete.button')}}
                        </a>
                    </div>
                @elseif($quiz->displayInfo)
                    <div class="">
                        <a href="{{route('stream.review.next')}}"
                           data-turbo-method="post"
                           class="btn btn-primary w-100" aria-current="true">
                            Продолжить
                        </a>
                    </div>
                @endif
            </div>

        </template>
    </turbo-stream>
@endif
