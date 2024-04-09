@php
    $user = $user ?? auth()->user();
@endphp

@if($user !== null)
    <div {{ $attributes->merge(['class' => 'bg-body-tertiary rounded overflow-hidden my-5 px-4 px-md-5 py-4 position-relative'])->except(['user', 'link']) }}>
        <div class="d-flex gap-4 align-items-center">
            <div class="col-auto col">
                <div class="avatar avatar-sm">
                    <img class="avatar-img rounded-circle"
                         src="{{ $user->avatar }}"
                         alt="{{ $user->title }}">
                </div>
            </div>

            <div class="w-25 text-truncate opacity-50">
                Новая запись
            </div>

            <div class="col-6 ms-auto text-end">
                <a href="{{ $link }}" class="btn btn-outline-primary stretched-link">Опубликовать</a>
            </div>
        </div>
    </div>
@endif
