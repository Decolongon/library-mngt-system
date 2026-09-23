<?php

namespace App\Filament\Resources\BookBorrowers\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class BookBorrowerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('borrower_id')
                    ->relationship('borrower', 'name')
                    ->label('Borrower')
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('book_id')
                    ->relationship('book', 'title')
                    ->label('Book')
                    ->searchable()
                    ->preload()
                    ->required(),

                DatePicker::make('borrow_at')
                    ->minDate(now()->startOfDay())
                    ->default(now())
                    ->prefix('Date borrowed: ')
                    ->native(false)
                    ->label('Borrows at')
                    ->required(),

                DatePicker::make('return_at')
                 ->minDate(now()->startOfDay())
                 ->prefix('Returns at: ')
                 ->native(false)
                ->required()
                ->label('When you gonna return this book?'),
            ]);
    }
}
