<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('last_read_book_id')->nullable()->after('provider_id');
            $table->timestamp('last_read_at')->nullable()->after('last_read_book_id');

            $table->foreign('last_read_book_id')
                  ->references('id')->on('books')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['last_read_book_id']);
            $table->dropColumn(['last_read_book_id', 'last_read_at']);
        });
    }
};
