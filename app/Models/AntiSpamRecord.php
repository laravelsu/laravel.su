<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class AntiSpamRecord extends Model
{
    use HasUuids;

    protected $fillable = [
        'telegram_id',
        'message_count',
    ];
}
