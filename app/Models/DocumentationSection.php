<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class DocumentationSection extends Model
{
    use HasFactory, HasUuids, Searchable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'title',
        'title_page',
        'slug',
        'version',
        'file',
        'content',
        'level',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function fileForUrl(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => str_replace('.md', '', $attributes['file']),
        );
    }

    /**
     * @return array
     */
    public function toSearchableArray()
    {
        return [
            'title'   => $this->title,
            'content' => $this->content,
            'level'   => $this->level,
        ];
    }
}
