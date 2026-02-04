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
      <label class="block font-medium text-gray-700 mb-1">Ангилал (шууд бичих)</label>
      <input type="text" name="category" value="{{ old('category') }}"
             class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-200 focus:border-blue-500">
      <p class="text-xs text-gray-500 mt-1">Шинэ ангилал бол автоматаар үүснэ.</p>
    </div>

    <div class="mb-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
      <label class="block font-medium text-gray-700 mb-2">Нэмэлт ангилал</label>
      <div class="flex gap-2 mb-3">
        <input type="text" id="additionalCategoryInput" placeholder="Ангилалын нэр бичээд Enter дарна уу..."
               class="flex-1 text-sm border-gray-300 rounded-lg shadow-sm focus:ring-blue-200 focus:border-blue-500">
        <button type="button" onclick="addCategory()" 
                class="px-4 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition-colors">
          + Нэмэх
        </button>
      </div>
      <div id="categoriesList" class="flex flex-wrap gap-2 min-h-[40px] p-2 border border-gray-200 rounded-lg bg-white">
        <span class="text-sm text-gray-400" id="emptyMessage">Нэмэлт ангилал байхгүй</span>
      </div>
      <p class="text-xs text-gray-500 mt-1">Хэд хэдэн ангилал нэмж болно. Устгахын тулд tag дээр дарна уу.</p>
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

<script>
let categories = [];

function addCategory() {
  const input = document.getElementById('additionalCategoryInput');
  const categoryName = input.value.trim();
  
  if (!categoryName) {
    alert('Ангилалын нэр оруулна уу!');
    return;
  }
  
  // Check if already exists
  if (categories.includes(categoryName)) {
    alert('Энэ ангилал аль хэдийн нэмсэн байна!');
    return;
  }
  
  categories.push(categoryName);
  renderCategories();
  input.value = '';
  input.focus();
}

function removeCategory(index) {
  categories.splice(index, 1);
  renderCategories();
}

function renderCategories() {
  const container = document.getElementById('categoriesList');
  const emptyMessage = document.getElementById('emptyMessage');
  
  if (categories.length === 0) {
    emptyMessage.style.display = 'block';
    container.innerHTML = '<span class="text-sm text-gray-400" id="emptyMessage">Нэмэлт ангилал байхгүй</span>';
    return;
  }
  
  container.innerHTML = categories.map((cat, index) => `
    <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-100 text-blue-800 rounded-full text-sm font-medium hover:bg-blue-200 cursor-pointer transition-colors" 
          onclick="removeCategory(${index})"
          title="Устгахын тулд дарна уу">
      ${cat}
      <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
      </svg>
      <input type="hidden" name="additional_categories[]" value="${cat}">
    </span>
  `).join('');
}

// Enter key support
document.getElementById('additionalCategoryInput').addEventListener('keypress', function(e) {
  if (e.key === 'Enter') {
    e.preventDefault();
    addCategory();
  }
});

// Initialize with old values if validation fails
@if(old('additional_categories'))
  categories = @json(old('additional_categories'));
  renderCategories();
@endif
</script>

@endsection