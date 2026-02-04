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
        Schema::table('books', function (Blueprint $table) {
            $table->date('first_published_date')->nullable()->after('published_date'); // Анх хэвлэгдсэн огноо
            $table->date('birth_date')->nullable()->after('first_published_date'); // Зохиогчийн төрсөн огноо  
            $table->date('death_date')->nullable()->after('birth_date'); // Зохиогчийн нас барсан огноо
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn(['first_published_date', 'author_birth_date', 'author_death_date']);
        });
    }
};
