<?php

namespace App\Filament\Resources\Books\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class BooksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->formatStateUsing(fn (string $state): string => Str::ucfirst($state))
                    ->label('Title'),

                TextColumn::make('author')
                    ->label('Author')
                    ->formatStateUsing(fn (string $state): string => Str::title($state)),

                TextColumn::make('isbn')
                    ->label('ISBN'),

                TextColumn::make('total_copies')
                    ->badge()
                    ->color(fn ($state) => match (true) {
                        $state > 10 => 'success',
                        $state > 0 => 'warning',
                        default => 'danger',
                    })
                    ->numeric(decimalPlaces: 0),

                TextColumn::make('available_copies')
                    ->badge()
                    ->color(fn ($state) => match (true) {
                        $state > 10 => 'success',
                        $state > 0 => 'warning',
                        default => 'danger',
                    })
                    ->numeric(decimalPlaces: 0),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
