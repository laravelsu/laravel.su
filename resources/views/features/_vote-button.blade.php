<div id="feature-vote-{{ $feature->id }}">
    @if($feature->status->value === 'implemented')
        <div class="d-flex flex-column align-items-center">
            <button class="btn btn-sm btn-primary mb-1" disabled>
                <x-icon path="bs.check-circle-fill" width="1rem" height="1rem" />
            </button>
            <div class="fw-bold fs-5 votes-count">{{ $feature->votes_count }}</div>
            <small class="text-primary mt-1" style="font-size: 0.7rem;">Реализовано</small>
        </div>
    @else
        @auth
            @if(($feature->user_vote ?? 0) === 1)
                <div class="d-flex flex-column align-items-center">
                    <button class="btn btn-sm btn-success mb-1" disabled>
                        <x-icon path="bs.check-circle-fill" width="1rem" height="1rem" />
                    </button>
                    <div class="fw-bold fs-5 votes-count">{{ $feature->votes_count }}</div>
                    <small class="text-success mt-1" style="font-size: 0.7rem;">Вы проголосовали</small>
                </div>
            @else
                <form action="{{ route('features.vote', $feature) }}"
                      method="POST">
                    @csrf
                    <input type="hidden" name="vote" value="1">
                    <button type="submit" class="btn btn-sm btn-outline-secondary mb-1">
                        <x-icon path="bs.chevron-up" width="1rem" height="1rem" />
                    </button>
                </form>
                <div class="fw-bold fs-5 votes-count">{{ $feature->votes_count }}</div>
            @endif
        @else
            <button class="btn btn-sm btn-outline-secondary mb-1" disabled>
                <x-icon path="bs.chevron-up" width="1rem" height="1rem" />
            </button>
            <div class="fw-bold fs-5 votes-count">{{ $feature->votes_count }}</div>
        @endauth
    @endif
</div>