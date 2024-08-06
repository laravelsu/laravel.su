<?php

namespace App\Models;

use App\Models\Concerns\HasAuthor;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Orchid\Metrics\Chartable;

class IdeaKey extends Model
{
    use Chartable, HasAuthor, HasUuids;

    /**
     * Get the idea request associated with the idea key.
     */
    public function request(): BelongsTo
    {
        return $this->belongsTo(IdeaRequest::class, 'request_id');
    }
}
