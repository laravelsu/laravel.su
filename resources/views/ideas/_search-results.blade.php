<turbo-frame id="ideas-frame">
    @if($ideas->isEmpty())
        <div class="col-12">
            <div class="text-center py-5">
                <x-icon path="bs.inbox" class="text-muted" width="4rem" height="4rem" />
                <p class="lead text-muted mt-3">Ничего не найдено</p>
            </div>
        </div>
    @else
        @include('ideas._list')
    @endif
</turbo-frame>
