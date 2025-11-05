<!-- <?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;

// Жишээ API route
Route::get('/test', function () {
    return response()->json(['message' => 'API ажиллаж байна!']);
    Route::get('/books', [BookController::class, 'index']);
});

Route::get('/books', function () {
    return response()->json(Book::all());
}); -->