<?php

namespace App\Filament\Widgets;

use App\Models\Book;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Cache;

class BookStatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Available Books', self::countAvailableCopies())
                ->description('Number of books currently available for borrowing.')
                 ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),
            
            Stat::make('Total Books', self::countAllBooks())
                ->description('Count all books')
                 ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('info'),

            Stat::make('Total Copies', self::countAllCopies())
                ->description('Total Copies of Books')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('warning')

        ];
    }

    protected static function countAvailableCopies(): int
    {
        return Cache::remember(Book::COUNT_AVAILABLE_COPIES_CACHE_KEY, now()->addMinutes(5), function () {
            return Book::query()->where('available_copies', '>', 0)->count();
        });
    }

    protected static function countAllBooks(): int
    {
        return Cache::remember(Book::COUNT_ALL_BOOKS_CACHE_KEY, now()->addMinutes(5), function () {
            return Book::query()->count();
        });
    }

    protected static function countAllCopies(): int
    {
         return Cache::remember(Book:: COUNT_TOTAL_COPIES_CACHE_KEY, now()->addMinutes(5), function () {
            return Book::query()->sum('total_copies');
        });
    } 
}
