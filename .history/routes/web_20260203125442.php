<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SocialController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\ReviewController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

// Social Login
Route::get('login/{provider}', [SocialController::class, 'redirectToProvider'])->where('provider', 'facebook|twitter|instagram')->name('social.redirect');
Route::get('login/{provider}/callback', [SocialController::class, 'handleProviderCallback'])->where('provider', 'facebook|twitter|instagram')->name('social.callback');

// Public
Route::get('/home', [HomeController::class, 'home'])->name('home');
Route::get('/book', [HomeController::class, 'book'])->name('book');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/service', [HomeController::class, 'service'])->name('service');
Route::get('/podcast', function () {
    return view('pages.podcast');
})->name('podcast');
Route::get('/manga', function () {
    return view('pages.manga');
})->name('manga');
Route::get('show', [HomeController::class, 'show'])->name('show');
// Single book (public)
Route::get('/books/{id}/read', [BookController::class, 'read'])->name('books.read');
Route::get('/books/{id}', [BookController::class, 'show'])->name('books.show');

// Authors list (public)
Route::get('/authors', [HomeController::class, 'authors'])->name('authors.index');
// Single author (public) - this must come after the /authors list route
Route::get('/authors/{slug}', [AuthorController::class, 'publicShow'])->name('authors.show');

// Auth / profile
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/profile/change-password', function () {
        return view('profile.change_password');
    })->name('profile.changePasswordForm');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.changePassword');
    // Reviews
    Route::post('/reviews', [\App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
    // Reading Progress
    Route::post('/books/{id}/progress', [\App\Http\Controllers\BookController::class, 'saveProgress'])->name('books.progress');
});

// Auth routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Admin routes (auth + admin middleware)
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])
    ->group(function () {
        Route::get('/', function() {
            return redirect()->route('admin.users.index');
        })->name('dashboard');

        Route::get('authors/export', [\App\Http\Controllers\AuthorController::class, 'export'])->name('authors.export');

        Route::resource('users', UserController::class);
        Route::resource('books', BookController::class);
        Route::resource('setting', SettingController::class);
        Route::resource('authors', AuthorController::class);
        
        // Book statistics chart
        Route::get('/bookchart', [\App\Http\Controllers\DashboardController::class, 'bookChart'])->name('bookchart');
    });

Route::post('/wishlist/toggle', function (Request $request) {
    $bookId = (int) $request->input('book_id');
    if (!$bookId) {
        return response()->json(['message' => 'Invalid book_id'], 422);
    }

    // Хэрэв хэрэглэгчийн wishlistBooks() релейшн байгаа бол DB дээр toggle
    if (Auth::check() && method_exists(Auth::user(), 'wishlistBooks')) {
        $user = Auth::user();
        $exists = $user->wishlistBooks()->where('book_id', $bookId)->exists();

        if ($exists) {
            $user->wishlistBooks()->detach($bookId);
            return response()->json(['in_wishlist' => false]);
        } else {
            $user->wishlistBooks()->attach($bookId);
            return response()->json(['in_wishlist' => true]);
        }
    }

    // Guest эсвэл релейшнгүй үед session ашиглана
    $key = 'wishlist.ids';
    $ids = collect(session($key, []));

    if ($ids->contains($bookId)) {
        $ids = $ids->reject(fn ($id) => (int)$id === $bookId)->values();
        session([$key => $ids->all()]);
        return response()->json(['in_wishlist' => false]);
    } else {
        $ids = $ids->push($bookId)->unique()->values();
        session([$key => $ids->all()]);
        return response()->json(['in_wishlist' => true]);
    }
})->name('wishlist.toggle');