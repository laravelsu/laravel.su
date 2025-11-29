<div id="feature-vote-{{ $feature->id }}" class="d-flex flex-column gap-1 align-items-center">
    @if($feature->status->value === 'implemented')
        <div class="d-flex flex-column align-items-center" title="Эта функция была реализована">
            <button class="btn btn-sm btn-primary mb-1" disabled>
                <x-icon path="bs.check-circle-fill" width="1rem" height="1rem" />
            </button>
            <div class="fw-bold fs-5 votes-count">{{ $feature->votes_count }}</div>
        </div>
    @else
        @auth
            @if(($feature->user_vote ?? 0) === 1)
                <div class="d-flex flex-column align-items-center" title="Вы проголосовали за эту функцию">
                    <button class="btn btn-sm btn-success mb-1" disabled>
                        <x-icon path="bs.check-circle-fill" width="1rem" height="1rem" />
                    </button>
                    <div class="fw-bold fs-5 votes-count">{{ $feature->votes_count }}</div>
                </div>
            @else
                <form action="{{ route('features.vote', $feature) }}" method="POST">
                    @csrf
                    <input type="hidden" name="vote" value="1">
                    <button type="submit" class="btn btn-sm btn-link text-success-emphasis">
                        <x-icon path="bs.caret-up-fill" width="1rem" height="1rem" />
                    </button>
                </form>
                <div class="fw-bold votes-count">{{ $feature->votes_count }}</div>
            @endif
        @else
            <button class="btn btn-sm btn-link text-success-emphasis" type="button" disabled>
                <x-icon path="bs.caret-up-fill" width="1rem" height="1rem" />
            </button>
            <div class="fw-bold votes-count">{{ $feature->votes_count }}</div>
        @endauth
    @endif
</div>
