<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Author;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AuthorController extends Controller
{
    // Заавал auth middleware-г route дээр эсвэл контроллер дээр нэмнэ
    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('can:manage-authors'); // хэрвээ permission байгаа бол идэвхжүүл
    }

    public function index(Request $request)
    {
        $query = Author::query();

        if ($q = $request->input('q')) {
            $query->where('name', 'like', "%{$q}%")->orWhere('bio', 'like', "%{$q}%");
        }

        $authors = $query->orderBy('name')->paginate(12)->withQueryString();

        return view('layouts.authors.index', compact('authors'));
    }

    public function create()
    {
        return view('layouts.admin.authors.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:authors,slug',
            'birth_date' => 'nullable|date',
            'death_date' => 'nullable|date|after_or_equal:birth_date',
            'birth_place' => 'nullable|string|max:255',
            'nationality' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'position' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:5000',
            'notable_works_text' => 'nullable|string|max:2000',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        
        $data = $request->only([
            'name', 'birth_date', 'death_date', 'birth_place', 
            'nationality', 'country', 'position', 'bio', 'notable_works_text'
        ]);
        
        // Auto-generate slug if not provided
        if (empty($request->slug)) {
            $data['slug'] = Str::slug($request->name);
        } else {
            $data['slug'] = Str::slug($request->slug);
        }
        
        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('authors', 'public');
        }
        
        // Handle notable works from textarea
        if (!empty($request->notable_works_text)) {
            $data['notable_works'] = array_values(
                array_filter(
                    array_map('trim', preg_split('/\r\n|[\r\n,]+/', $request->notable_works_text))
                )
            );
        }

        Author::create($data);

        return redirect()->route('admin.authors.index')->with('success', 'Зохиолч амжилттай нэмэгдлээ!');

        return redirect()->route('admin.authors.index')->with('success', 'Author created.');
    }

    public function edit(Author $author)
    {
        return view('admin.authors.edit', compact('author'));
    }

    public function update(Request $request, Author $author)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:authors,slug,' . $author->id,
            'birth_date' => 'nullable|date',
            'birth_place' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'notable_works' => 'nullable|array',
            'notable_works_text' => 'nullable|string',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:100',
            'website' => 'nullable|url|max:255',
            'is_active' => 'nullable|boolean',
            'avatar' => 'nullable|image|max:2048',
        ]);

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        if (!empty($data['notable_works_text'])) {
            $data['notable_works'] = array_values(array_filter(array_map('trim', preg_split('/\r\n|[\r\n,]+/', $data['notable_works_text']))));
        }

        if ($request->hasFile('avatar')) {
            // устгах хуучин
            if ($author->avatar) {
                Storage::disk('public')->delete($author->avatar);
            }
            $path = $request->file('avatar')->store('authors', 'public');
            $data['avatar'] = $path;
        }

        $data['is_active'] = !empty($data['is_active']);

        $author->update($data);

        return redirect()->route('admin.authors.index')->with('success', 'Author updated.');
    }

    public function destroy(Author $author)
    {
        // устгах өмнө зурагийг устгаж болно
        if ($author->avatar) {
            Storage::disk('public')->delete($author->avatar);
        }
        $author->delete();

        return redirect()->route('admin.authors.index')->with('success', 'Author deleted.');
    }
}
