// database/migrations/xxxx_xx_xx_create_authors_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('authors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('avatar')->nullable();
            $table->text('bio')->nullable();
            $table->string('website')->nullable();
            $table->json('social')->nullable(); // {"twitter":"...","linkedin":"..."}
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('authors');
    }
};
