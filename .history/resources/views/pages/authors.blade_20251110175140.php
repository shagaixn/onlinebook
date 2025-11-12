@php use Illuminate\Support\Str; @endphp
@include('include.header')

<main class="min-h-screen">
    <div class="max-w-7xl mx-auto py-12 px-6">
        <h1 class="text-4xl font-bold mb-10 text-center text-gray-800 dark:text-gray-100">
            👥 Зохиолчид
        </h1>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
            @forelse($authors as $author)
                <div class="bg-white dark:bg-slate-900 shadow-lg rounded-2xl overflow-hidden hover:-translate-y-1 transition duration-200">
                    @if($author->profile_image)
                        <img src="{{ asset('storage/' . $author->profile_image) }}" alt="{{ $author->name }}" class="w-full h-56 object-cover">
                    @else
                        <div class="w-full h-56 bg-gray-200 dark:bg-slate-700 flex items-center justify-center text-gray-500">
                            <span>No Image</span>
                        </div>
                    @endif
                    <div class="p-5">
                        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-2">
                            {{ $author->name }}
                        </h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                            {{ $author->nationality ?? 'Үндэс тодорхойгүй' }}
                        </p>
                        <p class="text-gray-700 dark:text-gray-300 text-sm line-clamp-3">
                            {{ Str::limit($author->biography, 100) }}
                        </p>
                        <div class="mt-4 flex justify-end">
                            <a href="{{ route('authors.show', $author->slug) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium">Дэлгэрэнгүй →</a>
                        </div>
                    </div>
                </div>
            @empty
                <p class="col-span-4 text-center text-gray-600 dark:text-gray-300">
                    😢 Зохиолч олдсонгүй.
                </p>
            @endforelse
        </div>
        <div class="mt-10">
            {{ $authors->links() }}
        </div>
    </div>
</main>
@include('include.footer')
