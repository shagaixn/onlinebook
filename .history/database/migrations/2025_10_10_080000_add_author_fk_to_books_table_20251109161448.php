<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('books')) {
            return;
        }

        if (!Schema::hasColumn('books', 'author')) {
            Schema::table('books', function (Blueprint $table) {
                // author_id already exists; this is a human-readable fallback/name column
                $table->string('author')->nullable()->after('author_id')->comment('Author name snapshot for display');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('books') && Schema::hasColumn('books', 'author')) {
            Schema::table('books', function (Blueprint $table) {
                $table->dropColumn('author');
            });
        }
    }
};