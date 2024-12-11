@extends('layout')
@section('title', 'Тайный Санта')
@section('description', 'Пусть каждый маленький жест сделает день особенным и принесет улыбку. С наступающими праздниками! 🎁')

@section('content')

    <div data-controller="santa"></div>


    <x-header>
        <x-slot:sup>Станьте частью волшебства!</x-slot>
        <x-slot:title>Тайный Санта Laravel 2025</x-slot>

        <x-slot:description>
            Пусть каждый маленький жест сделает день особенным и принесет улыбку. С наступающими праздниками! 🎁
        </x-slot>

        <x-slot:actions>
            <a href="{{ route('santa.game') }}" class="btn btn-primary btn-lg px-4">{{ $participant->exists ? 'Посмотреть статус' : 'Присоединится' }}</a>
            <a href="{{ route('santa.rules') }}" class="d-none d-md-inline-flex link-body-emphasis text-decoration-none icon-link icon-link-hover">
                Полные правила
                <x-icon path="i.arrow-right" class="bi"/>
            </a>
        </x-slot>

        <x-slot:content>
        <img src="/img/ui/santa/tree.svg" alt="Тайный Санта Laravel 2025"
             class="d-none d-sm-block mx-lg-auto img-fluid pe-none"
             style="max-height: 410px; object-fit: contain;"
             width="700"
             height="500"
             loading="lazy">
        </x-slot:content>

    </x-header>

    <x-container>
        <div class="text-primary mb-3 d-block text-uppercase fw-semibold ls-xl">Как это работает</div>
        <div class="bg-body-secondary p-5 rounded-5 overflow-hidden">
            <div class="row gx-5 gy-4 gy-md-5 row-cols-1 row-cols-lg-3 text-balance">
                <div class="col">
                    <p class="display-1 text-primary fw-bolder">1</p>
                    <h3 class="fs-2 fw-bolder">Присоединяйтесь к зимней игре</h3>
                    <hr class="w-25 text-primary">
                    <p class="text-balance">
                        Нажмите на кнопку "Присоединится" на странице игры, используя свой аккаунт.
                        Заполните информацию о себе это позволит нам организовать всё идеально.
                    </p>
                </div>
                <div class="col">
                    <p class="display-1 text-primary fw-bolder">2</p>
                    <h3 class="fs-2 fw-bolder">Узнайте, кого будете радовать</h3>
                    <hr class="w-25 text-primary">
                    <p class="text-balance">
                        18 декабря на вашей странице «Тайный Санта» появится информация о вашем получателе: его имя, предпочтения и адрес пункта выдачи Ozon.
                    </p>
                </div>
                <div class="col">
                    <p class="display-1 text-primary fw-bolder">3</p>
                    <h3 class="fs-2 fw-bolder">Закажите подарок на Ozon</h3>
                    <hr class="w-25 text-primary">
                    <p class="text-balance">
                        Найдите идеальный подарок на <a href="https://ozon.ru/" target="_blank">Ozon</a> и оформите заказ в указанный пункт выдачи. Это удобно: никаких
                        очередей и долгой доставки!
                    </p>
                </div>
                <div class="col">
                    <p class="display-1 text-primary fw-bolder">4</p>
                    <h3 class="fs-2 fw-bolder">Укажите номер заказа</h3>
                    <hr class="w-25 text-primary">
                    <p class="text-balance">
                        После покупки добавьте номер заказа и код получения на странице «Тайный Санта».
                        Это нужно, чтобы ваш подарок можно было получить в пункте выдачи.
                    </p>
                </div>
                <div class="col">
                    <p class="display-1 text-primary fw-bolder">5</p>
                    <h3 class="fs-2 fw-bolder">Получите свой подарок!</h3>
                    <hr class="w-25 text-primary">
                    <p class="text-balance">
                        Ждите подарок от вашего «Тайного Санты»! Как только он добавит информацию о своем заказе, вы
                        сможете забрать сюрприз в вашем пункте выдачи.
                    </p>
                </div>

                <div class="col">
                    <div class="p-4 p-xl-5 bg-body rounded d-flex flex-column h-100 position-relative d-flex align-items-center shadow-sm"
                    style="
    background: url(/img/ui/santa/pattern.svg);
    background-repeat: repeat;
    background-size: contain;">


                        <div class="text-decoration-none d-flex flex-column gap-1 text-center my-auto opacity-75">
                            <span class="d-block mb-3 display-1">
                              🎅
                            </span>

                            <div class="d-flex flex-column gap-1 fw-bolder text-balance" style="transform: rotate(10deg);">
                                <span class="text-primary fs-4 mb-1 fw-bolder">
                                    Хо<span class="text-body-emphasis">-</span>хо<span class="text-body-emphasis">-</span>хо
                                </span>

                                <span class="fs-5 text-body-emphasis">
                                    Праздник станет еще ярче!
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </x-container>


    {{--
    <x-container>
        <div class="row g-4 g-md-5">
            <div class="col-12 col-md-6">
                <div class="text-primary mb-3 d-block text-uppercase fw-semibold ls-xl">Деды морозы</div>
                <div class="row row-cols-3 row-cols-lg-6 text-center justify-content-center" style="filter:grayscale(100%)">
                    @foreach(\App\Models\User::all() as $user)
                        <div class="col">
                            <img class="img-fluid rounded-circle mb-3" src="{{ $user->avatar }}" alt="{{ $user->name }}" style="aspect-ratio: 1/1">
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="text-primary mb-3 d-block text-uppercase fw-semibold ls-xl">Гринчи</div>
                <div class="row row-cols-3 row-cols-lg-6 text-center justify-content-center" style="filter:grayscale(100%)">
                    @foreach(\App\Models\User::all() as $user)
                        <div class="col">
                            <img class="img-fluid rounded-circle mb-3" src="{{ $user->avatar }}" alt="{{ $user->name }}" style="aspect-ratio: 1/1">
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </x-container>
    --}}


    <x-container>
        <div class="row g-4 g-md-5  align-items-start position-relative mb-5">
            <div class="col-xl-4 py-3">
                <div class="mb-4">
                    <div
                        class="feature-icon-small d-inline-flex align-items-center justify-content-center border border-primary text-primary fs-4 rounded-3">
                        <x-icon path="i.faq"/>
                    </div>
                </div>
                <h5 class="fs-3 mt-2 fw-semibold">Часто задаваемые вопросы</h5>
            </div>
        </div>

        <div class="row row-cols-1 row-cols-md-2 g-4 g-md-5">

            <div class="col">
                <h6 class="fw-bolder mb-3">Какую информацию мне нужно указать?</h6>
                <p class="text-muted mb-0 pe-lg-5">
                    Укажите ваше полное имя, адрес, а также кратко расскажите о себе, чтобы ваш Тайный Санта мог выбрать подарок по вашему вкусу.
                </p>
            </div>

            <div class="col">
                <h6 class="fw-bolder mb-3">Кто получит информацию, которую я укажу?</h6>
                <p class="text-muted mb-0 pe-lg-5">
                    Вашу информацию, необходимую для отправки подарка, получит один случайный пользователь.
                    Следовательно, вы так же получаете информацию лишь об одном случайном участнике.
                </p>
            </div>

            <div class="col">
                <h6 class="fw-bolder mb-3">Как происходит распределение участников?</h6>
                <p class="text-muted mb-0 pe-lg-5">Пары формируются случайным образом, но в одностороннем порядке - каждый участник будет Тайным
                   Сантой для одного из пользователей.</p>
            </div>

            <div class="col">
                <h6 class="fw-bolder mb-3">Кто станет моим Тайным Сантой?</h6>
                <p class="text-muted mb-0 pe-lg-5">
                    Другой пользователь, оставивший заявку. Мы выберем его случайным образом.
                </p>
            </div>

            <div class="col">
                <h6 class="fw-bolder mb-3">До какого числа нужно отправить подарок?</h6>
                <p class="text-muted mb-0 pe-lg-5">
                    Все подарки должны быть отправлены до 25 декабря.
                </p>
            </div>

            <div class="col">
                <h6 class="fw-bolder mb-3">Каким должен быть мой подарок?</h6>
                <p class="text-muted mb-0 pe-lg-5">
                    Это зависит только от вас, конкретных критериев на стоимость подарков нет.
                </p>
            </div>

            <div class="col">
                <h6 class="fw-bolder mb-3">Почему нужно заказывать именно на маркетплейсе?</h6>
                <p class="text-muted mb-0 pe-lg-5">
                    Это компромисс между удобством и ламповостью. Он упрощает выбор и
                    доставку подарков, особенно для занятых участников, сохраняя при этом дух праздника.
                </p>
            </div>

            <div class="col">
                <h6 class="fw-bolder mb-3">Сколько денег я должен потратить на подарок?</h6>
                <p class="text-muted mb-0 pe-lg-5">
                    Это зависит только от вас, конкретных критериев на стоимость подарков нет. Помните, что
                    количество уделённого внимания важнее количества вложенных денег.
                </p>
            </div>

            <div class="col">
                <h6 class="fw-bolder mb-3">Как я могу связаться со своей парой?</h6>
                <p class="text-muted mb-0 pe-lg-5">
                    Никак. Если у вас есть сложности, свяжитесь с администрацией
                    проекта любым удобным для вас способом.
                </p>
            </div>

            <div class="col">
                <h6 class="fw-bolder mb-3">У меня еще остались вопросы!</h6>
                <p class="text-muted mb-0 pe-lg-5">
                    Задайте их в комментариях к посту в нашем сообществе.
                </p>
            </div>
        </div>

    </x-container>


@endsection
