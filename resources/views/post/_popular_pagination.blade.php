<div id="popular-more">
    @if($popular->hasMorePages())
        <a href="{{ $popular->nextPageUrl()}}"
           data-turbo-method="post"
           class="link-body-emphasis text-decoration-none fw-semibold">Показать ещё</a>
    @endif
</div>
