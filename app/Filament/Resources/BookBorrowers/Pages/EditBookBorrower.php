<?php

namespace App\Filament\Resources\BookBorrowers\Pages;

use App\Filament\Resources\BookBorrowers\BookBorrowerResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditBookBorrower extends EditRecord
{
    protected static string $resource = BookBorrowerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('book borrower updated')
            ->body('The book borrower has been saved successfully.');
    }
}
