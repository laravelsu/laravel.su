@extends('layout')
@section('title', 'Заявка на участие в игру "Тайный Санта"')

@section('content')

    <div data-controller="santa"></div>

    <x-container>
        <div class="row">
            <div class="bg-body-tertiary p-4 p-xl-5 rounded position-relative">

                <button type="button"
                        data-controller="history"
                        data-history-url-value="{{route('santa')}}"
                        data-action="click->history#back"
                        class="position-absolute top-0 end-0 m-4 btn btn-link link-secondary text-decoration-none fs-3 d-none d-md-inline">
                    <x-icon path="bs.x-lg"/>
                </button>

                <div class="col-xxl-8 mx-auto d-flex flex-column gap-3">

                    <x-profile :user="auth()->user()" class="mb-3"/>


                    @if($participant->exists && !$participant->hasReceiver())
                        <div class="alert alert-warning text-center lh-sm text-balance" role="alert">
                            <strong>У вас есть время до 20 декабря, чтобы изменить свою заявку! 🎁</strong>
                            <small class="opacity-75 d-block">После этой даты вы сможете увидеть информацию о получателе
                                                              вашего подарка и указать секретный-номер для отправления.
                                                              🚚</small>
                        </div>
                    @endif


                    @if($participant->exists && $participant->hasReceiver())
                    <dl class="bg-body rounded shadow-sm p-4 py-4 d-flex flex-column gap-3">

                        <div class="d-flex">
                            <dt class="opacity-50 fw-light me-3 col-4">
                                Номер получателя:
                            </dt>
                            <dd class="text-body-emphasis">
                                {{ $participant->receiver->id }}
                            </dd>
                        </div>
                        <div class="d-flex">
                            <dt class="opacity-50 fw-light me-3 col-4">
                                Адрес:
                            </dt>
                            <dd class="text-body-emphasis">
                                {{  $participant->receiver->address }}
                            </dd>
                        </div>
                        <div class="d-flex">
                            <dt class="opacity-50 fw-light me-3 col-4">
                                Вот, что получатель рассказал о себе:
                            </dt>
                            <dd class="text-body-emphasis">
                                {!! nl2br(e($participant->receiver->about)) !!}
                            </dd>
                        </div>
                    </dl>
                    @endif


                    <form action="{{ route('santa.game') }}" method="post">
                        @csrf

                        <div class="row g-5">

                            <div class="col-12 col-lg-7">

                                @if(!$participant->hasReceiver())
                                    <div class="mb-3">
                                        <label for="address" class="form-label">Адрес доставки</label>

                                        <div class="form-text">
                                            <p>
                                                Укажите адрес пункта выдачи OZON, удобного для вас.
                                                Например, рядом с домом. Или постамат в торговом центре.
                                            </p>
                                        </div>

                                        <textarea
                                            class="form-control text-balance mb-3 p-4 {{ $errors->has('address') ? 'is-invalid' : '' }}"
                                            required
                                            @disabled($participant->hasReceiver())
                                            rows="4"
                                            name="address"
                                            id="address"
                                            placeholder="г.Липецк ул. Красивых молдавских партизан, д.6">{{ old('address', $participant->address) }}</textarea>
                                        <x-error field="address" class="invalid-feedback my-3"/>
                                        <div class="form-text">
                                            <p><a href="https://www.ozon.ru/geo/" target="_blank">Укажите полный адрес</a>, чтобы ваш подарок точно нашёл вас! Лучше всего выбрать постамат</p>
                                        </div>

                                    </div>
                                    <div class="mb-3">
                                        <label for="about" class="form-label">О себе</label>

                                        <div class="form-text">
                                            <p>Помогите Тайному Санте лучше понять ваши предпочтения: расскажите о своих
                                               увлечениях, любимых вещах, размере одежды и том, чего вы точно не хотите
                                               получить в подарок.
                                            </p>
                                        </div>

                                        <textarea
                                            class="form-control text-balance mb-3 p-4 {{ $errors->has('about') ? 'is-invalid' : '' }}"
                                            required
                                            rows="10"
                                            @disabled($participant->hasReceiver())
                                            name="about"
                                            id="about"
                                            placeholder="Люблю настольные игры, научную фантастику и походы. У меня есть кошка. Размер одежды — XL. У меня аллергия на сладости.">{{ old('about', $participant->about) }}</textarea>
                                        <x-error field="about" class="invalid-feedback my-3"/>
                                        <div class="form-text">
                                            Пожалуйста, указывайте только общие предпочтения, без конкретных запросов.
                                            Тайный Санта сам подберет для вас лучший подарок, учитывая ваши интересы и
                                            пожелания!
                                        </div>
                                    </div>


                                    <div class="mb-3">
                                        <label for="about" class="form-label">Контакты</label>

                                        <div class="form-text">
                                            <p>
                                                Для непредвиденных случаев укажите номер телефона.
                                            </p>
                                        </div>

                                        <div class="mb-3">
                                            <input
                                                class="form-control text-balance mb-3 p-4 {{ $errors->has('phone') ? 'is-invalid' : '' }}"
                                                required
                                                @disabled($participant->hasReceiver())
                                                name="phone"
                                                id="phone"
                                                pattern="^\+7\d{10}$"
                                                value="{{ old('phone', $participant->phone) }}"
                                                placeholder="+79513000000"/>
                                            <x-error field="phone" class="invalid-feedback my-3"/>
                                            <div class="form-text">
                                                Его увидит только администрация сайта и свяжется с вами в случае необходимости.
                                            </div>
                                        </div>


                                        <input
                                            class="form-control text-balance mb-3 p-4 {{ $errors->has('telegram') ? 'is-invalid' : '' }}"
                                            @disabled($participant->hasReceiver())
                                            name="phone"
                                            id="phone"
                                            value="{{ old('telegram', $participant->telegram) }}"
                                            placeholder="@tabuna"/>
                                        <x-error field="telegram" class="invalid-feedback my-3"/>
                                        <div class="form-text">
                                            Мы бы предпочти решать все вопросы оперативно в Telegram. Укажите ваш никнейм, если у вас есть аккаунт.
                                            Его так же увидит только администрация сайта.
                                        </div>
                                    </div>
                                @endif

                                @if($participant->hasReceiver())
                                    <div class="mb-3">
                                        <label for="tracking_number" class="form-label">Цифровой код</label>
                                        <input
                                            class="form-control mb-3 p-4 {{ $errors->has('tracking_number') ? 'is-invalid' : '' }}"
                                            name="tracking_number"
                                            id="tracking_number"
                                            type="text"
                                            placeholder="Введите секретный код заказа"
                                            value="{{ old('tracking_number', $participant->tracking_number) }}"/>
                                        <x-error field="tracking_number" class="invalid-feedback my-3"/>
                                        <div class="form-text">
                                            Если вы уже отправили подарок, укажите цифровой код, который находится
                                            под штрихкодом в приложении или в разделе “Заказы” на сайте.
                                        </div>
                                    </div>
                                @endif

                            </div>


                            <div class="col-12 col-lg-5 d-none d-lg-block">
                                <div class="bg-body rounded p-4 d-flex flex-column gap-3">


                                    @if(!$participant->hasReceiver())
                                        <small class="fw-bolder d-block">Полезные советы</small>
                                        <p class="opacity-50 mb-0 small text-balance">
                                            Тайный Санта — это весёлый обмен подарками. Не бойтесь проявить фантазию!
                                            Если вы сомневаетесь в выборе, небольшой сувенир или открытка всегда уместны.
                                        </p>

                                    <p class="opacity-50 mb-0 small text-balance">
                                        Вашу личную информацию, необходимую для отправки подарка, получит лишь один
                                        пользователь. Следовательно, вы так же получаете информацию лишь об одном
                                        случайном участнике.
                                    </p>


                                    <p class="opacity-50 mb-0 small text-balance">
                                        Если вы не сможете участвовать в Тайном Санте, удалите свою заявку
                                        <strong>до жеребьевки</strong>, чтобы не стать гринчем и не испортить праздник.
                                    </p>
                                    @else
                                        <small class="fw-bolder d-block">❗ Код в каждом заказе уникален и обновляется раз в сутки.</small>

                                        <p class="opacity-50 mb-0 small text-balance">
                                            Пожалуйста, укажите так что бы он был актуален на момент отправки и
                                            у получателя была возможность забрать его в течении дня.
                                        </p>
                                    @endif
                                </div>
                            </div>

                        </div>

                        <div
                            class="mt-3 d-flex flex-column flex-md-row justify-content-center justify-content-md-start align-items-md-baseline gap-4">
                            <button type="submit"
                                    class="btn btn-primary mb-3 mb-md-0">{{ $participant->exists ? "Обновить заявку" : "Отправить заявку" }}</button>

                            @if($participant->exists && !$participant->hasReceiver())
                                <a class="justify-content-center justify-content-md-start link-body-emphasis icon-link text-decoration-none"
                                   data-turbo-method="delete"
                                   data-turbo-confirm="Вы уверены, что хотите удалить свою заявку?"
                                   href="{{route('santa.delete')}}">
                                    Я не смогу участвовать :(
                                </a>
                            @endif

                            {{--
                            @if($participant->exists && $participant->hasReceiver())
                                <a class="justify-content-center justify-content-md-start link-body-emphasis icon-link text-decoration-none"
                                   data-turbo-method="delete"
                                   data-turbo-confirm="Вы точно получили подарок?"
                                   href="{{route('santa.delete')}}">
                                    Я получил подарок 🎁
                                </a>
                            @endif
                            --}}
                        </div>


                    </form>

                </div>
            </div>
        </div>
    </x-container>
@endsection
