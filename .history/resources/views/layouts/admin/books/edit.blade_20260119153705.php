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

        <div>
            <label class="block font-medium text-gray-700 mb-1">Ангилал (шууд бичих)</label>
            <input type="text" name="category" value="{{ old('category', $book->category ?? $book->categoryModel?->name) }}"
                   class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-200 focus:border-blue-500">
            <p class="text-xs text-gray-500 mt-1">Шинэ ангилал бол автоматаар үүснэ.</p>
        </div>
        
        @if($categories && $categories->count() > 0)
        <div>
            <label class="block font-medium text-gray-700 mb-2">Эсвэл сонгох (олон ангилал сонгож болно)</label>
            
            <!-- Шинэ категори нэмэх хэсэг -->
            <div class="mb-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                <div class="flex gap-2 items-center">
                    <input type="text" id="newCategoryInput" placeholder="Шинэ ангилалын нэр..."
                           class="flex-1 text-sm border-gray-300 rounded-lg shadow-sm focus:ring-blue-200 focus:border-blue-500">
                    <button type="button" onclick="addNewCategory()" 
                            class="px-3 py-1.5 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
                        + Нэмэх
                    </button>
                </div>
            </div>
            
            <div id="categoryCheckboxes" class="grid grid-cols-2 md:grid-cols-3 gap-2 max-h-48 overflow-y-auto border border-gray-200 rounded-lg p-3">
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
            <p class="text-xs text-gray-500 mt-1">Нэгээс олон ангилал сонгож болно. Эсвэл дээрх хэсэгт шинэ ангилал нэмнэ үү.</p>
        </div>
        @endif
            <div>
                <label class="block font-medium text-gray-700 mb-1">💰 Үнэ (₮)</label>
                <input type="number" name="price" value="{{ old('price', $book->price) }}"
                       class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-200 focus:border-blue-500">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block font-medium text-gray-700 mb-1">📅 Хэвлэгдсэн огноо</label>
                <input type="date" name="published_date" value="{{ old('published_date', $book->published_date) }}"
                       class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-200 focus:border-blue-500">
            </div>
            <div>
                <label class="block font-medium text-gray-700 mb-1">📄 Хуудасны тоо</label>
                <input type="number" name="pages" value="{{ old('pages', $book->pages) }}"
                       class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-200 focus:border-blue-500">
            </div>
        </div>

        <div>
            <label class="block font-medium text-gray-700 mb-1">🖼️ Номын зураг</label>
            @if ($book->cover_image)
                <div class="mb-3">
                    <img src="{{ asset('storage/' . $book->cover_image) }}" class="w-32 h-32 object-cover rounded-lg shadow">
                </div>
            @endif
            <input type="file" name="cover_image"
                   class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-200 focus:border-blue-500">
        </div>

        <div>
            <label class="block font-medium text-gray-700 mb-1">📝 Тайлбар</label>
            <textarea name="description" rows="4"
                      class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-200 focus:border-blue-500">{{ old('description', $book->description) }}</textarea>
        </div>

        <div class="flex justify-between pt-4">
            <a href="{{ route('admin.books.index') }}" class="text-gray-600 hover:text-gray-800">← Буцах</a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-xl shadow transition">
                Хадгалах
            </button>
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