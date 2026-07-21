<ul class="docs-search-results list-unstyled" id="found_candidates" aria-live="polite">
    @forelse($searchOffer as $offer)
        <li class="docs-result-item">
            <a href="{{ route('docs', ['version' => $offer->version, 'page' => "{$offer->file_for_url}#{$offer->slug}" ]) }}"
               class="docs-search-result text-decoration-none">
                <span class="docs-search-result-page">{{ strip_tags($offer->title_page) }}</span>
                <span class="docs-search-result-title">{{ strip_tags($offer->title) }}</span>
                <span class="docs-search-result-snippet">
                    {{ \Illuminate\Support\Str::limit(\Illuminate\Support\Str::squish(html_entity_decode(strip_tags($offer->content))), 220) }}
                </span>
            </a>
        </li>
    @empty
        @if($query !== '')
            <li class="docs-search-empty">
                <strong>Ничего не найдено</strong>
                <span>Попробуйте изменить формулировку или сократить запрос.</span>
            </li>
        @else
            <li class="docs-search-suggestions">
                <p class="docs-search-suggestions-label">Смотрите также:</p>

                <a href="{{ route('jobs') }}" class="docs-search-suggestion text-decoration-none">
                    <x-icon path="i.files" width="1.25rem" height="1.25rem"/>
                    <span class="docs-search-suggestion-copy">
                        <strong>Ищете работу?</strong>
                        <span>Посмотрите актуальные предложения на рынке.</span>
                    </span>
                </a>

                <a href="{{ route('orchid') }}" class="docs-search-suggestion text-decoration-none">
                    <x-icon path="i.orchid" width="1.25rem" height="1.25rem"/>
                    <span class="docs-search-suggestion-copy">
                        <strong>Orchid</strong>
                        <span>Создавайте административные панели на PHP.</span>
                    </span>
                </a>

                <a href="{{ route('pastebin') }}" class="docs-search-suggestion text-decoration-none">
                    <x-icon path="i.code" width="1.25rem" height="1.25rem"/>
                    <span class="docs-search-suggestion-copy">
                        <strong>Pastebin</strong>
                        <span>Делитесь фрагментами кода без длинных сообщений.</span>
                    </span>
                </a>
            </li>
        @endif
    @endforelse
</ul>
