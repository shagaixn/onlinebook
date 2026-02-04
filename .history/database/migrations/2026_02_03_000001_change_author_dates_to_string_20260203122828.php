<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE authors MODIFY birth_date VARCHAR(255) NULL');
            DB::statement('ALTER TABLE authors MODIFY death_date VARCHAR(255) NULL');
            return;
        }

        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE authors ALTER COLUMN birth_date TYPE VARCHAR(255) USING NULLIF(birth_date::text, \'\')');
            DB::statement('ALTER TABLE authors ALTER COLUMN death_date TYPE VARCHAR(255) USING NULLIF(death_date::text, \'\')');
            return;
        }

        Schema::table('authors', function (Blueprint $table) {
            $table->string('birth_date_tmp')->nullable()->after('birth_place');
            $table->string('death_date_tmp')->nullable()->after('birth_date_tmp');
        });

        DB::table('authors')->update([
            'birth_date_tmp' => DB::raw('CASE WHEN birth_date IS NULL THEN NULL ELSE CAST(birth_date AS TEXT) END'),
            'death_date_tmp' => DB::raw('CASE WHEN death_date IS NULL THEN NULL ELSE CAST(death_date AS TEXT) END'),
        ]);

        Schema::table('authors', function (Blueprint $table) {
            $table->dropColumn(['birth_date', 'death_date']);
        });

        Schema::table('authors', function (Blueprint $table) {
            $table->renameColumn('birth_date_tmp', 'birth_date');
            $table->renameColumn('death_date_tmp', 'death_date');
        });
    }

    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE authors MODIFY birth_date DATE NULL');
            DB::statement('ALTER TABLE authors MODIFY death_date DATE NULL');
            return;
        }

        if ($driver === 'pgsql') {
            DB::statement("ALTER TABLE authors ALTER COLUMN birth_date TYPE DATE USING NULLIF(birth_date, '')::date");
            DB::statement("ALTER TABLE authors ALTER COLUMN death_date TYPE DATE USING NULLIF(death_date, '')::date");
            return;
        }

        Schema::table('authors', function (Blueprint $table) {
            $table->date('birth_date_date')->nullable()->after('birth_place');
            $table->date('death_date_date')->nullable()->after('birth_date_date');
        });

        DB::table('authors')->update([
            'birth_date_date' => DB::raw("CASE WHEN birth_date IN ('', '0') THEN NULL ELSE birth_date END"),
            'death_date_date' => DB::raw("CASE WHEN death_date IN ('', '0') THEN NULL ELSE death_date END"),
        ]);

        Schema::table('authors', function (Blueprint $table) {
            $table->dropColumn(['birth_date', 'death_date']);
        });

        Schema::table('authors', function (Blueprint $table) {
            $table->renameColumn('birth_date_date', 'birth_date');
            $table->renameColumn('death_date_date', 'death_date');
        });
    }
};
