<?php

namespace App\Http\Controllers;

use App\Models\BookCategory;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
   public function index(Request $request)
   {
       $query = BookCategory::withCount('books');

       if ($search = $request->get('q')) {
           $query->where(function ($q) use ($search) {
               $q->where('name', 'like', "%{$search}%")
                 ->orWhere('description', 'like', "%{$search}%");
           });
       }

       $categories = $query->orderBy('name')->paginate(12)->withQueryString();

       return view('categories.index', compact('categories'));
   }

   public function show($slugOrId)
   {
       // Try to find by slug first, if not found or if it's numeric, try by ID
       if (is_numeric($slugOrId)) {
           $category = BookCategory::findOrFail($slugOrId);
       } else {
           $category = BookCategory::where('slug', $slugOrId)->first();
           if (!$category) {
               $category = BookCategory::findOrFail($slugOrId);
           }
       }
       
       // Get IDs from many-to-many relationship
       $manyToManyBookIds = $category->books()->pluck('books.id');
       
       // Get IDs from single category_id relationship (backward compatibility)
       $singleCategoryBookIds = $category->booksWithSingleCategory()->pluck('id');
       
       // Merge and get unique IDs
       $allBookIds = $manyToManyBookIds->merge($singleCategoryBookIds)->unique();
       
       // Query books by IDs and paginate at database level
       $books = \App\Models\Book::whereIn('id', $allBookIds)
           ->with(['authorModel', 'categoryModel'])
           ->orderBy('created_at', 'desc')
           ->paginate(15);

       return view('categories.show', compact('category', 'books'));
   }
}