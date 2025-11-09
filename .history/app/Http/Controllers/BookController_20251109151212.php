<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\BookCategory;
use App\Models\Author;
use Illuminate\Support\Facades\Storage;

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
        return view('books.show', compact('book', 'category'));
    }

    public function store(Request $request)
    {
        // author_id-ийн оронд author_name текст авна
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author_name' => 'required|string|max:255',
            'category_id' => 'required|exists:book_categories,id',
            'published_date' => 'required|date',
            'price' => 'required|numeric|min:0',
            'pages' => 'required|numeric|min:1',
            'description' => 'required|string',
            'cover_image' => 'nullable|image|max:2048',
        ]);

        // Зохиолч хайх / шинээр үүсгэх
        $authorName = trim($validated['author_name']);
        $author = Author::where('name', $authorName)->first();
        if (!$author) {
            $author = Author::create([
                'name' => $authorName,
                'slug' => $this->uniqueAuthorSlug($authorName),
            ]);
        }
        $validated['author_id'] = $author->id;
        unset($validated['author_name']);

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        Book::create($validated);

        return redirect()->route('admin.books.index')->with('success', 'Ном амжилттай нэмэгдлээ!');
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
            'author_id' => 'required|exists:authors,id',
            'category_id' => 'required|exists:book_categories,id',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'published_date' => 'nullable|date',
            'price' => 'required|integer|min:0',
            'pages' => 'nullable|integer|min:1',
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('cover_image')) {
            if ($book->cover_image && Storage::disk('public')->exists($book->cover_image)) {
                Storage::disk('public')->delete($book->cover_image);
            }
            $validated['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        $book->update($validated);

        return redirect()->route('admin.books.index')->with('success', 'Ном шинэчлэгдлээ!');
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
            if ($book && $book->cover && Storage::disk('public')->exists($book->cover)) {
                Storage::disk('public')->delete($book->cover);
            }
            return $request->file('cover_image')->store('books', 'public');
        }
        // Update үед зураггүй бол хуучин зургийг хадгална
        return $book ? $book->cover : null;
    }

    // Зохиолчийн slug давхардахгүй үүсгэх энгийн туслагч
    protected function uniqueAuthorSlug($name)
    {
        $base = \Str::slug($name);
        $slug = $base;
        $i = 1;
        while (Author::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }
}