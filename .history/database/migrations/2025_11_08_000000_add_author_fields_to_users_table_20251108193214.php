<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('authors', function (Blueprint $table) {
            $table->id();

            // Тусад системийн user-тай холбох боломж (optional)
            $table->unsignedBigInteger('user_id')->nullable()->index();

            $table->string('name');
            $table->string('slug')->nullable()->unique();
            $table->string('avatar')->nullable();

            // Төрсөн огноо, газар
            $table->date('birth_date')->nullable();
            $table->string('birth_place')->nullable();

            // Танилцуулга / био
            $table->text('bio')->nullable();

            // Нийтлэсэн/хэвлэсэн бүтээлүүд (JSON)
            $table->json('notable_works')->nullable();

            // Удирддаг/хариуцдаг админ (users хүснэгттэй гадаад түлхүүр)
            $table->unsignedBigInteger('managed_by')->nullable()->index();

            // Байр суурь, зэрэг, статус
            $table->string('position')->nullable();
            $table->boolean('is_active')->default(true);

            // Холбоо ба бусад
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('website')->nullable();
            $table->string('country')->nullable();

            // Сошиал холбоосууд, meta
            $table->json('social_links')->nullable();
            $table->json('meta')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Гадаад түлхүүрийн хамаарлууд (users хүснэгт байх ёстой)
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('managed_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down() {
        Schema::dropIfExists('authors');
    }
};
