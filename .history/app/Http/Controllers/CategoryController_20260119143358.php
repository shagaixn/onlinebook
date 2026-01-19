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
       
       // Get books in this category (from both single and many-to-many relationships)
       $books = $category->books()->with(['authorModel'])->paginate(15);

       return view('categories.show', compact('category', 'books'));
   }
}