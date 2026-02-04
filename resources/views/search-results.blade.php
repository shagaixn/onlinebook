@include('include.header')

<main class="night-sky min-h-[100svh] max-w-9xl mx-auto px-4 py-20">
<div class="max-w-7xl mx-auto px-6 py-10">
    <div class="mb-10">
        <h1 class="text-3xl font-bold mb-2 text-slate-900 dark:text-slate-100">Хайлтын үр дүн</h1>
        <p class="text-gray-600 dark:text-gray-400">
            "<span class="font-semibold text-blue-600 dark:text-blue-400">{{ $query }}</span>" - хайлтаар 
            <span class="font-medium">{{ $books->total() + $authors->total() }}</span> үр дүн олдлоо
        </p>
    </div>

    {{-- Books Results --}}
    @if($books->count() > 0)
        <div class="mb-16">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100 flex items-center gap-2">
                    <span>📚</span> Номнууд
                    <span class="text-sm font-normal text-gray-500 dark:text-gray-400">({{ $books->total() }})</span>
                </h2>
                <a href="{{ route('book', ['q' => $query]) }}" class="text-blue-600 dark:text-blue-400 hover:underline text-sm font-medium">
                    Бүгдийг харах →
                </a>
            </div>
            
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
                @foreach($books as $book)
                    <a href="{{ route('books.show', $book->id) }}" class="group block">
                        <div class="relative aspect-[2/3] rounded-lg overflow-hidden shadow-md group-hover:shadow-xl transition-all duration-300 group-hover:-translate-y-1">
                            @if($book->cover_image)
                                <img src="{{ asset('storage/' . $book->cover_image) }}" 
                                     alt="{{ $book->title }}" 
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                                    <svg class="w-16 h-16 text-white opacity-50" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white line-clamp-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                            {{ $book->title }}
                        </h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $book->author_display }}</p>
                    </a>
                @endforeach
            </div>
            
            @if($books->hasPages())
                <div class="mt-6">
                    {{ $books->appends(['q' => $query])->links() }}
                </div>
            @endif
        </div>
    @endif

    {{-- Authors Results --}}
    @if($authors->count() > 0)
        <div class="mb-12">
            <h2 class="text-2xl font-bold mb-6 text-slate-900 dark:text-slate-100 flex items-center gap-2">
                <span>👥</span> Зохиолчид
                <span class="text-sm font-normal text-gray-500 dark:text-gray-400">({{ $authors->total() }})</span>
            </h2>
            
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                @foreach($authors as $author)
                    <a href="{{ route('authors.show', $author->slug) }}" 
                       class="group bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700 p-4 hover:shadow-lg transition-all hover:-translate-y-1">
                        <div class="flex items-center gap-3">
                            <div class="w-14 h-14 rounded-full overflow-hidden flex-shrink-0 bg-gradient-to-br from-yellow-500 via-amber-500 to-yellow-600">
                                @if($author->profile_image)
                                    <img src="{{ asset(\Illuminate\Support\Str::startsWith($author->profile_image, ['http://', 'https://', '/']) ? $author->profile_image : 'storage/' . ltrim($author->profile_image, '/')) }}" 
                                         alt="{{ $author->name }}" 
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="font-semibold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors truncate">
                                    {{ $author->name }}
                                </h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $author->books_count }} {{ $author->books_count == 1 ? 'ном' : 'ном' }}
                                </p>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
            
            @if($authors->hasPages())
                <div class="mt-6">
                    {{ $authors->appends(['q' => $query])->links() }}
                </div>
            @endif
        </div>
    @endif

    {{-- No Results --}}
    @if($books->count() === 0 && $authors->count() === 0)
        <div class="p-16 text-center bg-white dark:bg-slate-800 rounded-2xl border border-gray-200 dark:border-slate-700">
            <svg class="mx-auto h-20 w-20 text-gray-400 dark:text-slate-500 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Хайлтын үр дүн олдсонгүй</h3>
            <p class="text-gray-500 dark:text-gray-400 mb-6">
                "<span class="font-semibold">{{ $query }}</span>" - ийн хайлтаар ном эсвэл зохиолч олдсонгүй.
            </p>
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Нүүр хуудас руу буцах
            </a>
        </div>
    @endif
</div>
</main>

@include('include.footer')
