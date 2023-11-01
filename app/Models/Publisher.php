<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Publisher extends Model
{
    protected $fillable = [
        'name',
        'logo_url',
        'location',
        'is_certified'
    ];

    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }

    public function reviews(): MorphMany
    {
        return $this->morphMany(Review::class, 'reviewable');
    }
}
