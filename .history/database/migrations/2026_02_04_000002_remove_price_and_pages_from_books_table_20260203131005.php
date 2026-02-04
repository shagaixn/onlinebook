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
        if (! Schema::hasTable('books')) {
            return;
        }

        $columnsToDrop = [];

        if (Schema::hasColumn('books', 'price')) {
            $columnsToDrop[] = 'price';
        }

        if (Schema::hasColumn('books', 'pages')) {
            $columnsToDrop[] = 'pages';
        }

        if (empty($columnsToDrop)) {
            return;
        }

        Schema::table('books', function (Blueprint $table) use ($columnsToDrop) {
            $table->dropColumn($columnsToDrop);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('books')) {
            return;
        }

        $columnsToAdd = [
            'price' => ! Schema::hasColumn('books', 'price'),
            'pages' => ! Schema::hasColumn('books', 'pages'),
        ];

        if (! $columnsToAdd['price'] && ! $columnsToAdd['pages']) {
            return;
        }

        Schema::table('books', function (Blueprint $table) use ($columnsToAdd) {
            if ($columnsToAdd['price']) {
                $table->integer('price')->default(0)->after('published_date');
            }

            if ($columnsToAdd['pages']) {
                $table->integer('pages')->nullable()->after('price');
            }
        });
    }
};
