@extends('layouts.admin')

@section('title', 'Ном засах')

@section('content')
<div class="p-6 max-w-3xl mx-auto">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">✏️ Ном засах</h1>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="list-disc pl-6">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.books.update', $book->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6 bg-white p-6 rounded-2xl shadow">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-gray-700 font-medium mb-1">📖 Номын нэр</label>
            <input type="text" name="title" value="{{ old('title', $book->title) }}"
                   class="w-full border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-200 focus:border-blue-500" required>
        </div>

        <div>
            <label class="block font-medium text-gray-700 mb-1">Зохиолчийн нэр</label>
       <input type="text" name="author_name" value="{{ old('author_name', $book->author ?? $book->authorModel?->name) }}"
                   class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-200 focus:border-blue-500">
            <p class="text-xs text-gray-500 mt-1">Шинэ зохиолч бол автоматаар бүртгэгдэнэ.</p>
        </div>

        <div>
            <label class="block font-medium text-gray-700 mb-1">Ангилал (шууд бичих)</label>
            <input type="text" name="category" value="{{ old('category', $book->category ?? $book->categoryModel?->name) }}"
                   class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-200 focus:border-blue-500">
            <p class="text-xs text-gray-500 mt-1">Шинэ ангилал бол автоматаар үүснэ.</p>
        </div>
        @if($categories->count() > 0)
        <div>
            <label class="block font-medium text-gray-700 mb-2">Эсвэл сонгох (олон ангилал сонгож болно)</label>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-2 max-h-48 overflow-y-auto border border-gray-200 rounded-lg p-3">
                @foreach($categories as $cat)
                @php
                    $isChecked = $book->categories->contains($cat->id) || in_array($cat->id, old('category_ids', []));
                @endphp
                <label class="flex items-center gap-2 text-sm hover:bg-gray-50 p-2 rounded cursor-pointer">
                    <input type="checkbox" name="category_ids[]" value="{{ $cat->id }}" 
                        {{ $isChecked ? 'checked' : '' }}
                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span>{{ $cat->name }}</span>
                </label>
                @endforeach
            </div>
        </div>
        @endif
        <div>
            <label class="block text-gray-700 font-medium mb-1">📅 Хэвлэгдсэн огноо</label>
            <input type="date" name="published_date" value="{{ old('published_date', $book->published_date?->format('Y-m-d')) }}"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-200 focus:border-blue-500">
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-gray-700 font-medium mb-1">💰 Үнэ (₮)</label>
                <input type="number" name="price" value="{{ old('price', $book->price) }}"
                       class="w-full border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-200 focus:border-blue-500">
            </div>
            <div>
                <label class="block text-gray-700 font-medium mb-1">📄 Хуудасны тоо</label>
                <input type="number" name="pages" value="{{ old('pages', $book->pages) }}"
                       class="w-full border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-200 focus:border-blue-500">
            </div>
        </div>

        <div>
            <label class="block text-gray-700 font-medium mb-1">🖼️ Номын зураг</label>
            @if ($book->cover_image)
                <div class="mb-3">
                    <img src="{{ asset('storage/' . $book->cover_image) }}" class="w-32 h-32 object-cover rounded-lg shadow">
                </div>
            @endif
            <input type="file" name="cover_image"
                   class="w-full border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-200 focus:border-blue-500">
        </div>

        <div>
            <label class="block text-gray-700 font-medium mb-1">📝 Тайлбар</label>
            <textarea name="description" rows="4"
                      class="w-full border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-200 focus:border-blue-500">{{ old('description', $book->description) }}</textarea>
        </div>

        <div class="flex justify-between pt-4">
            <a href="{{ route('admin.books.index') }}" class="text-gray-600 hover:text-gray-800">← Буцах</a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-xl shadow transition">
                Хадгалах
            </button>
        </div>
    </form>
</div>
@endsection