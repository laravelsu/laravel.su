@extends('layout')
@section('title', 'Заявка на участие в Тайном Санте')

@section('content')

    <div data-controller="santa"></div>

    <x-container>
        <div class="row">
            <div class="bg-body-tertiary p-4 p-xl-5 rounded">
                <div class="col-xxl-8 mx-auto d-flex flex-column gap-3">

                    <x-profile :user="auth()->user()" class="mb-3"/>


                    @if($participant->exists && !$participant->hasReceiver())
                        <div class="alert alert-warning text-center lh-sm text-balance" role="alert">
                            <strong>У вас есть время до 20 декабря, чтобы изменить свою заявку! 🎁</strong>
                            <small class="opacity-75 d-block">После этой даты вы сможете увидеть информацию о получателе
                                                              вашего подарка и указать трек-номер для отправления.
                                                              🚚</small>
                        </div>
                    @endif


                    @if($participant->exists && $participant->hasReceiver())
                    <dl class="bg-body rounded shadow-sm p-4 py-4 d-flex flex-column gap-3">
                        <div class="d-flex">
                            <dt class="opacity-50 fw-light me-3 col-4">
                                О получателе:
                            </dt>
                            <dd class="text-body-emphasis">
                                {{  $participant->receiver->about }}
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
                    </dl>
                    @endif


                    <form action="{{ route('santa.start') }}" method="post">
                        @csrf

                        <div class="row g-5">

                            <div class="col-12 col-lg-7">

                                @if(!$participant->hasReceiver())
                                    <div class="mb-3">
                                        <label for="address" class="form-label">Адрес доставки</label>

                                        <div class="form-text">
                                            <p>Укажите свой домашний адрес или любого другого человека, который сможет
                                               забрать и
                                               передать вашу посылку с подарком, например, ваш родственник или близкий
                                               друг -
                                               договоритесь с ними, если не хотите раскрывать свою личность Тайному
                                               Санте.</p>
                                        </div>

                                        <textarea
                                            class="form-control text-balance mb-3 p-4 {{ $errors->has('address') ? 'is-invalid' : '' }}"
                                            required
                                            @disabled($participant->hasReceiver())
                                            rows="4"
                                            name="address"
                                            id="address"
                                            placeholder="398027, г.Липецк ул. Красивых молдавских партизан, д.6 кв.18 Багратионов Иван Дмитриевич">{{ old('address', $participant->address) }}</textarea>
                                        <x-error field="address" class="invalid-feedback my-3"/>
                                        <div class="form-text">
                                            <p>Укажите полный адрес, чтобы ваш подарок точно нашёл вас! Для
                                               непредвиденных случаев желательно указать номер телефона.</p>
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
                                @endif

                                @if($participant->hasReceiver())
                                    <div class="mb-3">
                                        <label for="tracking_number" class="form-label">Трек-номер</label>
                                        <input
                                            class="form-control mb-3 {{ $errors->has('tracking_number') ? 'is-invalid' : '' }}"
                                            name="tracking_number"
                                            id="tracking_number"
                                            type="text"
                                            placeholder="Введите трек-номер отправления"
                                            value="{{ old('tracking_number', $participant->tracking_number) }}"/>
                                        <x-error field="tracking_number" class="invalid-feedback my-3"/>
                                        <div class="form-text">
                                            Если вы уже отправили подарок, укажите трек-номер для отслеживания.
                                        </div>
                                    </div>
                                @endif

                            </div>

                            @if(!$participant->hasReceiver())
                            <div class="col-12 col-lg-5 d-none d-lg-block">
                                <div class="bg-body rounded p-4 d-flex flex-column gap-3">
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
                                </div>
                            </div>
                            @endif

                        </div>

                        <div
                            class="mt-3 d-flex flex-column flex-md-row justify-content-center justify-content-md-start align-items-md-baseline">
                            <button type="submit"
                                    class="btn btn-primary mb-3 mb-md-0">{{ $participant->exists ? "Обновить заявку" : "Отправить заявку" }}</button>

                            @if($participant->exists && !$participant->hasReceiver())
                                <a class="justify-content-center justify-content-md-start link-body-emphasis ms-md-auto icon-link text-decoration-none"
                                   data-turbo-method="delete"
                                   data-turbo-confirm="Вы уверены, что хотите удалить свою заявку?"
                                   href="{{route('santa.delete')}}">
                                    Я не смогу участвовать :(
                                </a>
                            @endif
                        </div>


                    </form>

                </div>
            </div>
        </div>
    </x-container>
@endsection
