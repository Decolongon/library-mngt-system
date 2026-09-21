<?php

namespace App\Models;

use App\Models\BookBorrower;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['title', 'author', 'isbn', 'total_copies', 'available_copies'])]
class Book extends Model
{
    public function borrowers(): HasMany
    {
        return $this->hasMany(BookBorrower::class);
    }
}
