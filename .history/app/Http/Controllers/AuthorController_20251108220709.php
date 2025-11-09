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
        return view('admin.authors.index', compact('authors'));
    }

    /**
     * Show the form for creating a new author.
     */
    public function create()
    {
        // Return the create blade view. Adjust view name if your file is located elsewhere.
        return view('admin.authors.create');
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



