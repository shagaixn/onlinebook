@extends('layouts.admin')

@section('title', 'Ном нэмэх')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-6 rounded-2xl shadow">
  <h1 class="text-2xl font-bold mb-6">📘 Ном нэмэх</h1>

  <form action="{{ route('admin.books.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
    @csrf

    <div>
      <label class="mb-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">Номын гарчиг</label>
      <input type="text" name="title" value="{{ old('title') }}" required
             class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-200 focus:border-blue-500">
    </div>

    <div class="mb-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
      <label class="block font-medium text-gray-700 mb-1">Зохиолчийн нэр</label>
      <input type="text" name="author_name" value="{{ old('author_name') }}" required
             class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-200 focus:border-blue-500">
      <p class="text-xs text-gray-500 mt-1">Шинэ зохиолч бол автоматаар бүртгэгдэнэ.</p>
    </div>

    <div class="mb-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
      <label class="block font-medium text-gray-700 mb-1">Ангилал 1 (үндсэн)</label>
      <input type="text" name="category" value="{{ old('category') }}"
             class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-200 focus:border-blue-500">
      <p class="text-xs text-gray-500 mt-1">Шинэ ангилал бол автоматаар үүснэ.</p>
    </div>

    <div class="mb-3 p-3 bg-green-50 border border-green-200 rounded-lg">
      <label class="block font-medium text-gray-700 mb-1">Ангилал 2 (нэмэлт)</label>
      <input type="text" name="category_2" value="{{ old('category_2') }}"
             class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-200 focus:border-green-500">
      <p class="text-xs text-gray-500 mt-1">Хоёр дахь ангилал (заавал биш).</p>
    </div>

    <div class="mb-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
      <label class="block font-medium text-gray-700 mb-1">Хэвлэгдсэн огноо</label>
      <input type="date" name="published_date" value="{{ old('published_date') }}"
             class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-200 focus:border-blue-500">
    </div>

    <div class="mb-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
      <label class="block font-medium text-gray-700 mb-1">Зураг</label>
      @if(old('cover_image'))
        <div class="mb-3">
          <img src="{{ asset('storage/' . old('cover_image')) }}" class="w-32 h-32 object-cover rounded-lg shadow">
        </div>
      @endif
      <input type="file" name="cover_image" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-200 focus:border-blue-500">
    </div>

    <div class="mb-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
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