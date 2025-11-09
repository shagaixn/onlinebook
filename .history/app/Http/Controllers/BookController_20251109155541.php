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
       $query = Book::with(['author', 'categoryModel']); // <--- Eager load

        if ($q = $request->input('q')) {
            $query->where(function ($qb) use ($q) {
                $qb->where('title', 'like', "%{$q}%")
                   ->orWhere('description', 'like', "%{$q}%");
            });
        }

        if ($category = $request->input('category')) {
            $query->where('category', $category); // эсвэл category_id ашиглавал where('category_id', $category)
        }

        if ($authorId = $request->input('author_id')) {
            $query->where('author_id', $authorId);
        }

        $books = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        // Филтерийн жагсаалтууд
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
            $category = \App\Models\BookCategory::find($book->category_id);
        }
        return view('layouts.admin.books.show', compact('book', 'category'));
    }

    public function store(Request $request)
    {
        // NOTE:
        // - Validation relaxed slightly to avoid failing creation unintentionally.
        // - Accept both author_name (text) and optional category_id from the form.
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author_name' => 'nullable|string|max:255',
            'category_id' => 'nullable|exists:book_categories,id',
            'published_date' => 'nullable|date',
            'price' => 'nullable|numeric|min:0',
            'pages' => 'nullable|numeric|min:1',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|max:2048',
        ]);

        DB::beginTransaction();
        try {
            // determine author: if author_name provided -> findOrCreate; otherwise null
            $authorId = null;
            if (!empty($validated['author_name'])) {
                $authorName = trim($validated['author_name']);
                if ($authorName !== '') {
                    $author = Author::firstOrCreate(
                        ['name' => $authorName],
                        ['slug' => Str::slug($authorName) ?: Str::slug($authorName . '-' . uniqid())]
                    );
                    $authorId = $author->id;
                }
            }

            // If the form also provides an authors select with id, prefer it
            if ($request->filled('author_id')) {
                $authorId = $request->input('author_id');
            }

            // prepare book data to save (normalized to books table)
            $bookData = [
                'title' => $validated['title'],
                'author_id' => $authorId,
                'price' => $validated['price'] ?? 0,
                'pages' => $validated['pages'] ?? null,
                'description' => $validated['description'] ?? null,
            ];

            // Convert category_id -> category (string) if provided
            if (!empty($validated['category_id'])) {
                $category = BookCategory::find($validated['category_id']);
                $bookData['category'] = $category?->name;
                $bookData['category_id'] = $validated['category_id'];
            } elseif ($request->filled('category')) {
                // fallback: direct category string from a legacy form
                $bookData['category'] = $request->input('category');
            }

            if ($request->hasFile('cover_image')) {
                $bookData['cover_image'] = $request->file('cover_image')->store('covers', 'public');
            }

            $book = Book::create($bookData);

            DB::commit();

            // Redirect to index — prefer named route if exists, otherwise to controller action
            if (Route::has('admin.books.index')) {
                return redirect()->route('admin.books.index')->with('success', 'Ном амжилттай нэмэгдлээ!');
            }

            // Fallback to controller action (works if there is a route for the action)
            return redirect()->action([self::class, 'index'])->with('success', 'Ном амжилттай нэмэгдлээ!');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Book create failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            // Return back with user-friendly error; include exception message during development only
            return back()->withErrors(['general' => 'Үүсгэхэд алдаа: ' . $e->getMessage()])->withInput();
        }
    }

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
            'author_name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:book_categories,id',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'published_date' => 'nullable|date',
            'price' => 'required|integer|min:0',
            'pages' => 'nullable|integer|min:1',
            'description' => 'nullable|string',
        ]);

        // Author name-аар зохиолчийг олж авах/үүсгэх
        $authorName = trim($validated['author_name']);
        $author = Author::firstOrCreate(
            ['name' => $authorName],
            ['slug' => \Illuminate\Support\Str::slug($authorName) ?: \Illuminate\Support\Str::slug($authorName . '-' . uniqid())]
        );
        $validated['author_id'] = $author->id;
        unset($validated['author_name']);

        // category_id -> category (string) руу хөрвүүлэх
        $category = BookCategory::find($validated['category_id'] ?? null);
        $validated['category'] = $category?->name;
        if (isset($validated['category_id'])) {
            $validated['category_id'] = $validated['category_id'];
        }

        if ($request->hasFile('cover_image')) {
            if ($book->cover_image && Storage::disk('public')->exists($book->cover_image)) {
                Storage::disk('public')->delete($book->cover_image);
            }
            $validated['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        $book->update($validated);

        return redirect()->route(\Route::has('admin.books.index') ? 'admin.books.index' : 'books.index')
                         ->with('success', 'Ном шинэчлэгдлээ!');
    }

    public function destroy(Book $book)
    {
        // Зураг устгах (хэрвээ байгаа бол)
        if ($book->cover_image && \Storage::disk('public')->exists($book->cover_image)) {
            \Storage::disk('public')->delete($book->cover_image);
        }
        $book->delete();
        return redirect()->route('admin.books.index')->with('success', 'Ном устгагдлаа!');
    }

    /**
     * Зураг upload хийх, update үед хуучин зургийг устгах
     */
    protected function handleImageUpload(Request $request, Book $book = null)
    {
        if ($request->hasFile('cover_image')) {
            // Хэрвээ update бол хуучин зураг устгах
            if ($book && $book->cover_image && Storage::disk('public')->exists($book->cover_image)) {
                Storage::disk('public')->delete($book->cover_image);
            }
            return $request->file('cover_image')->store('books', 'public');
        }
        // Update үед зураггүй бол хуучин зургийг хадгална
        return $book ? $book->cover_image : null;
    }
}