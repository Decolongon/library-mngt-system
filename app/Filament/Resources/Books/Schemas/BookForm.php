<?php

namespace App\Filament\Resources\Books\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class BookForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components(
                self::bookForm()
            );
    }

    protected static function bookForm(): array
    {
        return [
            TextInput::make('title')
                ->label('Book Title')
                ->required(),

            TextInput::make('isbn')
                ->label('ISBN')
                ->numeric()
                ->unique(ignoreRecord: true)
                ->required(),

            TextInput::make('author')
                ->label('Author')
                ->required(),

            TextInput::make('total_copies')
                ->label('How many Copies?')
                ->numeric()
                ->minValue(1)
                ->live(onBlur: true)
                ->required(),
            
             TextInput::make('available_copies')
                ->label('How many Copies you want to be borrowed?')
                ->numeric()
                ->minValue(1)
                ->maxValue(fn($get) => intval($get('total_copies')))
                ->required(),
        ];
    }
}
