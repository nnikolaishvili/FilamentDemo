<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookDetail extends Model
{
    protected $fillable = [
        'book_id',
        'description',
        'pages_count',
        'language'
    ];

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }
}
