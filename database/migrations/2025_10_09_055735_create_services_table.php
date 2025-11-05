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
        Schema::create('services', function (Blueprint $table) {
        $table->id();
    $table->string('title'); // Үйлчилгээний нэр
    $table->text('description')->nullable(); // Тайлбар
    $table->string('icon')->nullable(); // Жижиг icon эсвэл зураг
    $table->string('link')->nullable(); // Хэрвээ тусдаа хуудас руу хөтлөх бол
    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
