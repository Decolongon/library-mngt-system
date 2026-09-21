<?php

use App\Models\Book;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('book_borrowers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(App\Models\User::class,'borrower_id')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Book::class)->constrained()->cascadeOnDelete();
            $table->date('borrow_at')->nullable();
            $table->date('return_at')->nullable();
            $table->timestamps();

            $table->unique(['borrower_id', 'book_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_borrowers');
    }
};
