<?php

namespace App\Filament\Resources\Books\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

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
            Select::make('category_id')
                ->relationship(name: 'category', titleAttribute: 'name')
                ->preload()
                ->searchable()
                ->createOptionForm([
                    TextInput::make('name')
                        ->live(onBlur: true)
                        ->unique(ignoreRecord: true)
                        ->afterStateUpdated(fn ($state, $set) => $set('slug', Str::slug($state)))
                        ->required(),

                    TextInput::make('slug')
                        ->dehydrated()
                        ->unique(ignoreRecord: true)
                        ->disabled()
                        ->required(),
                ])
                ->loadingMessage('Loading categories...')
                ->required(),

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
                ->maxValue(fn ($get) => intval($get('total_copies')))
                ->required(),
        ];
    }
}
