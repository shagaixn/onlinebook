<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    /**
     * Display a listing of books (with optional search / filters).
     */
    public function index(Request $request)
    {
        $query = Book::query();

        // Search by title or description
        if ($q = $request->input('q')) {
            $query->where(function ($qb) use ($q) {
                $qb->where('title', 'like', "%{$q}%")
                   ->orWhere('description', 'like', "%{$q}%");
            });
        }

        // Filter by category
        if ($category = $request->input('category')) {
            $query->where('category', $category);
        }

        // Filter by author id
        if ($authorId = $request->input('author_id')) {
            $query->where('author_id', $authorId);
        }

        // Sort (optional)
        $sort = $request->input('sort', 'created_at');
        $direction = $request->input('dir', 'desc');

        $books = $query->orderBy($sort, $direction)->paginate(15)->withQueryString();

        // Prepare categories list for filter dropdown (distinct non-null categories)
        $categories = Book::query()
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        // Return view with books and categories
        return view('layouts.admin.books.index', compact('books', 'categories'));
    }

    /**
     * Show the form for creating a new book.
     */
    public function create()
    {
        // Provide categories so the create form can show dropdown / suggestions
        $categories = Book::query()
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('layouts.admin.books.create', compact('categories'));
    }

    /**
     * Store a newly created book in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'author_id'     => ['nullable', 'integer', 'exists:users,id'],
            'title'         => ['required', 'string', 'max:255'],
            'category'      => ['nullable', 'string', 'max:255'],
            'cover_image'   => ['nullable', 'image', 'max:5120'], // max 5MB
            'published_date'=> ['nullable', 'date'],
            'price'         => ['nullable', 'integer', 'min:0'],
            'pages'         => ['nullable', 'integer', 'min:1'],
            'description'   => ['nullable', 'string'],
        ]);

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        $book = Book::create($data);

        return redirect()->route('layouts.admin.books.show', $book)->with('success', 'Book created.');
    }

    /**
     * Display the specified book.
     */
    public function show(Book $book)
    {
        return view('books.show', compact('book'));
    }

    /**
     * Show the form for editing the specified book.
     */
    public function edit(Book $book)
    {
        // Provide categories for edit form as well
        $categories = Book::query()
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('books.edit', compact('book', 'categories'));
    }

    /**
     * Update the specified book in storage.
     */
    public function update(Request $request, Book $book)
    {
        $data = $request->validate([
            'author_id'     => ['nullable', 'integer', 'exists:users,id'],
            'title'         => ['required', 'string', 'max:255'],
            'category'      => ['nullable', 'string', 'max:255'],
            'cover_image'   => ['nullable', 'image', 'max:5120'],
            'published_date'=> ['nullable', 'date'],
            'price'         => ['nullable', 'integer', 'min:0'],
            'pages'         => ['nullable', 'integer', 'min:1'],
            'description'   => ['nullable', 'string'],
        ]);

        if ($request->hasFile('cover_image')) {
            // delete old cover (if any)
            if ($book->cover_image) {
                Storage::disk('public')->delete($book->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        $book->update($data);

        return redirect()->route('books.show', $book)->with('success', 'Book updated.');
    }

    /**
     * Remove the specified book from storage.
     */
    public function destroy(Book $book)
    {
        if ($book->cover_image) {
            Storage::disk('public')->delete($book->cover_image);
        }

        $book->delete();

        return redirect()->route('books.index')->with('success', 'Book deleted.');
    }
}