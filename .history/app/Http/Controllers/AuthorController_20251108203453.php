<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AuthorController extends Controller
{
    /**
     * Display a listing of the authors.
     */
    public function index(Request $request)
    {
        $query = Author::query()->with(['user', 'manager']);

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhere('position', 'like', "%{$search}%");
            });
        }

        $authors = $query->orderBy('name')->paginate(12);

        return view('layouts.authors.index', compact('authors'));
    }

    /**
     * Show the form for creating a new author.
     */
    public function create()
    {
        return view('layouts.authors.create');
    }

    /**
     * Store a newly created author in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => ['nullable', 'string', 'max:255', 'alpha_dash', Rule::unique('authors','slug')],
            'avatar' => 'nullable|image|max:2048',
            'birth_date' => 'nullable|date',
            'birth_place' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'notable_works' => 'nullable',
            'managed_by' => 'nullable|exists:users,id',
            'position' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'social_links' => 'nullable',
            'meta' => 'nullable',
            'is_active' => 'nullable|boolean',
            'user_id' => 'nullable|exists:users,id',
        ]);

        // avatar upload
        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        // normalize array/json fields
        $data['notable_works'] = $this->normalizeToArray($data['notable_works'] ?? null);
        $data['social_links'] = $this->normalizeToAssoc($data['social_links'] ?? null);
        $data['meta'] = $this->normalizeToAssoc($data['meta'] ?? null);

        // slug
        if (empty($data['slug'])) {
            $data['slug'] = $this->generateUniqueSlug($data['name']);
        } else {
            $data['slug'] = Str::slug($data['slug']);
        }

        $author = Author::create($data);

        return redirect()->route('authors.index')->with('success', 'Зохиолч амжилттай нэмэгдлээ.');
    }

    /**
     * Display the specified author (by slug).
     */
    public function show($slug)
    {
        $author = Author::with(['user','manager'])->where('slug', $slug)->firstOrFail();

        return view('authors.show', compact('author'));
    }

    /**
     * Show the form for editing the specified author.
     */
    public function edit($id)
    {
        $author = Author::findOrFail($id);

        return view('authors.edit', compact('author'));
    }

    /**
     * Update the specified author in storage.
     */
    public function update(Request $request, $id)
    {
        $author = Author::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => ['nullable', 'string', 'max:255', 'alpha_dash', Rule::unique('authors','slug')->ignore($author->id)],
            'avatar' => 'nullable|image|max:2048',
            'birth_date' => 'nullable|date',
            'birth_place' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'notable_works' => 'nullable',
            'managed_by' => 'nullable|exists:users,id',
            'position' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'social_links' => 'nullable',
            'meta' => 'nullable',
            'is_active' => 'nullable|boolean',
            'user_id' => 'nullable|exists:users,id',
        ]);

        // avatar replace
        if ($request->hasFile('avatar')) {
            // delete old avatar if exists
            if ($author->avatar && Storage::disk('public')->exists($author->avatar)) {
                Storage::disk('public')->delete($author->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $data['notable_works'] = $this->normalizeToArray($data['notable_works'] ?? $author->notable_works);
        $data['social_links'] = $this->normalizeToAssoc($data['social_links'] ?? $author->social_links);
        $data['meta'] = $this->normalizeToAssoc($data['meta'] ?? $author->meta);

        // slug logic: regenerate only if empty or name changed and slug omitted
        if (empty($data['slug'])) {
            if ($author->name !== $data['name']) {
                $data['slug'] = $this->generateUniqueSlug($data['name'], $author->id);
            } else {
                unset($data['slug']);
            }
        } else {
            $data['slug'] = Str::slug($data['slug']);
        }

        $author->update($data);

        return redirect()->route('authors.index')->with('success', 'Зохиолч амжилттай шинэчлэгдлээ.');
    }

    /**
     * Remove the specified author from storage (soft delete).
     */
    public function destroy($id)
    {
        $author = Author::findOrFail($id);

        $author->delete();

        return redirect()->route('authors.index')->with('success', 'Зохиолч устгагдлаа.');
    }

    /**
     * Normalize input to array for notable_works.
     * Accepts array, JSON string, or comma-separated string.
     */
    protected function normalizeToArray($value)
    {
        if (is_array($value)) {
            return array_values(array_filter($value, fn($v) => $v !== null && $v !== ''));
        }

        if (is_string($value)) {
            $trimmed = trim($value);
            // try JSON
            if ($json = json_decode($trimmed, true)) {
                return is_array($json) ? $json : [];
            }
            // comma separated
            return array_values(array_filter(array_map('trim', explode(',', $trimmed)), fn($v) => $v !== ''));
        }

        return null;
    }

    /**
     * Normalize to associative array for social_links/meta.
     * Accepts JSON string or array.
     */
    protected function normalizeToAssoc($value)
    {
        if (is_array($value)) {
            return $value;
        }

        if (is_string($value)) {
            $trimmed = trim($value);
            if ($trimmed === '') {
                return null;
            }
            $json = json_decode($trimmed, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($json)) {
                return $json;
            }
            // fallback: treat as single url under 'website'
            return ['value' => $trimmed];
        }

        return null;
    }

    /**
     * Generate unique slug from name.
     */
    protected function generateUniqueSlug($name, $ignoreId = null)
    {
        $base = Str::slug($name ?: Str::random(8));
        $slug = $base;
        $i = 1;

        while (Author::where('slug', $slug)
                ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
                ->exists()) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }
}