<?php

namespace App\Filament\Resources\BookBorrowers\Pages;

use App\Filament\Resources\BookBorrowers\BookBorrowerResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;

class ListBookBorrowers extends ListRecords
{
    protected static string $resource = BookBorrowerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Create Book Borrower')
                ->icon(Heroicon::OutlinedPlusCircle),
        ];
    }
}
