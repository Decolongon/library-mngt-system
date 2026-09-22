<?php

namespace App\Filament\Resources\BookBorrowers;

use App\Filament\Resources\BookBorrowers\Pages\CreateBookBorrower;
use App\Filament\Resources\BookBorrowers\Pages\EditBookBorrower;
use App\Filament\Resources\BookBorrowers\Pages\ListBookBorrowers;
use App\Filament\Resources\BookBorrowers\Pages\ViewBookBorrower;
use App\Filament\Resources\BookBorrowers\Schemas\BookBorrowerForm;
use App\Filament\Resources\BookBorrowers\Schemas\BookBorrowerInfolist;
use App\Filament\Resources\BookBorrowers\Tables\BookBorrowersTable;
use App\Models\BookBorrower;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BookBorrowerResource extends Resource
{
    protected static ?string $model = BookBorrower::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookmarkSquare;

    protected static ?string $recordTitleAttribute = 'book_id';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return BookBorrowerForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return BookBorrowerInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BookBorrowersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBookBorrowers::route('/'),
            'create' => CreateBookBorrower::route('/create'),
            'view' => ViewBookBorrower::route('/{record}'),
            'edit' => EditBookBorrower::route('/{record}/edit'),
        ];
    }
}
