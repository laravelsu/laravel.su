<?php

namespace App\Models;

use App\Models\Concerns\HasAuthor;
use App\Models\Enums\IdeaStatusEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laravel\Scout\Searchable;
use Orchid\Filters\Filterable;
use Orchid\Filters\Types\Like;
use Orchid\Screen\AsSource;

class Idea extends Model
{
    use AsSource, Filterable, HasAuthor, HasFactory, Searchable;

    protected $fillable = [
        'title',
        'description',
        'status',
        'user_id',
        'votes_count',
    ];

    protected $casts = [
        'status'     => IdeaStatusEnum::class,
        'votes_count' => 'integer',
    ];

    protected $attributes = [
        'status'     => 'proposed',
        'votes_count' => 0,
    ];

    protected $allowedFilters = [
        'title'       => Like::class,
        'description' => Like::class,
    ];

    protected $allowedSorts = [
        'title',
        'votes_count',
        'created_at',
    ];

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', IdeaStatusEnum::Published);
    }

    public function isProposed(): bool
    {
        return $this->status === IdeaStatusEnum::Proposed;
    }

    public function isImplemented(): bool
    {
        return $this->status === IdeaStatusEnum::Implemented;
    }

    public function isRejected(): bool
    {
        return $this->status === IdeaStatusEnum::Rejected;
    }

    public function isPublished(): bool
    {
        return $this->status === IdeaStatusEnum::Published;
    }

    public function voters(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'idea_votes')
            ->withPivot('vote')
            ->withTimestamps();
    }

    public function hasVotedBy(?User $user): bool
    {
        if (! $user) {
            return false;
        }

        return $this->voters()->where('user_id', $user->id)->exists();
    }

    public function getUserVote(?User $user): ?int
    {
        if (! $user) {
            return null;
        }

        $vote = $this->voters()->where('user_id', $user->id)->first();

        return $vote?->pivot->vote;
    }

    public function toggleVote(User $user, int $voteValue = 1): void
    {
        $existingVote = $this->voters()->where('user_id', $user->id)->first();

        if ($existingVote) {
            return;
        }

        $this->voters()->attach($user->id, ['vote' => $voteValue]);
        $this->increment('votes_count', $voteValue);
    }

    public function toSearchableArray(): array
    {
        return [
            'title' => $this->title,
        ];
    }
}
