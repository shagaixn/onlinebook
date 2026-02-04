<?php

namespace App\Http\Controllers;

use App\Models\dashboard;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = \App\Models\BookCategory::withCount('books')->get();

        $categoryLabels = $categories->pluck('name');
        $categoryCounts = $categories->pluck('books_count');

        return view('layouts.admin', [
            'categoryLabels' => $categoryLabels,
            'categoryCounts' => $categoryCounts,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */

    public function bookChart()
    {
        // Get total books count (only books with categories)
        $totalBooks = \App\Models\Book::whereNotNull('category_id')->count();
        
        // Get total authors count
        $totalAuthors = \App\Models\Author::count();
        
        // Get total users count
        $totalUsers = \App\Models\User::count();
        
        // Get book counts by category (only categories that have books)
        // Order by books count descending to show most popular categories
        $categories = \App\Models\BookCategory::withCount('books')
            ->having('books_count', '>', 0)
            ->orderBy('books_count', 'desc')
            ->get();
        
        $categoryLabels = $categories->pluck('name')->toArray();
        $bookCounts = $categories->pluck('books_count')->toArray();

        // Get top authors by book count
        $topAuthors = \App\Models\Author::withCount('books')
            ->having('books_count', '>', 0)
            ->orderBy('books_count', 'desc')
            ->limit(10)
            ->get()
            ->map(function($author) {
                return [
                    'name' => $author->name,
                    'books_count' => $author->books_count
                ];
            });

        // Get most read books based on reading progress count
        $mostReadBooks = \App\Models\Book::withCount('readingProgress')
            ->having('reading_progress_count', '>', 0)
            ->orderBy('reading_progress_count', 'desc')
            ->limit(10)
            ->get()
            ->map(function($book) {
                return [
                    'id' => $book->id,
                    'title' => $book->title,
                    'author' => $book->author_display,
                    'readers_count' => $book->reading_progress_count,
                    'cover_image' => $book->cover_image
                ];
            });

        // Get most read categories based on reading progress
        $mostReadCategories = \App\Models\BookCategory::withCount(['books as reading_count' => function($query) {
                $query->join('reading_progress', 'reading_progress.book_id', '=', 'books.id');
            }])
            ->having('reading_count', '>', 0)
            ->orderBy('reading_count', 'desc')
            ->get()
            ->map(function($category) {
                return [
                    'name' => $category->name,
                    'reading_count' => $category->reading_count
                ];
            });

        $readingCategoryLabels = $mostReadCategories->pluck('name')->toArray();
        $readingCategoryCounts = $mostReadCategories->pluck('reading_count')->toArray();

        return view('layouts.bookchart', [
            'totalBooks' => $totalBooks,
            'totalAuthors' => $totalAuthors,
            'totalUsers' => $totalUsers,
            'categories' => $categoryLabels,
            'bookCounts' => $bookCounts,
            'topAuthors' => $topAuthors,
            'mostReadBooks' => $mostReadBooks,
            'readingCategoryLabels' => $readingCategoryLabels,
            'readingCategoryCounts' => $readingCategoryCounts,
        ]);
    }

    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(dashboard $dashboard)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(dashboard $dashboard)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, dashboard $dashboard)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(dashboard $dashboard)
    {
        //
    }
}
