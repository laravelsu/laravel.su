@foreach($features as $feature)
    <div id="@domid($feature)"
         data-feature-search-target="item"
         data-title="{{ strtolower($feature->title) }}"
         class="bg-body-tertiary mb-4 p-4 p-xl-5 ps-3 ps-xl-4 rounded hotwire-frame">

        <div class="d-flex gap-3 align-items-start">
            <div class="d-inline-flex">
                @include('features._vote-button', ['feature' => $feature])
            </div>

            <div class="d-flex align-items-start gap-3 flex-column justify-content-between">

                <x-profile :user="$feature->author" />

                <div class="position-relative post overflow-hidden">
                    <h4 class="mb-3 mt-2">
                        {{ $feature->title }}
                        @if($feature->isProposed())
                            <small class="badge bg-warning text-dark ms-2">
                                {{ $feature->status->text() }}
                            </small>
                        @endif
                    </h4>

                    <div class="post" data-controller="prism">
                        <x-posts.content :content="$feature->description" />
                    </div>
                </div>
            </div>

        </div>
    </div>
@endforeach
