@if($features->hasMorePages())
    <turbo-frame
        id="feature-more"
        loading="lazy"
        src="{{ $features->nextPageUrl() }}">

            @foreach(range(0,2) as $placeholder)
                <div class="col-12 mb-3 feature-placeholder">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="row align-items-start">
                                <div class="col-auto text-center" style="width: 80px;">
                                    <span class="placeholder rounded" style="width: 40px; height: 40px;"></span>
                                    <span class="placeholder rounded d-block mt-2" style="width: 30px;"></span>
                                </div>

                                <div class="col">
                                    <span class="placeholder rounded col-4 mb-2 d-block"></span>
                                    <span class="placeholder rounded col-12 mb-1 d-block"></span>
                                    <span class="placeholder rounded col-10 mb-1 d-block"></span>
                                    <span class="placeholder rounded col-8 mb-2 d-block"></span>
                                    <span class="placeholder rounded col-5 d-block"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
    </turbo-frame>
@endif