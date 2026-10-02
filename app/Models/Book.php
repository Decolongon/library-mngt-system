<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['title', 'author', 'isbn', 'total_copies', 'available_copies','category_id'])]
class Book extends Model
{
    public const COUNT_AVAILABLE_COPIES_CACHE_KEY = 'available_copies';
    public const COUNT_ALL_BOOKS_CACHE_KEY = 'count_all_books';
    public const COUNT_TOTAL_COPIES_CACHE_KEY = 'total_copies';

    public function borrowers(): HasMany
    {
        return $this->hasMany(BookBorrower::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
