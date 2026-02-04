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
            'title' => 'required|string|max:255|unique:books,title',
            'description' => 'required|string|max:255',
            'category_id' => 'nullable|exists:book_categories,id|required_without:category_name',
            'category_name' => 'nullable|string|max:255|required_without:category_id',
        ]);

        $data = $request->all();

        // Handle multiple categories (many-to-many) and legacy category
        $categoryIds = collect();
        
        // Handle category 1 (primary)
        $rawName = $request->input('category_name') ?? $request->input('category');
        $name = is_string($rawName) ? trim($rawName) : null;
        if ($name) {
            $categoryModel = BookCategory::firstOrCreate(['name' => $name]);
            $data['category_id'] = $categoryModel->id; // Legacy support
            $categoryIds->push($categoryModel->id);
        }
        
        // Handle category 2 (additional)
        $rawName2 = $request->input('category_2');
        $name2 = is_string($rawName2) ? trim($rawName2) : null;
        \Log::info('Store - Category 2 input:', ['raw' => $rawName2, 'trimmed' => $name2]);
        if ($name2) {
            $categoryModel2 = BookCategory::firstOrCreate(['name' => $name2]);
            \Log::info('Store - Category 2 created:', ['id' => $categoryModel2->id, 'name' => $categoryModel2->name]);
            $categoryIds->push($categoryModel2->id);
        }
        
        // Filter out duplicates
        $categoryIds = $categoryIds->filter(function($id) {
            return $id > 0;
        })->unique();

        // Зураг хадгалах
        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        $book = Book::create($data);
        
        // Debug: Log category IDs
        \Log::info('Store - Category IDs to sync:', ['ids' => $categoryIds->toArray()]);
        
        // Sync many-to-many relationships
        if ($categoryIds->isNotEmpty()) {
            $book->categories()->sync($categoryIds);
            \Log::info('Store - Synced categories:', ['book_id' => $book->id, 'count' => $book->categories()->count()]);
        }

        return redirect()->route('admin.books.index');
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
            'description' => 'required|string|max:255',
            'category_id' => 'nullable|exists:book_categories,id|required_without:category_name',
            'category_name' => 'nullable|string|max:255|required_without:category_id',
        ]);

        $updateData = $request->all();

        // Handle multiple categories (many-to-many) and legacy category
        $categoryIds = collect();
        
        // Handle category 1 (primary)
        $rawName = $request->input('category_name') ?? $request->input('category');
        $name = is_string($rawName) ? trim($rawName) : null;
        if ($name) {
            $categoryModel = BookCategory::firstOrCreate(['name' => $name]);
            $updateData['category_id'] = $categoryModel->id; // Legacy support
            $categoryIds->push($categoryModel->id);
        }
        
        // Handle category 2 (additional)
        $rawName2 = $request->input('category_2');
        $name2 = is_string($rawName2) ? trim($rawName2) : null;
        if ($name2) {
            $categoryModel2 = BookCategory::firstOrCreate(['name' => $name2]);
            $categoryIds->push($categoryModel2->id);
        }
        
        // Filter out duplicates
        $categoryIds = $categoryIds->filter(function($id) {
            return $id > 0;
        })->unique();

        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            $updateData['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }
        
        $book->update($updateData);
        
        // Sync many-to-many relationships (only if we have categories to sync)
        if ($categoryIds->isNotEmpty()) {
            $book->categories()->sync($categoryIds);
        } elseif (!$name && !$name2) {
            // If both categories are empty, clear all relationships
            $book->categories()->sync([]);
        }

        return redirect()->route('admin.books.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        $categories = BookCategory::all();
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