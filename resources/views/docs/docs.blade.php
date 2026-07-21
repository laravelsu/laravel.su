@extends('layout')
@section('title', $docs->title(). " Laravel ". $docs->version)
@section('description', $docs->description())

@push('head')
    <link rel="canonical" href="{{ route('docs', ['version'=> \App\Docs::DEFAULT_VERSION, 'page'=> $docs->name]) }}">
@endpush

@section('content')

    @php
        $translationStatus = match (true) {
            $docs->behind() === null => 'Статус перевода не отмечен',
            $docs->behind() > 0 => 'Перевод отстаёт на '.$docs->behind().' изменения',
            default => 'Перевод актуален',
        };

        $translationStatusClass = match (true) {
            $docs->behind() === null => 'docs-status-unknown',
            $docs->behind() > 0 => 'docs-status-behind',
            default => 'docs-status-current',
        };
    @endphp

    <div class="container container-docs my-4 my-xxl-5 mx-auto"
         data-controller="docs"
         data-action="keydown@window->docs#openSearch">
        <div class="row gap-2 justify-content-center align-items-start position-relative mb-5">
            <aside class="col-3 col-xl-3 col-xxl-2 order-md-first order-last position-sticky py-md-3 z-1 d-none d-lg-block doc-navigation"
                   aria-label="Навигация по документации">
                <div class="mb-md-4 ms-md-4 d-flex align-items-stretch flex-column offcanvas-md offcanvas-start" id="docs-menu">

                    <button type="button"
                            class="docs-search-trigger btn d-flex align-items-center gap-2 w-100 text-start"
                            data-bs-toggle="modal"
                            data-bs-target="#docs-search-modal"
                            data-docs-target="searchTrigger"
                            aria-label="Поиск по документации">
                        <x-icon path="i.search" width="1em" height="1em" aria-hidden="true" focusable="false" />
                        <span class="flex-grow-1">Поиск</span>
                        <kbd class="docs-search-shortcut" aria-hidden="true">⌘K</kbd>
                    </button>

                    <nav aria-label="Разделы документации">
                    <ul class="docs-menu-list list-unstyled">
                        @foreach ($docs->getMenu() as $item)
                            @php
                                $sectionIsActive = active(
                                    collect($item['list'])->map(fn($link) => $link['href']),
                                    'true',
                                    'false'
                                ) === 'true';
                            @endphp
                            <li>
                                <button
                                    class="docs-menu-section btn d-flex align-items-center border-0 {{ $sectionIsActive ? '' : 'collapsed' }} w-100 text-start"
                                    data-action="click->docs#toggleMenuSection"
                                    aria-controls="{{ \Illuminate\Support\Str::slug($item['title']) }}-collapse"
                                    aria-expanded="{{ $sectionIsActive ? 'true' : 'false' }}">
                                    {{ $item['title'] }}
                                </button>

                                <div class="docs-menu-panel"
                                    id="{{ \Illuminate\Support\Str::slug($item['title']) }}-collapse"
                                    data-expanded="{{ $sectionIsActive ? 'true' : 'false' }}"
                                    aria-hidden="{{ $sectionIsActive ? 'false' : 'true' }}"
                                    @if(!$sectionIsActive) inert @endif>
                                    <div class="docs-menu-panel-inner">
                                    <ul class="docs-menu-links list-unstyled fw-normal">
                                        @foreach ($item['list'] as $link)
                                            <li>
                                                <a href="{{ $link['href'] }}"
                                                    class="{{ active(url($link['href']), 'active') }} text-decoration-none"
                                                    {!! active(url($link['href']), 'aria-current="page"') !!}>
                                                    {{ $link['title'] }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                    </nav>


                    <div class="d-flex align-items-center p-4 p-sm-0 mb-3">
                        <select class="form-select form-select-sm rounded-3" onchange="Turbo.visit(this.value);">
                            <optgroup label="Версия">
                                @foreach (\App\Docs::SUPPORT_VERSIONS as $version)
                                    <option
                                        value="{{ route('docs', ['version' => $version]) }}"
                                        @selected(active(route('docs', ['version' => $version]).'*'))>{{ $version }}</option>
                                @endforeach
                            </optgroup>
                        </select>

                        @if($docs->behind() === null)
                            <a href="{{ route('status', $docs->version) }}#{{ $docs->file }}" class="mx-3 text-decoration-none text-secondary" title="Перевод не имеет метки">
                                ●
                            </a>
                        @elseif ($docs->behind() > 0)
                            <a href="{{ route('status', $docs->version) }}#{{ $docs->file }}" class="mx-3 text-decoration-none text-primary" title="Отстаёт на {{ $docs->behind() }} изменения">
                                ●
                            </a>
                        @else
                            <a href="{{ route('status', $docs->version) }}#{{ $docs->file }}" class="mx-3 text-decoration-none text-success" title="Перевод актуален">
                                ●
                            </a>
                        @endif

                        <a href="{{ $docs->getOriginalUrl() }}" title="Посмотреть оригинал" target="_blank"
                           class="link-body-emphasis text-decoration-none d-none d-md-block">
                            <x-icon path="i.translation" />
                        </a>
                    </div>

                    <x-docs.banner />

                </div>
            </aside>
            <div class="px-0 px-md-2 px-xl-3 col-md-10 col-lg-8 col-xl-8 col-xxl-6 order-md-1 order-first">

                <div class="docs-content-surface bg-body-tertiary p-4 p-xl-5 rounded">
                    <main class="p-md-4 documentations position-relative" data-controller="prism">
                        <h1 class="display-6 fw-bold text-body-emphasis">{{ $docs->title() }}</h1>

                        <div class="docs-context d-flex flex-wrap align-items-center gap-2 mt-3 mb-4"
                             aria-label="Сведения о документе">
                            <span class="docs-context-item">Laravel {{ $docs->version }}</span>
                            <a href="{{ route('status', $docs->version) }}#{{ $docs->file }}"
                               class="docs-context-item {{ $translationStatusClass }} text-decoration-none">
                                <span class="docs-status-dot" aria-hidden="true"></span>
                                {{ $translationStatus }}
                            </a>
                            <a href="{{ $docs->getOriginalUrl() }}"
                               class="docs-context-item text-decoration-none"
                               target="_blank"
                               rel="noopener">
                                Оригинал
                                <span aria-hidden="true">↗</span>
                            </a>
                        </div>

                        @if ($docs->name === 'installation')
                            <nav class="docs-quick-actions mb-4" aria-label="Быстрый переход по установке">
                                <a href="#ustanovka-php-i-ustanovshhika-laravel"
                                   data-controller="scroll"
                                   data-to="#ustanovka-php-i-ustanovshhika-laravel">
                                    Установить PHP и Laravel
                                </a>
                                <a href="#sozdanie-prilozeniia"
                                   data-controller="scroll"
                                   data-to="#sozdanie-prilozeniia">
                                    Создать приложение
                                </a>
                            </nav>
                        @endif

                        @if ($docs->isOlderVersion())
                            <div class="d-flex flex-md-row flex-column px-4 px-xl-5 py-3 py-xl-4 bg-body-secondary rounded position-relative align-items-md-center my-4">
                                <div class="vr bg-primary position-absolute start-0 opacity-100" style="top: 1.5em; bottom: 1.5em;"></div>
                                <div class="my-3 my-md-0 col-md-10">
                                    <div class="d-flex align-items-center mb-3 mb-md-0">
                                        <div class="mb-1 d-block fw-bold text-balance">Вы просматриваете документ для прошлой версии.</div>
                                    </div>
                                    <div class="mb-0 d-block opacity-75 text-balance">
                                        Рассмотрите возможность обновления вашего проекта до актуальной версии <code>{{ \App\Docs::DEFAULT_VERSION }}</code>.
                                        <a href="{{ route('library.upgrade') }}" class="link-body-emphasis stretched-link link-underline-opacity-25 d-block">Почему это важно?</a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <details class="docs-table-of-contents d-block d-xxl-none mt-3 mb-4">
                            <summary>
                                <span>
                                    <span class="docs-toc-label">На этой странице</span>
                                    <span class="docs-toc-current" data-docs-target="currentSection"></span>
                                </span>
                                <span class="docs-toc-chevron" aria-hidden="true"></span>
                            </summary>
                            <div class="docs-table-of-contents-body">
                                <x-docs.anchors :content="$content"/>
                            </div>
                        </details>
                        <x-docs.content :content="$content"/>
                    </main>
                </div>
            </div>
            <aside class="col-3 col-xl-2 order-last position-sticky py-md-3 z-1 d-none d-xxl-block doc-navigation"
                   aria-label="Навигация по текущей странице">
                <div class="mb-md-4 d-flex align-items-stretch flex-column offcanvas-md offcanvas-start" id="docs-anchors">
                    <p class="docs-sidebar-label">На этой странице</p>
                    <x-docs.anchors :content="$content"/>
                </div>
            </aside>
        </div>
    </div>

    <div class="modal docs-search-modal fade"
         tabindex="-1"
         id="docs-search-modal"
         aria-labelledby="docs-search-title"
         aria-hidden="true">
        <div class="modal-dialog docs-search-dialog">
            <div class="modal-content docs-search-surface">
                <div class="modal-body">
                    <div class="docs-search-header">
                        <div>
                            <h2 class="docs-search-title" id="docs-search-title">Поиск по документации</h2>
                            <p class="docs-search-version">Laravel {{ $docs->version }}</p>
                        </div>
                        <button type="button"
                                class="btn-close docs-search-close"
                                data-bs-dismiss="modal"
                                aria-label="Закрыть поиск"></button>
                    </div>

                    <div data-controller="search-docs">
                        <form action="{{ route('docs.search', ['version' => $docs->version]) }}" method="post">
                            @csrf
                            <label for="docs-search-input" class="visually-hidden">Термин или фраза</label>
                            <div class="docs-search-field">
                                <x-icon path="i.search" width="1em" height="1em" aria-hidden="true" focusable="false" />
                                <input class="form-control"
                                       id="docs-search-input"
                                       data-action="input->search-docs#search"
                                       name="text"
                                       autocomplete="off"
                                       autofocus
                                       data-search-docs-target="text"
                                       type="search"
                                       placeholder="Введите термин или фразу"
                                >
                                <kbd aria-hidden="true">Esc</kbd>
                            </div>
                        </form>

                        @include('docs._search_lines', ['searchOffer' => [], 'query' => ''])
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
