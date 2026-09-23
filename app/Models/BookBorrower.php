<?php

namespace App\Models;

use App\Policies\BookBorrowerPolicy;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['book_id', 'borrower_id', 'return_at', 'borrow_at'])]
#[UsePolicy(BookBorrowerPolicy::class)]
class BookBorrower extends Model
{
    protected function casts(): array
    {
        return [
            'return_at' => 'date',
            'borrow_at' => 'date',
        ];
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function borrower(): BelongsTo
    {
        return $this->belongsTo(User::class, 'borrower_id');
    }
}
