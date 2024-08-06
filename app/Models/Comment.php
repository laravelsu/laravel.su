<?php

namespace App\Models;

use App\Models\Concerns\Approvable;
use App\Models\Concerns\HasAuthor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use League\CommonMark\Extension\Mention\MentionExtension;
use Orchid\Metrics\Chartable;
use Overtrue\LaravelLike\Traits\Likeable;

class Comment extends Model
{
    use Approvable, Chartable, HasAuthor, Likeable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'post_id',
        'parent_id',
        'content',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'post_id'   => 'integer',
        'user_id'   => 'integer',
        'parent_id' => 'integer',
        'approved'  => 'boolean',
    ];

    /**
     * Get the post that owns the comment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'post_id');
    }

    /**
     * Get the replies to the comment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function replies(): HasMany
    {
        return $this->hasMany(static::class, 'parent_id');
    }

    /**
     * Get the parent comment of the comment.
     */
    public function parent()
    {
        return $this->belongsTo(static::class, 'parent_id');
    }

    /**
     * Verify if the current comment is a reply from another comment.
     *
     * @return bool
     */
    public function isReply(): bool
    {
        return $this->attributes['parent_id'] > 0;
    }

    /**
     * Verify if the current comment has replies.
     *
     * @return bool
     */
    public function hasReplies(): bool
    {
        return count($this->replies) > 0;
    }

    /**
     * Convert mentioned usernames to HTML links.
     *
     * @param string|null $text
     *
     * @return string
     */
    protected function mentionedUserToHtmlUrl(?string $text = null): string
    {
        return Str::of($text)
            ->replaceMatches('/\@([a-zA-Z0-9_]+)/u', function ($mention) {
                $href = route('profile', $mention[1]);
                $name = Str::of($mention[0])->trim();

                return "<a href='$href' class='text-decoration-none'>$name</a>";
            });
    }

    /**
     * Get the pretty formatted comment content.
     *
     * @return string
     */
    public function prettyComment(): string
    {
        return Str::of($this->content ?? '')->markdown(
            [
                'html_input' => 'escape',
                'mentions'   => [
                    'github_handle' => [
                        'prefix'    => '@',
                        'pattern'   => '[a-z\d](?:[a-z\d]|-(?=[a-z\d])){0,38}(?!\w)',
                        'generator' => route('profile', '%s'),
                    ],
                ],
            ],
            [
                new MentionExtension,
            ]
        );
    }

    /**
     * Get the users mentioned in the comment content.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    public function getMentionedUsers()
    {
        $usersNikNames = Str::of($this->content)->matchAll('/\@([a-zA-Z0-9_]+)/u');
        if ($usersNikNames->isEmpty()) {
            return collect();
        }

        return User::whereIn('nickname', $usersNikNames)->get();
    }
}
