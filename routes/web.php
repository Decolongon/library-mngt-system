<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified', 'role:book_borrower'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    Route::livewire('books', 'pages::book.book')->name('books');
});

require __DIR__.'/settings.php';
