<?php

namespace App\Observers;

use App\Models\Book;
use Illuminate\Support\Facades\Cache;

class BookObserver
{
    /**
     * Handle the Book "created" event.
     */
    public function created(Book $book): void
    {
        $this->clearCache();
    }

    /**
     * Handle the Book "updated" event.
     */
    public function updated(Book $book): void
    {
        $this->clearCache();
    }

    /**
     * Handle the Book "deleted" event.
     */
    public function deleted(Book $book): void
    {
        $this->clearCache();
    }

    /**
     * Handle the Book "restored" event.
     */
    public function restored(Book $book): void
    {
        //
    }

    /**
     * Handle the Book "force deleted" event.
     */
    public function forceDeleted(Book $book): void
    {
        //
    }

    protected function clearCache(): void
    {
        Cache::forget(Book::COUNT_AVAILABLE_COPIES_CACHE_KEY);
        Cache::forget(Book::COUNT_ALL_BOOKS_CACHE_KEY);
        Cache::forget(Book::COUNT_TOTAL_COPIES_CACHE_KEY);
    }
}
