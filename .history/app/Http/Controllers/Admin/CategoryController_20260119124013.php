<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
   public function __construct()
   {
       $this->middleware('auth');
   }

   public function index(Request $request)
   {
       $query = BookCategory::withCount('books');

       if ($search = $request->get('search')) {
           $query->where(function ($q) use ($search) {
               $q->where('name', 'like', "%{$search}%")
                 ->orWhere('description', 'like', "%{$search}%");
           });
       }

       $categories = $query->orderBy('name')->paginate(15)->withQueryString();

       return view('layouts.admin.categories.index', compact('categories'));
   }

   public function create()
   {
       return view('layouts.admin.categories.create');
   }

   public function store(Request $request)
   {
       $data = $request->validate([
           'name' => 'required|string|max:255|unique:book_categories,name',
           'slug' => 'nullable|string|max:255|unique:book_categories,slug',
           'description' => 'nullable|string',
       ]);

       if (empty($data['slug'])) {
           $data['slug'] = $this->generateUniqueSlug($data['name']);
       } else {
           $data['slug'] = Str::slug($data['slug']);
       }

       BookCategory::create($data);

       return redirect()->route('admin.categories.index')->with('success', 'Ангилал амжилттай нэмэгдлээ.');
   }

   public function show(BookCategory $category)
   {
       $category->load(['books' => function ($query) {
           $query->latest()->take(20);
       }]);

       return view('layouts.admin.categories.show', compact('category'));
   }

   public function edit(BookCategory $category)
   {
       return view('layouts.admin.categories.edit', compact('category'));
   }

   public function update(Request $request, BookCategory $category)
   {
       $data = $request->validate([
           'name' => 'required|string|max:255|unique:book_categories,name,' . $category->id,
           'slug' => 'nullable|string|max:255|unique:book_categories,slug,' . $category->id,
           'description' => 'nullable|string',
       ]);

       if (empty($data['slug'])) {
           if ($category->name !== $data['name']) {
               $data['slug'] = $this->generateUniqueSlug($data['name'], $category->id);
           }
       } else {
           $data['slug'] = Str::slug($data['slug']);
       }

       $category->update($data);

       return redirect()->route('admin.categories.index')->with('success', 'Ангилал амжилттай шинэчлэгдлээ.');
   }

   public function destroy(BookCategory $category)
   {
       $category->delete();

       return redirect()->route('admin.categories.index')->with('success', 'Ангилал устгагдлаа.');
   }

   protected function generateUniqueSlug($name, $ignoreId = null)
   {
       $base = Str::slug($name ?: Str::random(8));
       $slug = $base;
       $i = 1;

       while (BookCategory::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
           $slug = $base . '-' . $i++;
       }

       return $slug;
   }
}