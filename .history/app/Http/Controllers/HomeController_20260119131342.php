<?php

namespace App\Http\Controllers;

use App\Models\Home;
use App\Models\Book;
use App\Models\Author;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // resources/views/pages/HomePage.blade.php-ийг дуудаж байна
        return view('pages.HomePage');
    }
    public function book(Request $request)
    {
        $q = trim((string) $request->get('q', ''));
        $categoryId = $request->integer('category_id');
        $categoryName = trim((string) $request->get('category', ''));
        $authorId = $request->integer('author_id');

        $query = Book::with(['authorModel', 'categories', 'categoryModel']);

        // Enhanced search: title, description, author name
        if ($q !== '') {
            $query->where(function($subQuery) use ($q) {
                $subQuery->where('title', 'like', "%{$q}%")
                        ->orWhere('description', 'like', "%{$q}%")
                        ->orWhere('author', 'like', "%{$q}%")
                        ->orWhereHas('authorModel', function($authorQuery) use ($q) {
                            $authorQuery->where('name', 'like', "%{$q}%");
                        });
            });
        }

        // Filter by category (supports both single and many-to-many)
        if ($categoryId) {
            $query->where(function($catQuery) use ($categoryId) {
                $catQuery->where('category_id', $categoryId)
                        ->orWhereHas('categories', function($manyQuery) use ($categoryId) {
                            $manyQuery->where('book_categories.id', $categoryId);
                        });
            });
        } elseif ($categoryName !== '') {
            $query->where(function($catQuery) use ($categoryName) {
                $catQuery->where('category', $categoryName)
                        ->orWhereHas('categories', function($manyQuery) use ($categoryName) {
                            $manyQuery->where('book_categories.name', $categoryName);
                        });
            });
        }
        // Filter by author
        if ($authorId) {
            $query->where('author_id', $authorId);
        }

        $books = $query->orderByDesc('created_at')->get();

         // Categories for dropdown
        $categories = \App\Models\BookCategory::orderBy('name')->get(['id','name']);

        // When no category filter is applied, prepare rows: each category with up to 10 latest books
        $categoryRows = collect();
        if (!$categoryId && $categoryName === '') {
            $cats = \App\Models\BookCategory::withCount('books')
                ->whereHas('books')
                ->orderByDesc('books_count')
                ->get();

            $categoryRows = $cats->map(function ($cat) {
                return [
                    'category' => $cat,
                    'books' => $cat->books()->latest()->take(10)->get(),
                ];
            });
        }

        return view('pages.Book', compact('books', 'categories', 'categoryId', 'categoryName', 'categoryRows'));
    }
    public function home()
    {
        // 1. Continue Reading (if logged in)
        $continueReading = null;
        if (\Illuminate\Support\Facades\Auth::check()) {
            $continueReading = \App\Models\ReadingProgress::with('book')
                ->where('user_id', \Illuminate\Support\Facades\Auth::id())
                ->orderBy('updated_at', 'desc')
                ->first();
        }

        // 2. New Books
        $newBooks = Book::latest()->take(6)->get();

        // 3. Top Rated Books (using the new reviews relation)
        // We need to load reviews count and avg rating
        $topRatedBooks = Book::withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->orderByDesc('reviews_avg_rating')
            ->having('reviews_count', '>', 0) // Only books with reviews
            ->take(6)
            ->get();
        
        // If not enough rated books, fill with random or latest
        if ($topRatedBooks->count() < 3) {
            $topRatedBooks = Book::inRandomOrder()->take(6)->get();
        }

        // 4. Categories
        $categories = \App\Models\BookCategory::withCount('books')->orderByDesc('books_count')->take(8)->get();

        // 5. Featured Authors
        $featuredAuthors = Author::withCount('books')->orderByDesc('books_count')->take(5)->get();

        // Wishlist IDs for UI state & Wishlist Books
        $wishlistIds = [];
        $wishlistBooks = collect();
        
        if (\Illuminate\Support\Facades\Auth::check()) {
            $user = \Illuminate\Support\Facades\Auth::user();
            $wishlistIds = $user->wishlistBooks()->pluck('book_id')->toArray();
            $wishlistBooks = $user->wishlistBooks()->latest()->get();
        } else {
            $wishlistIds = session('wishlist.ids', []);
            if (!empty($wishlistIds)) {
                $wishlistBooks = Book::whereIn('id', $wishlistIds)->get();
            }
        }

        return view('HomePage', compact(
            'continueReading',
            'newBooks',
            'topRatedBooks',
            'categories',
            'featuredAuthors',
            'wishlistIds',
            'wishlistBooks'
        ));
    }

    public function service()
    {
    return view('pages.service');
    }

    public function subscription()
    {
        return view('pages.subscription');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function authors(Request $request)
    {
        $query = Author::query();

        if ($search = $request->get('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nationality', 'like', "%{$search}%")
                  ->orWhere('biography', 'like', "%{$search}%");
            });
        }

        $authors = $query->orderBy('name')->paginate(12)->withQueryString();

        return view('authors.index', compact('authors'));
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
    public function show(Home $home)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Home $home)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Home $home)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Home $home)
    {
        //
    }
}
