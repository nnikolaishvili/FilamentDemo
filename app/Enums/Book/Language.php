<?php

namespace App\Enums\Book;

enum Language: string
{
    case en = 'English';
    case ka = 'Georgian';
    case bg = 'Bulgarian';
    case hi = 'Hindi';
    case ja = 'Japanese';

    public static function getCases(): array
    {
        return collect(self::cases())->pluck('value', 'name')->toArray();
    }
}
