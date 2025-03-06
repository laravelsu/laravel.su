<div {{ $attributes->merge([
    'class' => 'd-flex align-items-center',
    'itemscope' => 'itemscope',
    'itemprop' => 'author',
    'itemtype' => 'https://schema.org/Person'
    ])->except('user') }}
>
    <div class="avatar avatar-sm me-3">
        <a href="{{route('profile', $user)}}">
            <img class="avatar-img rounded-circle" src="{{ $user->avatar }}"
                 alt="{{ $user->title }}">
        </a>
    </div>

    <div class="small">
        <h6 class="mb-0 me-4">
            <a href="{{route('profile',$user)}}"
               class="link-body-emphasis text-decoration-none">
                <span itemprop="name">{{ $user->name }}</span>

                @if(!is_null($user->milestone))
                    <span class="text-primary small">({{ $user->milestone->name() }})</span>
                @endif
            </a>
        </h6>
        <p class="mb-0 small line-clamp line-clamp-1 opacity-75 me-4">{{ $user->presenter()->about() }}</p>
    </div>
</div>
