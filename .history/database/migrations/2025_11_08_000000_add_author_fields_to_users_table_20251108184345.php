<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAuthorFieldsToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->nullable()->after('email')->comment('user, author, admin');
            $table->boolean('is_author')->default(false)->after('role');
            $table->string('profile_image')->nullable()->after('is_author');
            $table->text('bio')->nullable()->after('profile_image');
            // optionally add contact fields if not present
            $table->string('phone')->nullable()->after('bio');
            $table->string('address')->nullable()->after('phone');
            $table->unsignedTinyInteger('age')->nullable()->after('address');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role','is_author','profile_image','bio','phone','address','age']);
        });
    }
}
