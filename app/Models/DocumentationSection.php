<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Laravel\Scout\Searchable;

class DocumentationSection extends Model
{
    use HasFactory;
    use HasUuids;
    use Searchable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'title',
        'slug',
        'version',
        'file',
        'content',
    ];

    protected function shortContent(): Attribute
    {

        return Attribute::make(
            get: fn (mixed $value, array $attributes) =>

            Str::limit(
                nl2br(strip_tags($attributes['content'])),
                90
            ),
        );
    }

    protected function fileForUrl(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => str_replace('.md', '', $attributes['file']),
        );
    }

    public function toSearchableArray()
    {
        return [
            'title'   => $this->title,
            'content' => $this->content,
        ];
    }
}
