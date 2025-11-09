@include('include.header')

<div class="max-w-4xl mx-auto mt-16 p-8 bg-white dark:bg-slate-900 rounded-2xl shadow transition-colors border border-gray-200 dark:border-slate-800">
    <h1 class="text-2xl font-bold mb-6">📚 Авторууд</h1>

    @if($authors->isEmpty())
        <p class="text-gray-600 dark:text-gray-400">Авторууд олдсонгүй.</p>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($authors as $author)
                <div class="p-4 rounded-lg bg-gray-50 dark:bg-slate-800 border border-gray-100 dark:border-slate-700">
                    <div class="flex items-center gap-4">
                        <img src="{{ $author->profile_image ?? 'https://cdn-icons-png.flaticon.com/512/149/149071.png' }}" alt="avatar" class="w-12 h-12 rounded-full object-cover">
                        <div>
                            <div class="font-semibold text-gray-800 dark:text-gray-100">{{ $author->name }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $author->email }}</div>
                        </div>
                    </div>
                    @if($author->bio ?? false)
                        <p class="mt-3 text-sm text-gray-600 dark:text-gray-300">{{ $author->bio }}</p>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $authors->links() }}
        </div>
    @endif

    <div class="mt-6">
        <a href="{{ url()->previous() }}" class="text-sm text-blue-600 hover:underline">◀ Буцах</a>
    </div>
</div>

@include('include.footer')
