<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class SecretSantaParticipant extends Model
{
    use AsSource, Filterable, HasUuids, SoftDeletes;

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
        'receiver',
        'santa',
    ];

    protected $allowedSorts = [
        'status',
        'tracking_number',
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
            ->without('receiver', 'santa');
    }

    // Связь с Сантой (кто отправляет мне подарок)
    public function santa()
    {
        return $this
            ->hasOne(SecretSantaParticipant::class, 'receiver_id', 'user_id')
            ->without('receiver', 'santa');
    }

    /**
     * @return bool
     */
    public function hasReceiver(): bool
    {
        return $this->receiver_id !== null;
    }

    /**
     * Проверяет, есть ли у пользователя Санта
     *
     * @return bool
     */
    public function hasSanta(): bool
    {
        return $this->santa !== null;
    }
}
