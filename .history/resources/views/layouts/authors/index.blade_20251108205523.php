@extends('layouts.sidebar')

@section('content')
<div class="max-w-6xl mx-auto mt-12 p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">Зохиолчид</h1>

        <div class="flex items-center gap-3">
            <form action="{{ route('authors.index') }}" method="GET" class="flex items-center gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Нэр эсвэл slug хайх"
                       class="px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Хайх</button>
            </form>

            <a href="{{ route('admin.authors.create') }}"
               class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                + Шинээр нэмэх
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if($authors->isEmpty())
        <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-6 rounded text-center">
            Зохиолч олдсонгүй.
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($authors as $author)
                <div class="bg-white dark:bg-slate-800 p-5 rounded-xl shadow hover:shadow-lg transition">
                    <div class="flex items-center gap-4 mb-4">
                        <img src="{{ $author->avatar ? Storage::disk('public')->url($author->avatar) : 'https://cdn-icons-png.flaticon.com/512/149/149071.png' }}"
                             alt="{{ $author->name }} avatar"
                             class="w-16 h-16 rounded-full object-cover border-2 border-gray-200">
                        <div>
                            <a href="{{ route('authors.show', $author->slug) }}" class="text-lg font-semibold text-gray-800 dark:text-gray-100">
                                {{ $author->name }}
                            </a>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1"> {{ $author->position ?? '' }}</p>
                        </div>
                    </div>

                    <p class="text-gray-700 dark:text-gray-300 text-sm mb-4 line-clamp-3">
                        {{ Str::limit($author->bio, 150) }}
                    </p>

                    <div class="flex items-center justify-between gap-2">
                        <div class="text-xs text-gray-500">
                            @if($author->birth_date)
                                Төрсөн: {{ $author->birth_date->format('Y-m-d') }}
                            @endif
                        </div>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('authors.edit', $author->id) }}"
                               class="text-sm px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">Засах</a>

                            <form action="{{ route('authors.destroy', $author->id) }}" method="POST" onsubmit="return confirm('Устгахдаа итгэлтэй байна уу?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sm px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">
                                    Устгах
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $authors->withQueryString()->links() }}
        </div>
    @endif
</div>
