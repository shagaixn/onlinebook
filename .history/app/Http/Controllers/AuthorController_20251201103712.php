<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\View;

class AuthorController extends Controller
{
    public function index(Request $request)
    {
        $query = Author::query();

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhere('nationality', 'like', "%{$search}%")
                  ->orWhere('biography', 'like', "%{$search}%");
            });
        }

        $authors = $query->orderBy('name')->paginate(15);

        return view('layouts.admin.authors.index', compact('authors'));
    }

    public function create()
    {
        // Only allow layouts.admin.authors.create, otherwise 404
        if (View::exists('layouts.admin.authors.create')) {
            return view('layouts.admin.authors.create');
        }
        abort(404, 'Create view not found. Expected: layouts.admin.authors.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => ['nullable','string','max:255','alpha_dash', Rule::unique('authors','slug')],
            'avatar' => 'nullable|image|max:2048', // form uses 'avatar' — we'll save to profile_image
            'birth_date' => 'nullable|date',
            'birth_place' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'notable_works_text' => 'nullable|string',
            'awards_text' => 'nullable|string', 
            'nationality' => 'nullable|string|max:255',
              'country' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'social_links' => 'nullable|array',
        ]);

         $socialLinks = [];
        if (!empty($data['social_links'])) $socialLinks = $data['social_links']; 

        // save uploaded avatar into profile_images & set profile_image column
        if ($request->hasFile('avatar')) {
            $data['profile_image'] = $request->file('avatar')->store('profile_images', 'public');
        }

        // slug: generate if empty
        if (empty($data['slug'])) {
            $data['slug'] = $this->generateUniqueSlug($data['name']);
        } else {
            $data['slug'] = Str::slug($data['slug']);
        }

        // map to DB columns that exist in your migration
        $saveData = [
            'name' => $data['name'],
            'slug' => $data['slug'],
            'nationality' => $data['nationality'] ?? null,
             'country' => $data['country'] ?? null,
            'birth_place' => $data['birth_place'] ?? null,
            'position' => $data['position'] ?? null,
            'email' => $data['email'] ?? null,
            'social_links' => !empty($socialLinks) ? $socialLinks : null,
            'birth_date' => $data['birth_date'] ?? null,
            'death_date' => $data['death_date'] ?? null,
            'profile_image' => $data['profile_image'] ?? null,
            'biography' => $data['bio'] ?? null,
            'awards' => $this->notableTextToAwards($data['notable_works_text'] ?? null),
            //   'awards' => $this->textToLines($data['awards_text'] ?? null),
            // 'notable_works' => $this->textToLines($data['notable_works_text'] ?? null),
        ];

        Author::create($saveData);

    return redirect()->route('admin.authors.index')->with('success', 'Зохиолч амжилттай нэмэгдлээ.');
    }

    // Admin resource show still maps here with {author} id. We keep it working for ID.
    public function show($id)
    {
        $author = Author::findOrFail($id);
        return view('authors.show', compact('author'));
    }

    // Public author details by slug (used by route('authors.show', $slug))
    public function publicShow(string $slug)
    {
        $author = Author::where('slug', $slug)->firstOrFail();
        return view('authors.show', compact('author'));
    }

    public function edit($id)
    {
    $author = Author::findOrFail($id);
    return view('layouts.admin.authors.edit', compact('author'));
    }

    public function update(Request $request, $id)
    {
        $author = Author::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => ['nullable','string','max:255','alpha_dash', Rule::unique('authors','slug')->ignore($author->id)],
            'avatar' => 'nullable|image|max:2048',
            'birth_date' => 'nullable|date',
            'birth_place' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'notable_works_text' => 'nullable|string',
             'awards_text' => 'nullable|string',
            'nationality' => 'nullable|string|max:255',
            'death_date' => 'nullable|date',
        ]);

        // avatar replacement -> profile_image
        if ($request->hasFile('avatar')) {
            // delete old profile image if exists
            if (!empty($author->profile_image) && Storage::disk('public')->exists($author->profile_image)) {
                Storage::disk('public')->delete($author->profile_image);
            }
            $data['profile_image'] = $request->file('avatar')->store('profile_images', 'public');
        }

        // slug logic
        if (empty($data['slug'])) {
            if ($author->name !== $data['name']) {
                $data['slug'] = $this->generateUniqueSlug($data['name'], $author->id);
            } else {
                unset($data['slug']);
            }
        } else {
            $data['slug'] = Str::slug($data['slug']);
        }

        $updateData = [
            'name' => $data['name'],
            'slug' => $data['slug'] ?? $author->slug,
            'nationality' => $data['nationality'] ?? $author->nationality,
            'birth_date' => $data['birth_date'] ?? $author->birth_date,
            'death_date' => $data['death_date'] ?? $author->death_date,
            'profile_image' => $data['profile_image'] ?? $author->profile_image,
            'biography' => $data['bio'] ?? $author->biography,
            'awards' => $this->notableTextToAwards($data['notable_works_text'] ?? null) ?? $author->awards,
        ];

        $author->update($updateData);

        return redirect()->route('admin.authors.index')->with('success', 'Зохиолч амжилттай шинэчлэгдлээ.');
    }

    public function destroy($id)
    {
        $author = Author::findOrFail($id);

        // delete profile image file if exists
        if (!empty($author->profile_image) && Storage::disk('public')->exists($author->profile_image)) {
            Storage::disk('public')->delete($author->profile_image);
        }

        $author->delete();

        return redirect()->route('admin.authors.index')->with('success', 'Зохиолч устгагдлаа.');
    }

    // Convert multiline/commas notable_works_text into a string for awards column
    protected function notableTextToAwards($text)
    {
        if (empty($text)) {
            return null;
        }

        // split by newlines, then by commas, trim and join by newline for readability
        $lines = preg_split('/\r\n|\r|\n/', trim($text));
        $items = [];
        foreach ($lines as $line) {
            foreach (explode(',', $line) as $part) {
                $p = trim($part);
                if ($p !== '') {
                    $items[] = $p;
                }
            }
        }

        return !empty($items) ? implode("\n", $items) : null;
    }

    protected function generateUniqueSlug($name, $ignoreId = null)
    {
        $base = Str::slug($name ?: Str::random(8));
        $slug = $base;
        $i = 1;

        while (Author::where('slug', $slug)->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }
}