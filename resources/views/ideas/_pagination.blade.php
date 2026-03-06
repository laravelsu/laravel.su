@if($ideas->hasMorePages())
    <turbo-frame
        id="idea-more"
        loading="lazy"
        src="{{ $ideas->nextPageUrl() }}">

        @foreach(range(0, 5) as $placeholder)
            <div class="bg-body-tertiary mb-4 p-xl-5 p-4 rounded post-placeholder idea-placeholder">

                <span class="placeholder rounded col-6 mb-4"></span>

                <span class="placeholder rounded col-7"></span>
                <span class="placeholder rounded col-4"></span>
                <span class="placeholder rounded col-4"></span>
                <span class="placeholder rounded col-6"></span>
                <span class="placeholder rounded col-8"></span>

                <div class="d-flex mt-4">
                    <span class="placeholder rounded col-2 me-2"></span>
                    <span class="placeholder rounded col-2 me-2"></span>
                    <span class="placeholder rounded col-2 me-2"></span>
                    <span class="placeholder rounded col-2 ms-auto"></span>
                </div>
            </div>
        @endforeach
    </turbo-frame>
@endif
