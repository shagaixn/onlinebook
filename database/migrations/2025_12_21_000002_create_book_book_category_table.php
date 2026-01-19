<?php

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
        if (!Schema::hasTable('book_book_category')) {
            Schema::create('book_book_category', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('book_id');
                $table->unsignedBigInteger('book_category_id');
                $table->timestamps();

                $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade');
                $table->foreign('book_category_id')->references('id')->on('book_categories')->onDelete('cascade');
                
                // Prevent duplicate entries
                $table->unique(['book_id', 'book_category_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_book_category');
    }
};
