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
       
       // Get books from many-to-many relationship
       $manyToManyBooks = $category->books()->with(['authorModel'])->get();
       
       // Get books from single category_id relationship (backward compatibility)
       $singleCategoryBooks = $category->booksWithSingleCategory()->with(['authorModel'])->get();
       
       // Merge and remove duplicates based on book id
       $allBooks = $manyToManyBooks->merge($singleCategoryBooks)->unique('id')->sortByDesc('created_at');
       
       // Manually paginate the merged collection
       $perPage = 15;
       $currentPage = request()->get('page', 1);
       $offset = ($currentPage - 1) * $perPage;
       
       $paginatedBooks = new \Illuminate\Pagination\LengthAwarePaginator(
           $allBooks->slice($offset, $perPage)->values(),
           $allBooks->count(),
           $perPage,
           $currentPage,
           ['path' => request()->url(), 'query' => request()->query()]
       );

       return view('categories.show', compact('category', 'paginatedBooks'))->with('books', $paginatedBooks);
   }
}