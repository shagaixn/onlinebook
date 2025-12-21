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
        Schema::create('authors', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Зохиолчийн нэр
            $table->string('slug')->unique(); // URL-д ашиглах өвөрмөц нэр
              $table->softDeletes(); // Мягмар устгах багана
            $table->string('nationality')->nullable(); // Үндэс, улс
            
            $table->date('birth_date')->nullable(); // Төрсөн он сар өдөр
            $table->date('death_date')->nullable(); // Нас барсан он сар өдөр
            $table->string('profile_image')->nullable(); // Зураг
            $table->text('biography')->nullable(); // Намтар, товч мэдээлэл
            $table->text('awards')->nullable(); // Шагнал, амжилт (optional)
            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('authors');
          $table->dropSoftDeletes();
    }
};
