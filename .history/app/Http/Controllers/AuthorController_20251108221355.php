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
                  ->orWhere('position', 'like', "%{$search}%")
                  ->orWhere('country', 'like', "%{$search}%")
                  ->orWhere('bio', 'like', "%{$search}%");
            });
        }

        $authors = $query->orderBy('name')->paginate(12);

        // Return the admin view (adjust if your view path differs)
        return view('layouts.authors.index', compact('authors'));
    }

    /**
     * Show the form for creating a new author.
     */
    public function create()
    {
        // Return the create blade view. Adjust view name if your file is located elsewhere.
        return view('authors.create');
    }

    /**
     * Store a newly created author in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => ['nullable', 'string', 'max:255', 'alpha_dash', Rule::unique('authors', 'slug')],
            'avatar' => 'nullable|image|max:2048',
            'birth_date' => 'nullable|date',
            'birth_place' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            // accept notable_works (json/commas) or notable_works_text (multiline)
            'notable_works' => 'nullable',
            'notable_works_text' => 'nullable|string',
            'managed_by' => 'nullable|exists:users,id',
            'position' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'social_links' => 'nullable',
            'meta' => 'nullable',
            'is_active' => 'nullable|boolean',
            'user_id' => 'nullable|exists:users,id',
            // extra optional contact fields from your create form (will be saved to meta if present)
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'website' => 'nullable|url|max:255',
        ]);

        // avatar upload
        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        // If the create form provided notable_works_text (multiline), convert into array
        if (!empty($data['notable_works_text'])) {
            // split by new lines and commas, trim, filter empties
            $data['notable_works'] = $this->normalizeToArray($data['notable_works_text']);
        } else {
            $data['notable_works'] = $this->normalizeToArray($data['notable_works'] ?? null);
        }

        // normalize associative fields
        $data['social_links'] = $this->normalizeToAssoc($data['social_links'] ?? null);
        $data['meta'] = $this->normalizeToAssoc($data['meta'] ?? null);

        // If contact fields were provided in the form, merge into meta.contact
        $contact = [];
        if (!empty($data['email'])) {
            $contact['email'] = $data['email'];
        }
        if (!empty($data['phone'])) {
            $contact['phone'] = $data['phone'];
        }
        if (!empty($data['website'])) {
            $contact['website'] = $data['website'];
        }
        if (!empty($contact)) {
            $meta = $data['meta'] ?? [];
            if (!is_array($meta)) {
                $meta = [];
            }
            $meta['contact'] = array_merge($meta['contact'] ?? [], $contact);
            $data['meta'] = $meta;
        }

        // slug
        if (empty($data['slug'])) {
            $data['slug'] = $this->generateUniqueSlug($data['name']);
        } else {
            $data['slug'] = Str::slug($data['slug']);
        }

        // Only keep fillable-like fields to avoid unexpected columns; adjust if your model differs
        $saveData = [
            'user_id' => $data['user_id'] ?? null,
            'name' => $data['name'],
            'slug' => $data['slug'],
            'avatar' => $data['avatar'] ?? null,
            'birth_date' => $data['birth_date'] ?? null,
            'birth_place' => $data['birth_place'] ?? null,
            'bio' => $data['bio'] ?? null,
            'notable_works' => $data['notable_works'] ?? null,
            'managed_by' => $data['managed_by'] ?? null,
            'position' => $data['position'] ?? null,
            'country' => $data['country'] ?? null,
            'social_links' => $data['social_links'] ?? null,
            'meta' => $data['meta'] ?? null,
            'is_active' => $data['is_active'] ?? true,
        ];

        $author = Author::create($saveData);

        return redirect()->route('admin.authors.index')->with('success', 'Зохиолч амжилттай нэмэгдлээ.');
    }

    /**
     * Display the specified author (by slug).
     */
    public function show($slug)
    {
        $author = Author::with(['user', 'manager'])->where('slug', $slug)->firstOrFail();

        return view('admin.authors.show', compact('author'));
    }

    /**
     * Show the form for editing the specified author.
     */
    public function edit($id)
    {
        $author = Author::findOrFail($id);

        return view('admin.authors.edit', compact('author'));
    }

    /**
     * Update the specified author in storage.
     */
    public function update(Request $request, $id)
    {
        $author = Author::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => ['nullable', 'string', 'max:255', 'alpha_dash', Rule::unique('authors', 'slug')->ignore($author->id)],
            'avatar' => 'nullable|image|max:2048',
            'birth_date' => 'nullable|date',
            'birth_place' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'notable_works' => 'nullable',
            'notable_works_text' => 'nullable|string',
            'managed_by' => 'nullable|exists:users,id',
            'position' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'social_links' => 'nullable',
            'meta' => 'nullable',
            'is_active' => 'nullable|boolean',
            'user_id' => 'nullable|exists:users,id',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'website' => 'nullable|url|max:255',
        ]);

        // avatar replace
        if ($request->hasFile('avatar')) {
            // delete old avatar if exists
            if (!empty($author->avatar) && Storage::disk('public')->exists($author->avatar)) {
                Storage::disk('public')->delete($author->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        // notable works precedence: text area (multiline) -> notable_works json/commas -> keep existing
        if (!empty($data['notable_works_text'])) {
            $data['notable_works'] = $this->normalizeToArray($data['notable_works_text']);
        } else {
            $data['notable_works'] = $this->normalizeToArray($data['notable_works'] ?? $author->notable_works);
        }

        $data['social_links'] = $this->normalizeToAssoc($data['social_links'] ?? $author->social_links);
        $data['meta'] = $this->normalizeToAssoc($data['meta'] ?? $author->meta);

        // Merge contact fields into meta.contact if provided
        $contact = [];
        if (!empty($data['email'])) {
            $contact['email'] = $data['email'];
        }
        if (!empty($data['phone'])) {
            $contact['phone'] = $data['phone'];
        }
        if (!empty($data['website'])) {
            $contact['website'] = $data['website'];
        }
        if (!empty($contact)) {
            $meta = $data['meta'] ?? ($author->meta ?? []);
            if (!is_array($meta)) {
                $meta = [];
            }
            $meta['contact'] = array_merge($meta['contact'] ?? [], $contact);
            $data['meta'] = $meta;
        }

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

        // Prepare update payload (only keep expected keys)
        $updateData = [
            'user_id' => $data['user_id'] ?? $author->user_id,
            'name' => $data['name'],
            'slug' => $data['slug'] ?? $author->slug,
            'avatar' => $data['avatar'] ?? $author->avatar,
            'birth_date' => $data['birth_date'] ?? $author->birth_date,
            'birth_place' => $data['birth_place'] ?? $author->birth_place,
            'bio' => $data['bio'] ?? $author->bio,
            'notable_works' => $data['notable_works'] ?? $author->notable_works,
            'managed_by' => $data['managed_by'] ?? $author->managed_by,
            'position' => $data['position'] ?? $author->position,
            'country' => $data['country'] ?? $author->country,
            'social_links' => $data['social_links'] ?? $author->social_links,
            'meta' => $data['meta'] ?? $author->meta,
            'is_active' => $data['is_active'] ?? $author->is_active,
        ];

        $author->update($updateData);

        return redirect()->route('admin.authors.index')->with('success', 'Зохиолч амжилттай шинэчлэгдлээ.');
    }

    /**
     * Remove the specified author from storage (soft delete).
     */
    public function destroy($id)
    {
        $author = Author::findOrFail($id);

        $author->delete();

        return redirect()->route('admin.authors.index')->with('success', 'Зохиолч устгагдлаа.');
    }

    /**
     * Normalize input to array for notable_works.
     * Accepts array, JSON string, comma-separated string or newline-separated text.
     */
    protected function normalizeToArray($value)
    {
        if (is_array($value)) {
            return array_values(array_filter($value, fn($v) => $v !== null && $v !== ''));
        }

        if (is_string($value)) {
            $trimmed = trim($value);
            if ($trimmed === '') {
                return null;
            }

            // try JSON
            $json = json_decode($trimmed, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($json)) {
                return array_values(array_filter($json, fn($v) => $v !== null && $v !== ''));
            }

            // split by new lines first, then by commas
            $lines = preg_split('/\r\n|\r|\n/', $trimmed);
            $items = [];
            foreach ($lines as $line) {
                foreach (explode(',', $line) as $part) {
                    $part = trim($part);
                    if ($part !== '') {
                        $items[] = $part;
                    }
                }
            }

            return array_values(array_filter($items, fn($v) => $v !== null && $v !== ''));
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
            // fallback: treat as single url under 'website' or 'value'
            if (filter_var($trimmed, FILTER_VALIDATE_URL)) {
                return ['website' => $trimmed];
            }
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