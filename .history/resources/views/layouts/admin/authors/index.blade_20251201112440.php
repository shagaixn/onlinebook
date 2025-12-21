@extends('layouts.sidebar')

@section('title', 'Зохиолчид')

@section('content')
<div class="max-w-7xl mx-auto py-12 px-6">
    <h1 class="text-4xl font-bold mb-10 text-center text-gray-800 dark:text-gray-100">
        📚 Зохиолчдын жагсаалт
    </h1>
      {{-- Хайлт & Нэмэх товч --}}
    <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-8">
        <form method="GET" action="{{ route('admin.authors.index') }}" class="flex gap-2 w-full sm:w-auto">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Нэр, үндэс эсвэл намтраар хайх..."
                   class="flex-1 sm:w-80 px-4 py-2 rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <button type="submit" class="px-4 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700 transition">Хайх</button>
        </form>
        <a href="{{ route('admin.authors.create') }}" class="bg-green-600 text-white px-5 py-2 rounded-xl hover:bg-green-700 transition flex items-center gap-2">
            <span>+</span> Шинээр нэмэх
        </a>
    </div>
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
                    <div class="mt-4 flex justify-between items-center gap-2">
                                <a href="{{ route('authors.show', $author->slug) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium">
                                    Дэлгэрэнгүй →
                                </a>
                                <a href="{{ route('admin.authors.edit', $author->id) }}" class="inline-block px-3 py-1 rounded bg-yellow-400 hover:bg-yellow-500 text-white text-xs font-semibold transition">Засах</a>
                                <form action="{{ route('admin.authors.destroy', $author->id) }}" method="POST" onsubmit="return confirm('Устгахдаа итгэлтэй байна уу?');" class="inline-block">
                                     @csrf
                                     @method('DELETE')
                                     <button type="submit" class="px-3 py-1 rounded bg-red-500 hover:bg-red-600 text-white text-xs font-semibold transition">Устгах</button>
                                </form>
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
@endsection