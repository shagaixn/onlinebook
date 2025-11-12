@include('include.header')

<div class="max-w-7xl mx-auto px-6 py-10">
    <h1 class="text-3xl font-bold mb-6 text-slate-900 dark:text-slate-100">Зохиолчид</h1>

    <form method="GET" action="{{ route('authors.index') }}" class="mb-6 flex gap-3">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Нэр эсвэл танилцуулга..."
                     class="flex-1 px-4 py-2 rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500" />
        <button type="submit" class="px-5 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700">Хайх</button>
    </form>

    @if($authors->count() === 0)
        <div class="p-8 text-center text-gray-500 dark:text-gray-400 bg-white dark:bg-slate-900 rounded-xl border border-gray-100 dark:border-slate-800">
            Зохиолч олдсонгүй.
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($authors as $author)
                <a href="{{ route('authors.show', $author->slug) }}" class="block bg-white dark:bg-slate-900 rounded-2xl shadow hover:shadow-lg border border-gray-100 dark:border-slate-800 overflow-hidden hover:-translate-y-0.5 transition">
                    @if($author->profile_image)
                        {{-- Use asset('storage/...') to avoid APP_URL mismatch issues and simplify path resolution --}}
                        <img 
                            src="{{ asset('storage/'.$author->profile_image) }}" 
                            alt="{{ $author->name }}" 
                            loading="lazy"
                            class="w-full h-48 object-cover" 
                            onerror="this.onerror=null;this.src='/images/authors/default.jpg';" />
                    @else
                        <div class="w-full h-48 bg-gray-200 dark:bg-slate-800 flex items-center justify-center text-gray-500">
                            No Image
                        </div>
                    @endif
                    <div class="p-4">
                        <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100">{{ $author->name }}</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $author->nationality ?? 'Үндэс тодорхойгүй' }}</p>
                        <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">{{ Str::limit($author->biography, 100) }}</p>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $authors->links() }}
        </div>
    @endif
</div>

@include('include.footer')
