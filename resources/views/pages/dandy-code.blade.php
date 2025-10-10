@extends('layout')
@section('title', 'Денди-код')
@section('description', 'Руководство о том, как писать код с аккуратностью, уважением к читателю и стилем — даже если вы новичок.')

@section('content')
    <x-header image="/img/dandy-code/hat.svg">
        <x-slot name="sup">
            Александр Черняев
        </x-slot>
        <x-slot name="title">
            Денди-код
        </x-slot>

        <x-slot name="description">
            Руководство о том, как писать код с аккуратностью, уважением к читателю и стилем — даже если вы новичок.
        </x-slot>

        <x-slot:actions>
            <a href="https://www.ozon.ru/product/dendi-kod-kak-pisat-kod-s-akkuratnostyu-uvazheniem-k-chitatelyu-i-stilem-dazhe-esli-vy-novichok-2855017761"
               target="_blank"
               class="btn btn-primary btn-lg px-4">
                Купить книгу
            </a>

            <a href="https://github.com/tabuna/dandy-code"
               target="_blank"
               class="d-none d-md-inline-flex link-body-emphasis text-decoration-none icon-link icon-link-hover">
                Репозиторий <x-icon path="i.arrow-right" class="bi" />
            </a>
        </x-slot>
    </x-header>



    <x-container>

        <div class="row g-4 g-md-5 pb-lg-5 align-items-start" data-controller="open-quiz">
            <div class="col-md-6">
                <main class="post position-relative quiz-code-hover d-flex flex-column h-100">


                <pre class="rounded position-relative p-4 language-php mt-auto" tabindex="0"><code
                        class="language-php">// Плохо ❌
public function hasAccess(User $user): bool {
    if (!$user->isBanned()) {
        if ($user->isAdmin()) {
            return true;
        } else {
            if ($user->isGranted(GRANT::EDIT)) {
                return true;
            } else {
                return false;
            }
            return false;
        }
    } else {
        return false;
    }
}
</code></pre>
                </main>

                <div class="d-none d-md-block">
                    <p class="text-balance"><strong>Для новичков</strong>, которые хотят сразу учиться писать чистый и понятный код.</p>

                    <p class="text-balance"><strong>Для опытных разработчиков</strong>, уставших от беспорядка и стремящихся к порядку и
                        эффективности.</p>

                    <p class="text-balance"><strong>Для руководителей и тимлидов</strong>, желающих донести до команды важность стиля и общих
                        правил.</p>
                </div>
            </div>

            <div class="col-md-6 order-md-first mb-4 mb-md-0">
                <h2 class="fw-bold text-uppercase mb-4" style="
    letter-spacing: -0.1rem;">
                    <span class="ms-4">Ты ведь и сам знаешь,</span>
                    <span class="d-block">как тяжело читать</span>
                    <span class="d-inline-flex ms-5">запутанный, неряшливый код</span>
                </h2>


                <div class="mb-4 text-balance bg-body-tertiary rounded p-4 p-xl-5 position-relative">

                    <p>Наверняка в школе у вас были тетрадки в клетку и в линейку — для каждого предмета свои.
                    На уроках математики вы аккуратно писали <mark class="text-nowrap">«2 + 2 = 4»</mark>, размещая каждый знак в отдельной клетке.
                    Оставляли пару строк между задачами, чтобы всё выглядело опрятно и не сливалось.</p>

                    <p>
                        Когда вы стали старше, преподаватель просил оформлять работы по правилам — чтобы было понятно и удобно проверять.
                        С кодом — то же самое. Если он написан неаккуратно и хаотично, с ним сложно разобраться.
                    <p>

                    <p>
                        А если код понятный, то работать с ним куда приятнее.
                        Вот чему учит «Денди-код».
                    </p>
                </div>
            </div>



            <div class="col-md-6">
                <main class="post position-relative quiz-code-hover">


                <pre class="rounded position-relative overflow-hidden p-4 language-php" tabindex="0" title="Василиса: Я уверена, что это не просто случайность. Что делает этот код?"><code
                        class="language-php">// Хорошо ✅
public function hasAccess(User $user): bool
{
    if ($user->isBanned()) {
        // Пользователь заблокирован
        return false;
    }

    if ($user->isAdmin()) {
        // Пользователь является администратором
        return true;
    }

    // Пользователь имеет разрешение на редактирование
    return $user->isGranted(GRANT::EDIT);
}
</code></pre>
                </main>
            </div>




            <div class="col-md-6 d-none d-md-block">
                <img src="/img/dandy-code/man.svg" class="img-fluid" style="max-height: 350px; margin: 0 auto; display: block">
                <div class="ms-3 lh-1 mt-3">
                    <div class="fw-bolder mb-1">Реальные советы</div>
                    <small class="opacity-50 text-balance d-block">
                        Можно сразу применить, чтобы сделать свои приложения лучше и прокачать навыки разработчика.
                    </small>
                </div>
            </div>





        </div>

    </x-container>


    <x-call-to-action
            link="{{ url('https://github.com/tabuna/dandy-code') }}"
            text="Скачать книгу бесплатно"
    >
        <x-slot:title>Пора стать «Денди»</x-slot>

        <x-slot:description>
            <p class="text-balance">
                Эта книга — не про архитектурные паттерны и не про фреймворки. Она про то, как писать
                код, который живёт в команде. Код, который приятно читать. Который легко поддерживать.
                Код, за который не будет стыдно, когда его откроют другие.
            </p>
        </x-slot>

        <x-slot:caption>
            <small class="text-balance d-block text-end opacity-75">
                * PDF версия для чтения на любом устройстве.
            </small>
        </x-slot>
    </x-call-to-action>

@endsection
