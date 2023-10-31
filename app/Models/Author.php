<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Author extends Model
{
    protected $fillable = [
        'name',
        'phone_number',
        'email'
    ];

    public function books(): BelongsToMany
    {
        return $this->belongsToMany(Book::class, 'author_book')->withPivot('role')->withTimestamps();
    }
}
