<?php

namespace App\Filament\Resources\BookBorrowers\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class BookBorrowerInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('borrower.name')
                    ->label('Borrower'),
                TextEntry::make('book.title')
                    ->label('Book'),
                TextEntry::make('borrow_at')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('return_at')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
