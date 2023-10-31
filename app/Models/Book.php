<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use SoftDeletes;

    protected $casts = [
        'tags' => 'array'
    ];

    protected $fillable = [
        'name',
        'genre_id',
        'publisher_id',
        'publication_date',
        'thumbnail',
        'tags',
        'price',
        'is_best_seller'
    ];

    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(Author::class, 'author_book')->withPivot('role')->withTimestamps();
    }

    public function bookDetail(): HasOne
    {
        return $this->hasOne(BookDetail::class);
    }

    public function publisher(): BelongsTo
    {
        return $this->belongsTo(Publisher::class);
    }

    public function genre(): BelongsTo
    {
        return $this->belongsTo(Genre::class);
    }

    public function reviews(): MorphMany
    {
        return $this->morphMany(Review::class, 'reviewable');
    }
}
