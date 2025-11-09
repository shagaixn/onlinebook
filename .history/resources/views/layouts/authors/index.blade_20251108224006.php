@extends('layouts.si') {{-- layouts/app.blade.php гэж үзье --}}

@section('title', 'Зохиолчид')

@section('content')
<div class="max-w-7xl mx-auto py-12 px-6">

    <h1 class="text-4xl font-bold mb-10 text-center text-gray-800 dark:text-gray-100">
        📚 Зохиолчдын жагсаалт
    </h1>

    {{-- Хайлт хэсэг --}}
    <div class="mb-8 flex justify-center">
        <form action="{{ route('authors.index') }}" method="GET" class="flex gap-3 w-full max-w-md">
            <input 
                type="text" 
                name="search" 
                value="{{ request('search') }}" 
                placeholder="Зохиолчийн нэрээр хайх..." 
                class="w-full px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white"
            >
            <button 
                type="submit" 
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-xl transition">
                🔍 Хайх
            </button>
        </form>
    </div>

    {{-- Зохиолчдын жагсаалт --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
        @forelse($authors as $author)
            <div class="bg-white dark:bg-slate-900 shadow-lg rounded-2xl overflow-hidden hover:-translate-y-1 transition duration-200">
                {{-- Зураг --}}
                @if($author->profile_image)
                    <img src="{{ asset('storage/' . $author->profile_image) }}" alt="{{ $author->name }}" class="w-full h-56 object-cover">
                @else
                    <div class="w-full h-56 bg-gray-200 dark:bg-slate-700 flex items-center justify-center text-gray-500">
                        <span>No Image</span>
                    </div>
                @endif

                {{-- Мэдээлэл --}}
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
                        <a href="{{ route('authors.show', $author->slug) }}" 
                           class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium">
                           Дэлгэрэнгүй →
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <p class="col-span-4 text-center text-gray-600 dark:text-gray-300">
                😢 Зохиолч олдсонгүй.
            </p>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-10">
        {{ $authors->links() }}
    </div>

</div>
@endsection
