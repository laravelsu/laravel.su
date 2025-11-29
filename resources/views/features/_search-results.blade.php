<turbo-frame id="features-frame">
    @if($features->isEmpty())
        <div class="col-12">
            <div class="text-center py-5">
                <x-icon path="bs.inbox" class="text-muted" width="4rem" height="4rem" />
                <p class="lead text-muted mt-3">Ничего не найдено</p>
            </div>
        </div>
    @else
        @include('features._list')
    @endif
</turbo-frame>
