@foreach($ideas as $idea)
    <div id="@domid($idea)"
         data-idea-search-target="item"
         data-title="{{ strtolower($idea->title) }}"
         class="bg-body-tertiary mb-4 p-4 p-xl-5 ps-3 ps-xl-4 rounded hotwire-frame">

        <div class="d-flex gap-3 align-items-start">
            <div class="d-inline-flex">
                @include('ideas._vote-button', ['idea' => $idea])
            </div>

            <div class="d-flex align-items-start gap-3 flex-column justify-content-between">

                <x-profile :user="$idea->author" />

                <div class="position-relative post overflow-hidden">
                    <h4 class="mb-3 mt-2">
                        {{ $idea->title }}
                        @if($idea->isProposed())
                            <small class="badge bg-warning text-dark ms-2">
                                {{ $idea->status->text() }}
                            </small>
                        @endif
                    </h4>

                    <div class="post" data-controller="prism">
                        <x-posts.content :content="$idea->description" />
                    </div>
                </div>
            </div>

        </div>
    </div>
@endforeach
