<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\BookCategory;
use App\Models\Author;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;

class BookController extends Controller
{
    public function index(Request $request)
    {
        // Eager load authorModel & categoryModel so we can fallback if needed
        $query = Book::with(['authorModel', 'categoryModel']);

        if ($q = $request->input('q')) {
            $query->where(function ($qb) use ($q) {
                $qb->where('title', 'like', "%{$q}%")
                   ->orWhere('description', 'like', "%{$q}%");
            });
        }

        if ($category = $request->input('category')) {
            $query->where('category', $category);
        }

        if ($authorId = $request->input('author_id')) {
            $query->where('author_id', $authorId);
        }

        $books = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        // Authors list for filter (we are NOT creating new authors here)
        $authors = Author::orderBy('name')->pluck('name', 'id');

        return view('layouts.admin.books.index', compact('books', 'authors'));
    }

    public function create()
    {
        $categories = BookCategory::all();
        $authors = Author::orderBy('name')->get();
        return view('layouts.admin.books.create', compact('categories', 'authors'));
    }

    public function show($id)
    {
        $book = Book::findOrFail($id);
        $category = null;
        if ($book->category_id) {
            $category = BookCategory::find($book->category_id);
        }
        return view('layouts.admin.books.show', compact('book', 'category'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            // author_name нь хэрэглэгчийн оруулсан текст (шинэ зохиолч үүсгэхгүй)
            'author_name' => 'nullable|string|max:255',
            // author_id - бол хэрэглэгч өмнө нь байгаа зохиолчийг select хийсэн тохиолдолд ирнэ
            'author_id' => 'nullable|exists:authors,id',
            'category_id' => 'nullable|exists:book_categories,id',
            'published_date' => 'nullable|date',
            'price' => 'nullable|numeric|min:0',
            'pages' => 'nullable|numeric|min:1',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|max:2048',
        ]);

        DB::beginTransaction();
        try {
            // Тодорхойл: хэрвээ author_id ирсэн бол тухайн existing author-аас name-г авна;
            // эс тэгвэл form-оос ирсэн author_name-г books.author руу хадгална.
            $bookData = [
                'title' => $validated['title'],
                'price' => $validated['price'] ?? 0,
                'pages' => $validated['pages'] ?? null,
                'description' => $validated['description'] ?? null,
                'published_date' => $validated['published_date'] ?? null,
            ];

            $authorName = null;
            $authorId = null;

            if (!empty($validated['author_id'])) {
                $author = Author::find($validated['author_id']);
                if ($author) {
                    $authorId = $author->id;
                    $authorName = $author->name; // snapshot for display
                }
            } elseif (!empty($validated['author_name'])) {
                $authorName = trim($validated['author_name']);
            }

            $bookData['author_id'] = $authorId;
            $bookData['author'] = $authorName;

            // category_id -> category (string)
            if (!empty($validated['category_id'])) {
                $category = BookCategory::find($validated['category_id']);
                $bookData['category'] = $category?->name;
                $bookData['category_id'] = $validated['category_id'];
            } elseif ($request->filled('category')) {
                $bookData['category'] = $request->input('category');
            }

            if ($request->hasFile('cover_image')) {
                $bookData['cover_image'] = $request->file('cover_image')->store('covers', 'public');
            }

            $book = Book::create($bookData);

            DB::commit();

// LOG for debugging
Log::info('Book created, id=' . $book->id . ', attempting redirect.');

// Prefer admin route if exists, otherwise books.index, otherwise fallback to controller action or URL
if (Route::has('admin.books.index')) {
    Log::info('Redirecting to route admin.books.index');
    return redirect()->route('admin.books.index')->with('success', 'Ном амжилттай нэмэгдлээ!');
}

if (Route::has('books.index')) {
    Log::info('Redirecting to route books.index');
    return redirect()->route('books.index')->with('success', 'Ном амжилттай нэмэгдлээ!');
}

// Fallback to controller action (safe)
Log::info('Redirecting to controller action BookController@index as fallback');
return redirect()->action([self::class, 'index'])->with('success', 'Ном амжилттай нэмэгдлээ!');

    public function edit(Book $book)
    {
        $categories = BookCategory::all();
        $authors = Author::orderBy('name')->get();
        return view('layouts.admin.books.edit', compact('book', 'categories', 'authors'));
    }

    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author_name' => 'nullable|string|max:255',
            'author_id' => 'nullable|exists:authors,id',
            'category_id' => 'nullable|exists:book_categories,id',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'published_date' => 'nullable|date',
            'price' => 'required|integer|min:0',
            'pages' => 'nullable|integer|min:1',
            'description' => 'nullable|string',
        ]);

        // Prepare data
        $updateData = [
            'title' => $validated['title'],
            'price' => $validated['price'] ?? 0,
            'pages' => $validated['pages'] ?? null,
            'description' => $validated['description'] ?? null,
            'published_date' => $validated['published_date'] ?? null,
        ];

        // author: prefer selected existing author, otherwise use provided author_name (but DO NOT create new Author record)
        $authorName = null;
        $authorId = null;

        if (!empty($validated['author_id'])) {
            $author = Author::find($validated['author_id']);
            if ($author) {
                $authorId = $author->id;
                $authorName = $author->name;
            }
        } elseif (!empty($validated['author_name'])) {
            $authorName = trim($validated['author_name']);
        }

        $updateData['author_id'] = $authorId;
        $updateData['author'] = $authorName;

        // category
        $category = BookCategory::find($validated['category_id'] ?? null);
        $updateData['category'] = $category?->name;
        if (isset($validated['category_id'])) {
            $updateData['category_id'] = $validated['category_id'];
        }

        if ($request->hasFile('cover_image')) {
            if ($book->cover_image && Storage::disk('public')->exists($book->cover_image)) {
                Storage::disk('public')->delete($book->cover_image);
            }
            $updateData['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        $book->update($updateData);

        return redirect()->route(Route::has('admin.books.index') ? 'admin.books.index' : 'books.index')
                         ->with('success', 'Ном шинэчлэгдлээ!');
    }

    public function destroy(Book $book)
    {
        if ($book->cover_image && \Storage::disk('public')->exists($book->cover_image)) {
            \Storage::disk('public')->delete($book->cover_image);
        }
        $book->delete();
        return redirect()->route('admin.books.index')->with('success', 'Ном устгагдлаа!');
    }

    protected function handleImageUpload(Request $request, Book $book = null)
    {
        if ($request->hasFile('cover_image')) {
            if ($book && $book->cover_image && Storage::disk('public')->exists($book->cover_image)) {
                Storage::disk('public')->delete($book->cover_image);
            }
            return $request->file('cover_image')->store('books', 'public');
        }
        return $book ? $book->cover_image : null;
    }
}