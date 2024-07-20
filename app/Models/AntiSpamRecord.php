<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AntiSpamRecord extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'telegram_id',
        'message_count',
    ];
}
