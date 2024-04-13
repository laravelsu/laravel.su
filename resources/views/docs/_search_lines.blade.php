<ul class="d-grid gap-4 mt-5 mb-0 list-unstyled" id="found_candidates">
    @forelse($searchOffer as $offer)
        <li class="position-relative overflow-hidden docs-result-item">
            <a href="{{ route('docs', ['version' => $offer->version, 'page' => "{$offer->file_for_url}#{$offer->slug}" ]) }}"
               class="stretched-link"></a>

            <p class="mb-1 fw-bold font-monospace small text-primary">{{ $offer->title_page }}</p>
            <p class="h5 mb-2">{!!  $offer->title !!}</p>
            <div class="small line-clamp line-clamp-4 opacity-75 main">{!! $offer->content !!}</div>
        </li>

        @if(!$loop->last)
            <li class=""><hr class="my-1"></li>
        @endif
    @empty
        <li class="d-flex gap-4">
            <small class="text-primary">Смотрите так же:</small>
        </li>

        <li class="d-flex gap-4 position-relative">
            <x-icon path="i.files" class="text-body-secondary flex-shrink-0 mt-1" width="1.5rem" height="1.5rem"/>
            <div>
                <h5 class="mb-0">Ищите работу?</h5>
                <small class="opacity-75">Посмотрите раздел вакансии с самыми вкусными предложениями на рынке.</small>
            </div>

            <a href="{{ route('jobs') }}" class="stretched-link"></a>
        </li>
        <li class="d-flex gap-4 position-relative">
            <x-icon path="i.orchid" class="text-body-secondary flex-shrink-0 mt-1" width="1.5rem" height="1.5rem"/>
            <div>
                <h5 class="mb-0">Orchid</h5>
                <small class="opacity-75">Создавайте админ панели и внутренние бизнес системы основанные на PHP коде.</small>
            </div>

            <a href="{{ route('orchid') }}" class="stretched-link"></a>
        </li>
        <li class="d-flex gap-4 position-relative">
            <x-icon path="i.code" class="text-body-secondary flex-shrink-0 mt-1" width="1.5rem" height="1.5rem"/>
            <div>
                <h5 class="mb-0">Pastebin</h5>
                <small class="opacity-75">Делитесь своим кодом правильно, ни кто не любит простыню текста в сообщениях.</small>
            </div>

            <a href="{{ route('pastebin') }}" class="stretched-link"></a>
        </li>
    @endforelse
</ul>
