<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\BookCategory;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

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
            'author_name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:255',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:book_categories,id',
            'published_date' => 'nullable|date',
            'price' => 'nullable|numeric|min:0',
            'pages' => 'nullable|integer|min:1',
            'cover_image' => 'nullable|image|max:5120',
        ]);

        $data = $request->all();

        // Auto-create author if needed
        if (!empty($data['author_name'])) {
            $authorName = trim($data['author_name']);
            $author = \App\Models\Author::firstOrCreate(
                ['name' => $authorName],
                ['slug' => \Illuminate\Support\Str::slug($authorName)]
            );
            $data['author_id'] = $author->id;
            $data['author'] = $author->name;
        }

        // Handle single category (backward compatibility)
        $categoryId = null;
        if (!empty($data['category'])) {
            $categoryName = trim($data['category']);
            $categoryModel = BookCategory::firstOrCreate(
                ['name' => $categoryName],
                ['slug' => \Illuminate\Support\Str::slug($categoryName)]
            );
            $categoryId = $categoryModel->id;
            $data['category_id'] = $categoryId;
        }

        // Cover image
        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        $book = Book::create($data);

        // Attach multiple categories if provided
        if (!empty($request->category_ids)) {
            $book->categories()->attach($request->category_ids);
        } elseif ($categoryId) {
            // If single category was created, also attach it to many-to-many
            $book->categories()->attach($categoryId);
        }

        return redirect()->route('admin.books.index')->with('success', 'Ном амжилттай нэмэгдлээ');
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
            'author_name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:255',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:book_categories,id',
            'published_date' => 'nullable|date',
            'price' => 'nullable|numeric|min:0',
            'pages' => 'nullable|integer|min:1',
            'cover_image' => 'nullable|image|max:5120',
        ]);

        $updateData = $request->all();

        // Auto-create author if needed
        if (!empty($updateData['author_name'])) {
            $authorName = trim($updateData['author_name']);
            $author = \App\Models\Author::firstOrCreate(
                ['name' => $authorName],
                ['slug' => \Illuminate\Support\Str::slug($authorName)]
            );
            $updateData['author_id'] = $author->id;
            $updateData['author'] = $author->name;
        }

        // Handle single category
        $categoryId = null;
        if (!empty($updateData['category'])) {
            $categoryName = trim($updateData['category']);
            $categoryModel = BookCategory::firstOrCreate(
                ['name' => $categoryName],
                ['slug' => \Illuminate\Support\Str::slug($categoryName)]
            );
            $categoryId = $categoryModel->id;
            $updateData['category_id'] = $categoryId;
        }

        // Handle cover image
        if ($request->hasFile('cover_image')) {
            // Delete old image
            if ($book->cover_image && Storage::disk('public')->exists($book->cover_image)) {
                Storage::disk('public')->delete($book->cover_image);
            }
            $updateData['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        $book->update($updateData);

        // Sync multiple categories
        if (!empty($request->category_ids)) {
            $book->categories()->sync($request->category_ids);
        } elseif ($categoryId) {
            // If single category was created, sync it
            $book->categories()->sync([$categoryId]);
        } else {
            // Clear all categories if none provided
            $book->categories()->sync([]);
        }

        return redirect()->route('admin.books.index')->with('success', 'Ном амжилттай шинэчлэгдлээ');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        $book->load('categories'); // Load many-to-many categories
        $categories = BookCategory::orderBy('name')->get();
        return view('layouts.admin.books.edit', compact('book', 'categories'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        // Зураг устгах
        if ($book->cover_image && Storage::disk('public')->exists($book->cover_image)) {
            Storage::disk('public')->delete($book->cover_image);
        }

        $book->delete();

        return redirect()->route('admin.books.index');
    }
}