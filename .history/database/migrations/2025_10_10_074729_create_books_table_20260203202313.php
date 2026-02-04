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
        // Хэрэв хүснэгт аль хэдийн байгаа бол дахин үүсгэх оролдлогоос гарна.
        if (Schema::hasTable('books')) {
            return;
        }

        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('author_id')->nullable();
            $table->string('title');
            $table->string('category')->nullable();
            $table->string('cover_image')->nullable();
            $table->date('published_date')->nullable();
            $table->date('first_published_date')->nullable(); // Анх хэвлэгдсэн огноо
            $table->date('author_birth_date')->nullable(); // Зохиогчийн төрсөн огноо  
            $table->date('author_death_date')->nullable(); // Зохиогчийн нас барсан огноо
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
