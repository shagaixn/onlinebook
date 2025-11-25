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
        Schema::table('authors', function (Blueprint $table) {
            $table->string('country')->nullable()->after('nationality'); // Улс
            $table->string('birth_place')->nullable()->after('country'); // Төрсөн газар
            $table->string('position')->nullable()->after('birth_place'); // Албан тушаал
            $table->string('email')->nullable()->after('position'); // Имэйл хаяг
            $table->text('social_links')->nullable()->after('email'); // Сошиал линкүүд (JSON)
            $table->text('notable_works')->nullable()->after('awards'); // Алдартай бүтээлүүд
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('authors', function (Blueprint $table) {
            $table->dropColumn(['country', 'birth_place', 'position', 'email', 'social_links', 'notable_works']);
        });
    }
};
