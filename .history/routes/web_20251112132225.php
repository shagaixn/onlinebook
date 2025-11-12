
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

Route::get('/', function () {
    return view('welcome');
});

// Public
Route::get('/home', [HomeController::class, 'home'])->name('home');
Route::get('/book', [HomeController::class, 'book'])->name('book');
Route::get('/subscription', [HomeController::class, 'subscription'])->name('subscription');
Route::get('/service', [HomeController::class, 'service'])->name('service');
Route::get('show', [HomeController::class, 'show'])->name('show');
Route::get('/authors', [HomeController::class, 'authors'])->name('authors.index');

Route::get('/books/{id}', [BookController::class, 'show'])->name('books.show');

// Authors list (public)
Route::get('/authors', [HomeController::class, 'authors'])->name('authors.index');
// Single author (public) - this must come after the /authors list route
Route::get('/authors/{slug}', [AuthorController::class, 'show'])->name('authors.show');

// Auth / profile
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.changePassword');
});

// Auth routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Social login
Route::get('login/facebook', [SocialController::class, 'redirectToFacebook'])->name('login.facebook');
Route::get('login/facebook/callback', [SocialController::class, 'handleFacebookCallback']);
Route::get('login/instagram', [SocialController::class, 'redirectToInstagram'])->name('login.instagram');
Route::get('login/instagram/callback', [SocialController::class, 'handleInstagramCallback']);

// Admin routes (auth + admin middleware)
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])
    ->group(function () {
        Route::get('/', function() {
            return redirect()->route('admin.users.index');
        })->name('dashboard');

        // Export authors CSV (must be defined BEFORE resource authors so 'export' doesn't get treated as {author})
        Route::get('authors/export', [\App\Http\Controllers\AuthorController::class, 'export'])->name('authors.export');

        Route::resource('users', UserController::class);
        Route::resource('books', BookController::class);
        Route::resource('setting', SettingController::class);
        Route::resource('authors', AuthorController::class);
        // books.index already provided by resource('books') so no extra Route::get('/books') needed
    });