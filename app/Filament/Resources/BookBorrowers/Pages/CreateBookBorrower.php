<?php

namespace App\Filament\Resources\BookBorrowers\Pages;

use App\Filament\Resources\BookBorrowers\BookBorrowerResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateBookBorrower extends CreateRecord
{
    protected static string $resource = BookBorrowerResource::class;

     protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Book Borrower created')
            ->body('Book Borrower has been created successfully.');
    }
}
