<ul class="list-group" id="found_candidates">
    @foreach($searchOffer as $offer)
{{--        <li class="list-group-item fs-6 text fw-bold" style="font-size: 14px !important;">{{ $offer->title_page }}</li>--}}

                <a href="{{ route('docs', ['version' => $offer->version, 'page' => "{$offer->file_for_url}#{$offer->slug}" ]) }}" class="list-group-item list-group-item-action" aria-current="true">
                        <div class="d-flex w-100 justify-content-between">
                                <p class="mb-1 fw-bold font-monospace">{{ $offer->title_page }}</p>
{{--                                <small>3 days ago</small>--}}
                        </div>
{{--                        @dd(Str::of($offer->content)->limit(20)->__toString())--}}
                        <p class="mb-1">{{ $offer->title }}</p>
                        <small>{!! $offer->short_content !!}</small>

                </a>
    @endforeach
</ul>

