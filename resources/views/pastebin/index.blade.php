@extends('layout')
@section('title', empty($content) ? 'Pastebin' : \Illuminate\Support\Str::of($content)->limit(30). ' | '. 'Pastebin')
@section('cover', 'Поделитесь своим кодом с кем угодно')
@section('description', 'Делитесь вашим кодом онлайн с кем угодно на земле.')

@section('content')

    <x-container>

        <form action="{{ route('pastebin.store') }}" method="post">
            <div data-controller="prism" class="rounded overflow-hidden">
            <pre contenteditable="true"
                 class="rounded line-numbers border p-4 m-0"
                 style="min-height: 600px;"
                 data-action="input->prism#paste keydown->prism#keydownPaste"
                 data-prism-target="editable"><code id="yaml"
                                                    placeholder="Поделитесь своим фрагментом кода тут!">{{ $content }}</code></pre>

                <input name="code" data-prism-target="output" type="hidden" required value="{{ $content }}">
            </div>
            <div
                class="mt-3 d-flex flex-column flex-md-row justify-content-center justify-content-md-start align-items-md-baseline">
                <button type="submit" class="btn btn-primary mb-3 mb-md-0">
                    {{ $content ? "Обновить" : "Сохранить" }}
                </button>

                <a href="{{ route('pastebin') }}" type="submit"
                   class="btn btn-link text-decoration-none link-secondary mb-3 mb-md-0">
                    Новый фрагмент
                </a>

                <div class="d-flex ms-md-auto align-items-baseline justify-content-center justify-content-md-end clipboard" data-controller="clipboard"
                     data-clipboard-done-class="done">
                    <small class="user-select-all me-2 col-6 col-md-auto text-truncate lh-1 w-auto"
                           data-clipboard-target="source">{{ url()->current() }}</small>
                    <a href="#"
                       data-action="clipboard#copy">
                        <x-icon path="i.copy" class="copy-action" data-controller="tooltip"
                                title="Скопировать в буфер"/>
                        <x-icon path="i.copy-fill" class="copy-done" data-controller="tooltip" title="Скопировано"/>
                    </a>
                </div>
            </div>
        </form>
    </x-container>

    @include('particles.positions')

    <x-call-to-action link="{{ route('library') }}" text="Перейти в библиотеку">
        <x-slot:title>Откройте дверь к мастерству</x-slot>

        <x-slot:description>
            В нашей библиотеке вы обнаружите сокровища знаний: чистый код, стратегии безопасности, методы оптимизации и
            многое другое. Углубитесь в эти ресурсы, чтобы стать выдающимся разработчиком.
        </x-slot>
    </x-call-to-action>

@endsection
