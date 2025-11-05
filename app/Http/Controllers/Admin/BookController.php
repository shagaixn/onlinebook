<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\BookCategory;

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
        ['name' => 'Уран зохиол'],
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

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = Book::all();
        return view('layouts.admin.books.index', compact('books'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = BookCategory::all();
        return view('layouts.admin.books.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'category_id' => 'required|exists:book_categories,id',
        ]);

        $data = $request->all();

        // Зураг хадгалах
        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        Book::create($data);

        return redirect()->route('books.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        return view('layouts.admin.books.show', compact('book'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'category_id' => 'required|exists:book_categories,id',
        ]);

        $book->update($request->all());

        return redirect()->route('books.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        $book->delete();

        return redirect()->route('books.index');
    }
}