@extends('layout')
@section('title', $docs->title(). " Laravel ". $docs->version)
@section('description', $docs->description())

@push('head')
    <link rel="canonical" href="{{ route('docs', ['version'=> \App\Docs::DEFAULT_VERSION, 'page'=> $docs->name]) }}">
@endpush

@section('content')

    <div class="container container-docs my-4 my-xxl-5 mx-auto">
        <div class="row gap-2 justify-content-center align-items-start position-relative mb-5">
            <div class="col-3 col-xl-3 col-xxl-2 order-md-first order-last position-sticky top-0 py-md-3 z-1 d-none d-lg-block doc-navigation">
                <div class="mb-md-4 ms-md-4 d-flex align-items-stretch flex-column offcanvas-md offcanvas-start" id="docs-menu">

                    <div class="position-relative">
                        <input class="form-control" type="text" placeholder="Поиск...">
                        <a href="#" data-bs-toggle="modal" data-bs-target="#docs-search-modal" class="stretched-link"></a>
                    </div>

                    <ul class="list-unstyled p-4 px-md-0">
                        @foreach ($docs->getMenu() as $item)
                            <li class="mb-2">
                                <button
                                    class="btn btn-toggle d-flex align-items-center rounded border-0 collapsed text-body-secondary p-0 w-100 text-start"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#{{ \Illuminate\Support\Str::slug($item['title']) }}-collapse"
                                    aria-expanded="true">
                                    {{ $item['title'] }}
                                </button>

                                <div class="collapse {{ active(collect($item['list'])->map(fn($link) => $link['href']), 'show') }} mt-2"
                                    id="{{ \Illuminate\Support\Str::slug($item['title']) }}-collapse"
                                    data-bs-parent="#docs-menu">
                                    <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small ms-2">
                                        @foreach ($item['list'] as $link)
                                            <li>
                                                <a href="{{ $link['href'] }}"
                                                    class="{{ active(url($link['href']), 'active', 'link-body-emphasis') }} d-inline-flex text-decoration-none rounded p-2">
                                                    {{ $link['title'] }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </li>
                        @endforeach
                    </ul>


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
            </div>
            <div class="px-0 px-md-2 px-xl-3 col-md-10 col-lg-8 col-xl-8 col-xxl-6 order-md-1 order-first">

                <div class="bg-body-tertiary p-4 p-xl-5 rounded">
                    <main class="px-md-4 px-xl-5 documentations position-relative" data-controller="prism">
                        <h1 class="display-6 fw-bold text-body-emphasis">{{ $docs->title() }}</h1>
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

                        <div class="d-block d-xxl-none mt-3">
                            <x-docs.anchors :content="$content"/>
                        </div>
                        <x-docs.content :content="$content"/>
                    </main>
                </div>
            </div>
            <div class="col-3 col-xl-2 order-last position-sticky top-0 py-md-3 z-1 d-none d-xxl-block doc-navigation">
                <div class="mb-md-4 d-flex align-items-stretch flex-column offcanvas-md offcanvas-start" id="docs-menu">
                    <main>
                        <x-docs.anchors :content="$content"/>
                    </main>
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-sheet fade" tabindex="-1" role="dialog" id="docs-search-modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content rounded-4 shadow">
                <div class="modal-body p-5">
                    <p class="h4 fw-bold mb-4 text-balance">Поиск по документации</p>

                    <div data-controller="search-docs">
                        <form action="{{ route('docs.search', ['version' => $docs->version]) }}" method="post">
                            @csrf
                            <input class="form-control form-control-md"
                                   data-action="input->search-docs#search"
                                   name="text"
                                   autocomplete="off"
                                   autofocus
                                   data-search-docs-target="text"
                                   type="search"
                                   placeholder="Введите термин или фразу ..."
                            >
                        </form>

                        @include('docs._search_lines', ['searchOffer' => []])
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
