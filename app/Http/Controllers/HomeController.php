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

        $books = Book::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where('title', 'like', "%{$q}%");
            })
            ->orderByDesc('created_at')
            ->get();

        return view('pages.Book', compact('books'));
    }
    public function home()
    {
        // Fetch latest books (limit to 6 for display)
        $newBooks = Book::with('author')
            ->orderByDesc('created_at')
            ->take(6)
            ->get();
        
        // Fetch latest authors (limit to 6 for display)
        $newAuthors = Author::orderByDesc('created_at')
            ->take(6)
            ->get();
        
        // Get wishlist IDs from session or authenticated user
        $wishlistIds = [];
        if (auth()->check() && method_exists(auth()->user(), 'wishlistBooks')) {
            $wishlistIds = auth()->user()->wishlistBooks()->pluck('book_id')->toArray();
        } else {
            $wishlistIds = session('wishlist.ids', []);
        }
        
        // Get wishlist books for marquee display (if any wishlist IDs exist)
        $wishlistBooks = !empty($wishlistIds)
            ? Book::whereIn('id', $wishlistIds)->select('id', 'title')->get()
            : collect();
        
        return view('HomePage', compact('newBooks', 'newAuthors', 'wishlistIds', 'wishlistBooks'));
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
