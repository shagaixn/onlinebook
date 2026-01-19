@extends('layouts.admin')

@section('title', 'Ангиллын дэлгэрэнгүй')

@section('content')
<div class="p-6">
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-800">📂 {{ $category->name }}</h1>
        <a href="{{ route('admin.categories.index') }}"
           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
            Буцах
        </a>
    </div>

    <div class="bg-white rounded-xl shadow p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Мэдээлэл</h2>
        
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-gray-600 font-semibold">Нэр:</p>
                <p class="text-gray-800">{{ $category->name }}</p>
            </div>
            <div>
                <p class="text-gray-600 font-semibold">Slug:</p>
                <p class="text-gray-800">{{ $category->slug }}</p>
            </div>
            <div class="col-span-2">
                <p class="text-gray-600 font-semibold">Тайлбар:</p>
                <p class="text-gray-800">{{ $category->description ?? '-' }}</p>
            </div>
            <div>
                <p class="text-gray-600 font-semibold">Номын тоо:</p>
                <p class="text-gray-800">{{ $category->books->count() }}</p>
            </div>
            <div>
                <p class="text-gray-600 font-semibold">Үүсгэсэн:</p>
                <p class="text-gray-800">{{ $category->created_at->format('Y-m-d H:i') }}</p>
            </div>
        </div>
    </div>

    @if ($category->books->count() > 0)
    <div class="bg-white rounded-xl shadow p-6">
        <h2 class="text-xl font-semibold mb-4">Энэ ангиллын номууд (сүүлийн 20)</h2>
        
        <div class="overflow-x-auto">
            <table class="min-w-full border-collapse">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left text-gray-600 font-semibold">Зураг</th>
                        <th class="px-4 py-2 text-left text-gray-600 font-semibold">Нэр</th>
                        <th class="px-4 py-2 text-left text-gray-600 font-semibold">Зохиолч</th>
                        <th class="px-4 py-2 text-center text-gray-600 font-semibold">Үйлдэл</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($category->books as $book)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-2">
                            @if ($book->cover_image)
                                <img src="{{ asset('storage/' . $book->cover_image) }}" 
                                     alt="{{ $book->title }}"
                                     class="w-12 h-16 object-cover rounded">
                            @else
                                <div class="w-12 h-16 bg-gray-200 rounded flex items-center justify-center">
                                    📚
                                </div>
                            @endif
                        </td>
                        <td class="px-4 py-2 font-medium">{{ $book->title }}</td>
                        <td class="px-4 py-2 text-gray-600">{{ $book->author_display ?? '-' }}</td>
                        <td class="px-4 py-2 text-center">
                            <a href="{{ route('admin.books.show', $book) }}"
                               class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">
                                Харах
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection
