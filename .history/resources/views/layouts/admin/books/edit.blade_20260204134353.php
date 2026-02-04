@extends('layouts.admin')

@section('title', 'Ном засах')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-6 rounded-2xl shadow">
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

        <div class="mb-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
            <label class="block font-medium text-gray-700 mb-1">📖 Номын нэр</label>
            <input type="text" name="title" value="{{ old('title', $book->title) }}"
                   class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-200 focus:border-blue-500" required>
        </div>

        <div class="mb-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
            <label class="block font-medium text-gray-700 mb-1">Зохиолчийн нэр</label>
            <input type="text" name="author_name" value="{{ old('author_name', $book->author ?? $book->authorModel?->name) }}" required
                   class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-200 focus:border-blue-500">
        </div>

        <div class="mb-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
            <label class="block font-medium text-gray-700 mb-1">Ангилал 1 (үндсэн)</label>
            <input type="text" name="category" value="{{ old('category', $book->category ?? $book->categoryModel?->name) }}"
                   class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-200 focus:border-blue-500">
            <p class="text-xs text-gray-500 mt-1">Шинэ ангилал бол автоматаар үүснэ.</p>
        </div>

        <div class="mb-3 p-3 bg-green-50 border border-green-200 rounded-lg">
            <label class="block font-medium text-gray-700 mb-1">Ангилал 2 (нэмэлт)</label>
            @php
                $secondCategory = $book->categories->skip(1)->first();
            @endphp
            <input type="text" name="category_2" value="{{ old('category_2', $secondCategory?->name) }}"
                   class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-200 focus:border-green-500">
            <p class="text-xs text-gray-500 mt-1">Хоёр дахь ангилал (заавал биш).</p>
        </div>
            
            <x-form.date-field name="published_date" label="Хэвлэгдсэн огноо" :value="old('published_date', $book->published_date)" />

            <div class="mb-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
            <label class="block font-medium text-gray-700 mb-1">🖼️ Номын зураг</label>
            @if ($book->cover_image)
                <div class="mb-3">
                    <img src="{{ asset('storage/' . $book->cover_image) }}" class="w-32 h-32 object-cover rounded-lg shadow">
                </div>
            @endif
            <input type="file" name="cover_image"
                   class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-200 focus:border-blue-500">
        </div>

        <div class="mb-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
            <label class="block font-medium text-gray-700 mb-1">📝 Тайлбар</label>
            <textarea name="description" rows="4"
                      class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-200 focus:border-blue-500">{{ old('description', $book->description) }}</textarea>
        </div>

        <div class="mb-3 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" name="is_featured" value="1" 
                       {{ old('is_featured', $book->is_featured) ? 'checked' : '' }}
                       class="w-5 h-5 rounded border-gray-300 text-yellow-600 focus:ring-yellow-500">
                <div>
                    <span class="block font-medium text-gray-700">🌟 Онцлох ном</span>
                    <span class="text-sm text-gray-600">Home хуудсанд онцлон харуулах</span>
                </div>
            </label>
        </div>

        <div class="flex justify-between pt-4">
            <a href="{{ route('admin.books.index') }}" class="text-gray-600 hover:text-gray-800">← Буцах</a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-xl shadow transition">
                Хадгалах
            </button>
        </div>
    </div>
    </form>
</div>

@endsection