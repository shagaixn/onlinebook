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
            $table->string('position')->nullable()->after('birth_place');    
            $table->string('nationality')->nullable();
            $table->string('birth_place')->nullable(); // Үндэс, улс
            $table->date('birth_date')->nullable(); // Төрсөн он сар өдөр
            $table->date('death_date')->nullable(); // Нас барсан он сар өдөр
            $table->string('profile_image')->nullable();
            $table->text('notable_works')->nullable()->after('awards'); 
            $table->text('biography')->nullable(); // Намтар, товч мэдээлэл
 // Шагнал, амжилт (optional)
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
          $table->dropColumn(['nationality', 'birth_place', 'position', 'email', 'social_links', 'notable_works']);
    }
};
