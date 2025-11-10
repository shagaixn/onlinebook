<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('book_categories', function (Blueprint $table) {
        $table->id();
        $table->string('name')->unique();
        $table->text('description')->nullable();
        $table->timestamps();
    });

    // 20 төрлийн category-г анхдагчаар нэмэх
    \DB::table('book_categories')->insert([
        ['name' => 'Түүх'],
        ['name' => 'Шинжлэх ухаан'],
        ['name' => 'Хүүхдийн ном'],
        ['name' => 'Сурах бичиг'],
        ['name' => 'Бизнес'],
        ['name' => 'Өөрийгөө хөгжүүлэх'],
        ['name' => 'Гадаад хэл'],
        ['name' => 'Технологи'],
        ['name' => 'Эрүүл мэнд'],
        ['name' => 'Хоол хүнс'],
        ['name' => 'Аялал'],
        ['name' => 'Урлаг'],
        ['name' => 'Спорт'],
        ['name' => 'Философи'],
        ['name' => 'Шашин'],
        ['name' => 'Сэтгэл судлал'],
        ['name' => 'Хууль'],
        ['name' => 'Инженер'],
        ['name' => 'Сонирхол'],
    ]);
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_categories');
    }
};