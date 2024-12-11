<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SecretSantaParticipant extends Model
{
    use SoftDeletes, HasUuids;

    protected $fillable = [
        'address',
        'about',
        'tracking_number',
        'telegram',
        'phone',
    ];

    /**
     * @var string[]
     */
    protected $with = [
        'receiver'
    ];

    // Связь с пользователем
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Связь с получателем
    public function receiver()
    {
        return $this
            ->belongsTo(SecretSantaParticipant::class, 'receiver_id', 'user_id')
            ->without('receiver');
    }

    /**
     * @return bool
     */
    public function hasReceiver(): bool
    {
        return $this->receiver_id !== null;
    }
}
