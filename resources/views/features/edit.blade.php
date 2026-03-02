@extends('layout')

@section('content')
    <div class="container my-5">
        <div class="row">
            <div class="bg-body-tertiary p-4 p-xl-5 rounded">
                <div class="col-xxl-8 mx-auto">
                    <form method="post" action="{{ route('feature.store') }}" data-controller="post-preview" data-post-preview-target="form">
                        <div
                            data-controller="tabs"
                            data-tabs-active-tab-class="bg-body-secondary"
                            data-tabs-index-value="0"
                        >

                            <div class="row gy-3">
                                <div class="col-12 col-md-7">
                                    <x-profile :user="auth()->user()" class="mb-3"/>
                                </div>
                            </div>


                            <div class="">
                                <div data-tabs-target="panel">
                                <textarea data-controller="textarea-autogrow"
                                          data-textarea-autogrow-resize-debounce-delay-value="500"
                                          id="title" name="title"
                                          maxlength="255"
                                          placeholder="Темная тема оформления"
                                          required
                                          rows="1"
                                          class="w-100 py-3 form-editor form-editor-title text-balance">{{ old('title', $feature->title) }}</textarea>
                                    <textarea data-controller="textarea-autogrow"
                                              data-textarea-autogrow-resize-debounce-delay-value="500"
                                              id="description" name="description"
                                              maxlength="5000"
                                              placeholder="Подробно опишите, что должна делать эта функция и почему она важна"
                                              required
                                              rows="1"
                                              class="w-100 py-3 fs-4 fw-normal form-editor form-editor-title text-balance">{{ old('title', $feature->content) }}</textarea>
                                </div>
                                <div class="d-none" data-tabs-target="panel">
                                    <main class="post" id="post-preview"></main>
                                </div>
                            </div>


                            <div
                                class="mt-3 gap-3 d-flex flex-column flex-md-row justify-content-center justify-content-md-start align-items-md-baseline">
                                <button
                                    formaction="{{ $feature->exists ? route('feature.update', $feature) : route('feature.store') }}"
                                    type="submit"
                                    data-turbo-confirm="Ваша публикация будет доступна широкой аудитории. Публикуйте ответственно."
                                    class="btn btn-primary mb-3 mb-md-0">
                                    {{ $feature->exists ? "Обновить" : "Опубликовать" }}
                                </button>

                                @if($feature->exists)
                                    <a class="justify-content-center justify-content-md-start btn btn-link ms-md-auto icon-link text-decoration-none"
                                       data-turbo-method="delete"
                                       data-turbo-confirm="Вы уверены, что хотите удалить публикацию?"
                                       href="{{ route('feature.delete', $feature)}}">
                                        <x-icon path="i.delete"/>
                                        Удалить
                                    </a>
                                @endif
                            </div>


                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

@endsection
