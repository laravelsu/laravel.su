@extends('layout')
@section('title', 'Скелет для вашего пакета Laravel')
@section('description', 'У вас идея для пакета Laravel? Начните с этого генератора, чтобы получить заготовочный код и сконцентрироваться на вашем проекте.')

@section('content')
    <x-header image="/img/ui/crane-h.svg">
        <x-slot name="sup">
            С чего начать?
        </x-slot>
        <x-slot name="title">
            Скелет для вашего пакета Laravel
        </x-slot>

        <x-slot name="description">
            У вас идея для пакета Laravel? Начните с этого генератора, чтобы получить заготовочный код и
            сконцентрироваться на вашем проекте.
        </x-slot>
        <x-slot name="content">
            <div class="position-relative mb-5 marketing" style="transform: rotate(350deg);">
                <div class="text-balance bg-body-secondary rounded p-4 p-xl-5 position-relative">
                    <blockquote>Кажется, я реализовывал это раньше, мне нужно проверить некоторые старые
                                проекты...
                    </blockquote>
                    <div class="d-flex align-items-center">
                        <img alt="image" height="50" class="rounded-circle" src="/img/avatars/avatar1.svg">
                        <div class="ms-3 lh-1">
                            <div class="fw-bolder mb-1">Разработчик</div>
                            <small class="opacity-50">Который неэффективно использует свои решения повторно.</small>
                        </div>
                    </div>
                </div>
            </div>
        </x-slot>
    </x-header>


    <x-container>
        <div class="main mb-4">

            <section class="row g-3 g-md-5 mb-5 pb-5 justify-content-center align-items-baseline">
                <div class="col-lg-6 py-lg-4 pe-lg-5">

                    <div class="p-4 p-xl-5 bg-body-tertiary rounded position-relative mb-4">
                        <x-icon path="bs.github" class="mb-1 fs-1 text-body-secondary"/>
                        <h4 class="fw-semibold">Руководство</h4>

                        <div class="pe-lg-5">

                            <p>
                                Для начала загрузите архив с шаблоном пакета. В нем содержатся все необходимые материалы
                                для создания пакета Laravel.
                            </p>


                            <a href="https://github.com/spatie/package-skeleton-laravel/archive/refs/heads/main.zip"
                               class="btn btn-primary d-block w-100 mb-3"
                               download="package-skeleton-laravel.zip"
                            >Скачать скелет</a>

                            <p>
                                После загрузки архива, распакуйте его в папку, где планируете разместить ваш пакет.
                            </p>

                            <p>
                                Далее, откройте терминал и выполните следующую команду. Вам потребуется ответить на
                                несколько вопросов, таких как название вашего пакета:
                            </p>

                            <pre class="rounded-3 language-shell" tabindex="0"><code language="shell">php ./configure.php</code></pre>

                            <p>
                                Готово. Наслаждайтесь процессом создания вашего собственного пакета!
                            </p>
                        </div>
                    </div>

                    <p class="text-center d-none d-md-block w-75 mx-auto opacity-75">
                        Для предложений смотрите <a href="https://github.com/spatie/package-skeleton-laravel"
                                                    target="_blank">исходный код</a>.
                    </p>


                </div>
                <div class="col-lg-6 py-lg-4 ps-lg-5 border-lg-start">

                    <h4 class="fw-semibold">Мотивация</h4>

                    <div class="pe-lg-5">
                        <p>
                            Есть так много преимуществ, когда вы начинаете инкапсулировать свой код в более мелкие,
                            более управляемые пакеты:
                        </p>

                        <ul class="text-balance ms-3">
                            <li><strong>Управляемость:</strong> код становится легче обслуживать и изменять.</li>
                            <li><strong>Тестируемость:</strong> пакеты упрощают процесс тестирования кода.</li>
                            <li><strong>Документация:</strong> легче документировать отдельные пакеты, облегчая понимание их функционала.</li>
                            <li><strong>Повторное использование:</strong> создавая компоненты программного обеспечения в виде пакетов, вы можете повторно использовать их в будущих проектах.</li>
                            <li><strong>Вовлечение в сообщество:</strong> откройте исходный код и получите выгоду от PHP-сообщества.</li>
                        </ul>

                        <p>
                            Помимо перечисленного, есть и другие преимущества, делающие создание пакетов обязательным
                            шагом в разработке программного обеспечения.
                        </p>
                    </div>
                </div>
            </section>
        </div>

    </x-container>
@endsection
