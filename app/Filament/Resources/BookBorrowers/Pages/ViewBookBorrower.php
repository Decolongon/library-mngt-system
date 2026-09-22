<?php

namespace App\Filament\Resources\BookBorrowers\Pages;

use App\Filament\Resources\BookBorrowers\BookBorrowerResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewBookBorrower extends ViewRecord
{
    protected static string $resource = BookBorrowerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
