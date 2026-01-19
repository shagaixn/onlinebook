<!DOCTYPE html>
<html lang="mn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $category->name }} - OnlineBook</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    @include('include.header')

    <div class="container mx-auto px-4 py-8">
        <!-- Category Header -->
        <div class="bg-white rounded-xl shadow-md p-8 mb-8">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h1 class="text-4xl font-bold text-gray-800 mb-2">📂 {{ $category->name }}</h1>
                    @if ($category->description)
                        <p class="text-gray-600 text-lg">{{ $category->description }}</p>
                    @endif
                </div>
                <a href="{{ route('categories.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                    ← Буцах
                </a>
            </div>
            <div class="mt-4">
                <span class="bg-blue-100 text-blue-800 px-4 py-2 rounded-full text-sm font-semibold">
                    {{ $books->total() }} ном
                </span>
            </div>
        </div>

        @if ($books->count() > 0)
        <!-- Books Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
            @foreach ($books as $book)
            <a href="{{ route('books.show', $book->id) }}" 
               class="bg-white rounded-xl shadow-md hover:shadow-xl transition overflow-hidden">
                <!-- Cover Image -->
                <div class="aspect-[2/3] bg-gray-200 relative overflow-hidden">
                    @if ($book->cover_image)
                        <img src="{{ asset('storage/' . $book->cover_image) }}" 
                             alt="{{ $book->title }}"
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-400 text-6xl">
                            📚
                        </div>
                    @endif
                </div>
                
                <!-- Book Info -->
                <div class="p-4">
                    <h3 class="font-bold text-gray-800 mb-1 line-clamp-2 text-sm">
                        {{ $book->title }}
                    </h3>
                    <p class="text-gray-600 text-xs mb-2">
                        {{ $book->author_display ?? 'Unknown' }}
                    </p>
                    @if ($book->price > 0)
                        <p class="text-blue-600 font-semibold text-sm">
                            ₮{{ number_format($book->price) }}
                        </p>
                    @else
                        <p class="text-green-600 font-semibold text-sm">
                            Үнэгүй
                        </p>
                    @endif
                </div>
            </a>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $books->links() }}
        </div>
        @else
        <div class="bg-yellow-100 border border-yellow-400 text-yellow-800 px-6 py-4 rounded-xl text-center">
            <p class="text-lg">Энэ ангилалд ном олдсонгүй</p>
        </div>
        @endif
    </div>
</body>
</html>
