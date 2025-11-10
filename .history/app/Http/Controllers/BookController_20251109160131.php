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
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author_name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:book_categories,id',
            'published_date' => 'nullable|date',
            'price' => 'nullable|numeric|min:0',
            'pages' => 'nullable|numeric|min:1',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|max:2048',
        ]);

        DB::beginTransaction();
        try {
            // Author-ийг зөвхөн байгаа нэрээр хайна, олдохгүй бол алдаа
            $authorName = trim($validated['author_name']);
            $author = Author::where('name', $authorName)->first();
            if (!$author) {
                return back()->withErrors(['author_name' => 'Ийм нэртэй зохиолч бүртгэлгүй байна.'])->withInput();
            }
            $authorId = $author->id;

            $bookData = [
                'title' => $validated['title'],
                'author_id' => $authorId,
                'price' => $validated['price'] ?? 0,
                'pages' => $validated['pages'] ?? null,
                'description' => $validated['description'] ?? null,
                'published_date' => $validated['published_date'] ?? null,
            ];

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

            if (\Route::has('admin.books.index')) {
                return redirect()->route('admin.books.index')->with('success', 'Ном амжилттай нэмэгдлээ!');
            }

            return redirect()->action([self::class, 'index'])->with('success', 'Ном амжилттай нэмэгдлээ!');
        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error('Book create failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
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

        // Author-ийг зөвхөн байгаа нэрээр хайна, олдохгүй бол алдаа
        $authorName = trim($validated['author_name']);
        $author = Author::where('name', $authorName)->first();
        if (!$author) {
            return back()->withErrors(['author_name' => 'Ийм нэртэй зохиолч бүртгэлгүй байна.'])->withInput();
        }
        $validated['author_id'] = $author->id;
        unset($validated['author_name']);

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