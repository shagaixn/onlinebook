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
      <label class="block font-medium text-gray-700 mb-1">Зохиолчийн нэр</label>
      <input type="text" name="author_name" value="{{ old('author_name') }}" required
             class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-200 focus:border-blue-500">
      <p class="text-xs text-gray-500 mt-1">Шинэ зохиолч бол автоматаар бүртгэгдэнэ.</p>
    </div>

    <div>
      <label class="block font-medium text-gray-700 mb-1">Ангилал</label>
      <input type="text" name="category" value="{{ old('category') }}"
            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-200 focus:border-blue-500">
          <p class="text-xs text-gray-500 mt-1">Ангиллын нэрийг шууд бичнэ үү (шинэ нэр бол автоматаар үүснэ).</p>
    </div>
    @if($categories->count() > 0)
    <div>
      <label class="block font-medium text-gray-700 mb-2">Эсвэл сонгох (олон ангилал сонгож болно)</label>
      <div class="grid grid-cols-2 md:grid-cols-3 gap-2 max-h-48 overflow-y-auto border border-gray-200 rounded-lg p-3">
        @foreach($categories as $cat)
        <label class="flex items-center gap-2 text-sm hover:bg-gray-50 p-2 rounded cursor-pointer">
          <input type="checkbox" name="category_ids[]" value="{{ $cat->id }}" 
                {{ in_array($cat->id, old('category_ids', [])) ? 'checked' : '' }}
              class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
          <span>{{ $cat->name }}</span>
        </label>
        @endforeach
      </div>
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