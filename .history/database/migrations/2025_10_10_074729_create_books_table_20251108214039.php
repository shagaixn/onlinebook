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
        Schema::create('books', function (Blueprint $table) {
        $table->id();
        $table->string('title');
          $table->foreignId('author_id')->constrained('authors')->onDelete('cascade');
        $table->string('category')->nullable();
        $table->string('cover_image')->nullable();
        $table->date('published_date')->nullable();
        $table->integer('price')->default(0);
        $table->integer('pages')->nullable();
        $table->text('description')->nullable();
        $table->timestamps();

        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
