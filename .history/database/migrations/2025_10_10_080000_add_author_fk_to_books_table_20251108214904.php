<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Хэрэв хүснэгтүүд байгаа бол гадаад түлхүүр нэмэх оролдлого хийнэ.
        if (Schema::hasTable('books') && Schema::hasTable('authors')) {
            // Зарим DB драйверт constraint нэр өөр байж болох тул try/catch ашиглана.
            try {
                Schema::table('books', function (Blueprint $table) {
                    $table->foreign('author_id')->references('id')->on('authors')->onDelete('cascade');
                });
            } catch (\Throwable $e) {
                // Алдаа гарвал лог бичих эсвэл санаа амраах - ноух
                // ...existing code...
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('books')) {
            try {
                Schema::table('books', function (Blueprint $table) {
                    $table->dropForeign(['author_id']);
                });
            } catch (\Throwable $e) {
                // ...existing code...
            }
        }
    }
};