<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAuthorFieldsToUsersTable extends Migration
{
    public function up()
    {
        // Нэмэхийн өмнө тухайн багана байгаа эсэхийг шалгана, ингэснээр давхар нэмэх алдаа гарна.
        if (!Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('role')->nullable()->after('email')->comment('user, author, admin');
            });
        }

        if (!Schema::hasColumn('users', 'is_author')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('is_author')->default(false)->after('role');
            });
        }

        if (!Schema::hasColumn('users', 'profile_image')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('profile_image')->nullable()->after('is_author');
            });
        }

        if (!Schema::hasColumn('users', 'bio')) {
            Schema::table('users', function (Blueprint $table) {
                $table->text('bio')->nullable()->after('profile_image');
            });
        }

        if (!Schema::hasColumn('users', 'phone')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('phone')->nullable()->after('bio');
            });
        }

        if (!Schema::hasColumn('users', 'address')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('address')->nullable()->after('phone');
            });
        }

        if (!Schema::hasColumn('users', 'age')) {
            Schema::table('users', function (Blueprint $table) {
                $table->unsignedTinyInteger('age')->nullable()->after('address');
            });
        }
    }

    public function down()
    {
        // Устгахын өмнө байгаа колонуудаа цуглуулж дараа нь устгана.
        $columns = [];
        foreach (['role','is_author','profile_image','bio','phone','address','age'] as $col) {
            if (Schema::hasColumn('users', $col)) {
                $columns[] = $col;
            }
        }

        if (!empty($columns)) {
            Schema::table('users', function (Blueprint $table) use ($columns) {
                $table->dropColumn($columns);
            });
        }
    }
}
