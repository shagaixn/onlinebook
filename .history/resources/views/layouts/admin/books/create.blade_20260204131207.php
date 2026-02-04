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
let tempCategoryId = -1; // Temporary ID for new categories

function addNewCategory() {
  const input = document.getElementById('newCategoryInput');
  const categoryName = input.value.trim();
  
  if (!categoryName) {
    alert('Ангилалын нэрийг оруулна уу!');
    return;
  }
  
  // Check if category already exists
  const existingCategories = document.querySelectorAll('#categoryCheckboxes span');
  for (let span of existingCategories) {
    if (span.textContent.toLowerCase() === categoryName.toLowerCase()) {
      alert('Энэ ангилал аль хэдийн байна!');
      return;
    }
  }
  
  // Create new checkbox for the category
  const categoryContainer = document.getElementById('categoryCheckboxes');
  const newLabel = document.createElement('label');
  newLabel.className = 'flex items-center gap-2 text-sm hover:bg-gray-50 p-2 rounded cursor-pointer bg-green-50 border border-green-200';
  
  newLabel.innerHTML = `
    <input type="checkbox" name="category_ids[]" value="${tempCategoryId}" checked
           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
    <span>${categoryName}</span>
    <span class="text-xs text-green-600">(шинэ)</span>
  `;
  
  // Add hidden input for new category name
  const hiddenInput = document.createElement('input');
  hiddenInput.type = 'hidden';
  hiddenInput.name = 'new_categories[]';
  hiddenInput.value = categoryName;
  newLabel.appendChild(hiddenInput);
  
  categoryContainer.appendChild(newLabel);
  
  // Clear input and decrease temp ID
  input.value = '';
  tempCategoryId--;
}

// Enter key support
document.getElementById('newCategoryInput').addEventListener('keypress', function(e) {
  if (e.key === 'Enter') {
    e.preventDefault();
    addNewCategory();
  }
});
</script>
@endsection