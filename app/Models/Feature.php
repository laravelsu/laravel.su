<?php

namespace App\Models;

use App\Models\Concerns\HasAuthor;
use App\Models\Enums\FeatureStatusEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laravel\Scout\Searchable;
use Orchid\Filters\Filterable;
use Orchid\Filters\Types\Like;
use Orchid\Screen\AsSource;

class Feature extends Model
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
        'status'      => FeatureStatusEnum::class,
        'votes_count' => 'integer',
    ];

    protected $attributes = [
        'status'      => 'proposed',
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

    /**
     * Get only published features.
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', FeatureStatusEnum::Published);
    }

    public function isProposed(): bool
    {
        return $this->status === FeatureStatusEnum::Proposed;
    }

    public function isImplemented(): bool
    {
        return $this->status === FeatureStatusEnum::Implemented;
    }

    public function isRejected(): bool
    {
        return $this->status === FeatureStatusEnum::Rejected;
    }

    public function isPublished(): bool
    {
        return $this->status === FeatureStatusEnum::Published;
    }

    /**
     * Get voters who voted for this feature.
     */
    public function voters(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'feature_votes')
            ->withPivot('vote')
            ->withTimestamps();
    }

    /**
     * Check if the user has voted for this feature.
     */
    public function hasVotedBy(?User $user): bool
    {
        if (! $user) {
            return false;
        }

        return $this->voters()->where('user_id', $user->id)->exists();
    }

    /**
     * Get the user's vote for this feature (1 or -1).
     */
    public function getUserVote(?User $user): ?int
    {
        if (! $user) {
            return null;
        }

        $vote = $this->voters()->where('user_id', $user->id)->first();

        return $vote?->pivot->vote;
    }

    /**
     * Vote for this feature (one-time only).
     */
    public function toggleVote(User $user, int $voteValue = 1): void
    {
        // Check if user has already voted
        $existingVote = $this->voters()->where('user_id', $user->id)->first();

        // If user already voted, do nothing (no cancellation allowed)
        if ($existingVote) {
            return;
        }

        // Add new vote (only upvotes allowed, voteValue should be 1)
        $this->voters()->attach($user->id, ['vote' => $voteValue]);
        $this->increment('votes_count', $voteValue);
    }

    /**
     * Get the indexable data array for the model.
     */
    public function toSearchableArray(): array
    {
        return [
            'title'       => $this->title,
        ];
    }
}
