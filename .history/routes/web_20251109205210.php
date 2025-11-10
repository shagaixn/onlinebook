<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SocialController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthorController;


Route::get('/', function () {
    return view('welcome');
});
        // home
        Route::get('/home', [HomeController::class, 'home'])->name('home');
        Route::get('/book', [HomeController::class, 'book'])->name('book');
        Route::get('/subscription', [HomeController::class, 'subscription'])->name('subscription');

        // routes/web.php
        Route::get('/books/{id}', [BookController::class, 'show'])->name('books.show');

        //authors
        Route::get('/authors', [AuthorController::class, 'index'])->name('authors.index');
        
        // login/logout
        
        Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [LoginController::class, 'login']);
        Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

        Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
        Route::post('/register', [RegisterController::class, 'register']);
        Route::get('/profile', [ProfileController::class, 'show'])->middleware('auth')->name('profile');

        // social login
        Route::get('login/facebook', [SocialController::class, 'redirectToFacebook'])->name('login.facebook');
        Route::get('login/facebook/callback', [SocialController::class, 'handleFacebookCallback']);

        Route::get('login/instagram', [SocialController::class, 'redirectToInstagram'])->name('login.instagram');
        Route::get('login/instagram/callback', [SocialController::class, 'handleInstagramCallback']);

    //admin
    Route::prefix('Admin')->name('Admin.')->middleware(['auth', 'Admin'])->group(function () {
Route::resource('users', UserController::class);
Route::resource('books', BookController::class);
Route::resource('setting', SettingController::class);
Route::resource('authors', AuthorController::class);

    Route::get('/books', [BookController::class, 'index'])->name('books.index');

    
});
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.changePassword');
});

Route::get('/authors', [AuthorController::class, 'index'])->name('authors.index');
