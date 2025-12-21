<?php

namespace App\Providers;
use App\Models\Book;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        try {
            $categories = Book::query()
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

    View::share('categories', $categories);
        } catch (\Exception $e) {
            // Gracefully handle missing tables during migrations or setup
            View::share('categories', collect());
        }
     }