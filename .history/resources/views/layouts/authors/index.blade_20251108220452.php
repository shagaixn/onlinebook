@extends('layouts.sidebar')

@section('content')
<div class="max-w-6xl mx-auto p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">Зохиолчид</h1>

        <div class="flex items-center gap-3">
            <form action="{{ route('authors.index') }}" method="GET" class="flex items-center gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Нэр, улс эсвэл slug хайх"
                       class="px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Хайх</button>
            </form>

            <a href="{{ route('authors.create') }}" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                + Шинээр нэмэх
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 border border-green-200 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if($authors->isEmpty())
        <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-6 rounded text-center">
            Зохиолч олдсонгүй.
        </div>
    @else
        <div class="overflow-x-auto bg-white dark:bg-slate-800 rounded shadow">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-700">
                <thead class="bg-gray-50 dark:bg-slate-900">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">#</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Зураг</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Нэр</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Үндэс / Төрсөн</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Slug</th>
                        <th class="px-4 py-3 text-right text-sm font-medium text-gray-600">Үйлдэл</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100 dark:bg-slate-800 dark:divide-slate-700">
                    @foreach($authors as $author)
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">{{ $loop->iteration + ($authors->currentPage()-1) * $authors->perPage() }}</td>
                            <td class="px-4 py-3">
                                <img src="{{ $author->profile_image ? Storage::disk('public')->url($author->profile_image) : 'https://cdn-icons-png.flaticon.com/512/149/149071.png' }}"
                                     alt="{{ $author->name }}" class="w-10 h-10 rounded-full object-cover border">
                            </td>
                            <td class="px-4 py-3">
                                <div class="font-semibold text-gray-800 dark:text-gray-100">
                                    <a href="{{ route('authors.show', $author->slug) }}">{{ $author->name }}</a>
                                </div>
                                <div class="text-xs text-gray-500 mt-1">{{ Str::limit($author->biography, 80) }}</div>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                                <div>{{ $author->nationality ?? '-' }}</div>
                                <div class="text-xs text-gray-500">
                                    @if($author->birth_date) Төрсөн: {{ $author->birth_date->format('Y') }} @endif
                                    @if($author->death_date) • Нас барсан: {{ $author->death_date->format('Y') }} @endif
                                </div>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                                {{ $author->slug }}
                            </td>
                            <td class="px-4 py-3 text-right text-sm">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('authors.edit', $author->id) }}" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">Засах</a>

                                    <form action="{{ route('authors.destroy', $author->id) }}" method="POST" onsubmit="return confirm('Устгахдаа итгэлтэй байна уу?');" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 text-sm">Устгах</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $authors->withQueryString()->links() }}
        </div>
    @endif
</div>
@endsection