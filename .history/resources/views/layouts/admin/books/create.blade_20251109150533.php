@extends('layouts.admin')

@section('title', 'Ном нэмэх')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-6 rounded-2xl shadow">
  <h1 class="text-2xl font-bold mb-6">📘 Ном нэмэх</h1>

  <form action="{{ route('admin.books.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
    @csrf

    <div>
      <label class="block font-medium text-gray-700 mb-1">Номын гарчиг</label>
      <input type="text" name="title" value="{{ old('title') }}" required
             class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-200 focus:border-blue-500">
    </div>

    <div>
      <label class="block font-medium text-gray-700 mb-1">Зохиолчийн нэр </label>
      <input type="text" name="author" value="{{ old('author') }}" required
             class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-200 focus:border-blue-500">
    </div>

    <div>
      <label for="category_id" class="block font-medium text-gray-700 mb-1">Ангилал</label>
      <select name="category_id" id="category_id" required
              class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-200 focus:border-blue-500">
        <option value="">-- Сонгох --</option>
        @foreach($categories as $category)
          <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
            {{ $category->name }}
          </option>
        @endforeach
      </select>
    </div>

    <div>
      <label class="block font-medium text-gray-700 mb-1">Хэвлэгдсэн огноо</label>
      <input type="date" name="published_date" value="{{ old('published_date') }}"
             class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-200 focus:border-blue-500">
    </div>

    <div class="grid grid-cols-2 gap-4">
      <div>
        <label class="block font-medium text-gray-700 mb-1">Үнэ (₮)</label>
        <input type="number" name="price" value="{{ old('price') }}" required
               class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-200 focus:border-blue-500">
      </div>

      <div>
        <label class="block font-medium text-gray-700 mb-1">Хуудасны тоо</label>
        <input type="number" name="pages" value="{{ old('pages') }}"
               class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-200 focus:border-blue-500">
      </div>
    </div>

    <div>
      <label class="block font-medium text-gray-700 mb-1">Зураг</label>
      {{-- Хэрвээ хуучин зураг байгаа бол харуулах --}}
      @if(old('cover_image'))
        <div class="mb-3">
          <img src="{{ asset('storage/' . old('cover_image')) }}" class="w-32 h-32 object-cover rounded-lg shadow">
        </div>
      @endif
      <input type="file" name="cover_image" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-200 focus:border-blue-500">
    </div>

    <div>
      <label class="block font-medium text-gray-700 mb-1">Тайлбар</label>
      <textarea name="description" rows="4"
                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-200 focus:border-blue-500">{{ old('description') }}</textarea>
    </div>

    <div class="pt-4 flex justify-between">
      <a href="{{ route('admin.books.index') }}" class="text-gray-600 hover:underline">← Буцах</a>
      <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg shadow">Нэмэх</button>
    </div>
  </form>
</div>
@endsection