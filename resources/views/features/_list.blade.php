@foreach($features as $feature)
    <div class="col-12 mb-3"
         data-feature-search-target="item"
         data-title="{{ strtolower($feature->title) }}">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="row align-items-start">
                    <div class="col-auto text-center" style="width: 80px;">
                        @include('features._vote-button', ['feature' => $feature])
                    </div>

                    <div class="col">
                        <div class="d-flex align-items-start mb-2">
                            @if($feature->icon)
                                <x-icon :path="$feature->icon" class="me-2" width="1.5rem" height="1.5rem" />
                            @endif
                            <div class="w-100">
                                <h5 class="card-title mb-2">
                                    {{ $feature->title }}
                                    @if($feature->isProposed())
                                        <span class="badge bg-warning text-dark ms-2" style="font-size: 0.65rem;">{{ $feature->status->text() }}</span>
                                    @endif
                                </h5>
                                <p class="card-text text-muted mb-2">{{ $feature->description }}</p>
                                <small class="text-muted d-block" style="font-size: 0.8rem;">
                                    Предложил <x-profile :user="$feature->author" size="sm" class="d-inline"/>
                                    • {{ $feature->created_at->diffForHumans() }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach
