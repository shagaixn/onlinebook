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
            $table->unsignedBigInteger('author_id')->nullable();
            $table->string('title');
            $table->string('category')->nullable();
            $table->string('cover_image')->nullable();
            $table->date('published_date')->nullable();
            $table->integer('price')->default(0);
            $table->integer('pages')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        if (Schema::hasTable('authors')) {
            Schema::table('books', function (Blueprint $table) {
                $table->foreign('author_id')->references('id')->on('authors')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
