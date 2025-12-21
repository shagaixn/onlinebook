@extends('layouts.sidebar')

@section('content')
<div class="max-w-3xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">>Шинэ зохиолч нэмэх</h1>
    @if($errors->any())
        <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 rounded">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('admin.authors.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6 bg-white dark:bg-slate-800 p-6 rounded-lg shadow">
        @csrf
        {{-- Хувийн мэдээлэл --}}
        <div class="border-b pb-4 mb-4">
            <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-3">👤 Хувийн мэдээлэл</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium">Төрсөн огноо</label>
                    <input type="date" name="birth_date" value="{{ old('birth_date') }}" class="mt-1 block w-full border rounded px-3 py-2 @error('birth_date') border-red-500 @enderror">
                    @error('birth_date') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium">Нас барсан огноо</label>
                    <input type="date" name="death_date" value="{{ old('death_date') }}" class="mt-1 block w-full border rounded px-3 py-2 @error('death_date') border-red-500 @enderror">
                    @error('death_date') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium">Төрсөн газар</label>
                    <input name="birth_place" value="{{ old('birth_place') }}" class="mt-1 block w-full border rounded px-3 py-2 @error('birth_place') border-red-500 @enderror" placeholder="Улаанбаатар, Монгол">
                    @error('birth_place') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium">Үндэс</label>
                    <input name="nationality" value="{{ old('nationality') }}" class="mt-1 block w-full border rounded px-3 py-2 @error('nationality') border-red-500 @enderror" placeholder="Монгол">
                    @error('nationality') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
            {{-- Үндсэн мэдээлэл --}}
        <div class="border-b pb-4 mb-4">
            <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-3">📝 Үндсэн мэдээлэл</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium">Нэр <span class="text-red-500">*</span></label>
                    <input name="name" value="{{ old('name') }}" maxlength="255" required class="mt-1 block w-full border rounded px-3 py-2 @error('name') border-red-500 @enderror">
                    @error('name') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>
      
      
       {{-- Намтар, бүтээлүүд --}}
        <div class="border-b pb-4 mb-4">
            <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-3">📚 Намтар & Бүтээлүүд</h2>
            <div>
 <label class="block text-sm font-medium">Товч намтар</label>
                <textarea name="bio" rows="4" class="mt-1 block w-full border rounded px-3 py-2 @error('bio') border-red-500 @enderror" placeholder="Зохиолчийн намтар, товч танилцуулга...">{{ old('bio') }}</textarea>
                @error('bio') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <div>
                    <label class="block text-sm font-medium">Алдартай бүтээлүүд</label>
                    <textarea name="notable_works_text" rows="4" class="mt-1 block w-full border rounded px-3 py-2 @error('notable_works_text') border-red-500 @enderror" placeholder="Ном1&#10;Ном2&#10;Ном3">{{ old('notable_works_text') }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">Мөр бүр тусдаа бүтээл</p>
                    @error('notable_works_text') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium">Шагнал, цол</label>
                    <textarea name="awards_text" rows="4" class="mt-1 block w-full border rounded px-3 py-2 @error('awards_text') border-red-500 @enderror" placeholder="Шагнал1&#10;Шагнал2">{{ old('awards_text') }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">Мөр бүр тусдаа шагнал</p>
                    @error('awards_text') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
               {{-- Профайл зураг --}}
        <div class="border-b pb-4 mb-4">
            <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-3">🖼️ Профайл зураг</h2>
            <div class="flex items-start gap-6">
                <div class="flex-shrink-0">
                    <div id="avatarPreviewContainer" class="w-32 h-32 rounded-lg border-2 border-dashed border-gray-300 dark:border-gray-600 flex items-center justify-center bg-gray-50 dark:bg-slate-700 overflow-hidden">
                        <img id="avatarPreview" src="" alt="Preview" class="w-full h-full object-cover hidden">
                        <span id="avatarPlaceholder" class="text-gray-400 text-sm text-center px-2">Зураг сонгоно уу</span>
                    </div>
                </div>
                <div class="flex-1">
                    <input type="file" name="avatar" id="avatar" class="block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" accept="image/*">
                    <p class="text-xs text-gray-500 mt-2">PNG, JPG, GIF — хамгийн ихдээ 2MB</p>
                    @error('avatar') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
         {{-- Холбоо барих --}}
        <div class="border-b pb-4 mb-4">
            <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-3">📧 Холбоо барих</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium">Имэйл хаяг</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="mt-1 block w-full border rounded px-3 py-2 @error('email') border-red-500 @enderror" placeholder="author@example.com">
                    @error('email') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium">Вэбсайт</label>
                    <input type="url" name="social_website" value="{{ old('social_website') }}" class="mt-1 block w-full border rounded px-3 py-2 @error('social_website') border-red-500 @enderror" placeholder="https://example.com">
                    @error('social_website') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>
        
        <div class="flex items-center gap-4">
            <input type="hidden" name="is_active" value="0">
            <label class="inline-flex items-center">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                <span class="ml-2">Идэвхтэй</span>
            </label>
        </div>
        <div class="flex items-center gap-4">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Хадгалах</button>
            <a href="{{ route('admin.authors.index') }}" class="text-gray-600">Буцах</a>
        </div>
    </form>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form[action="{{ route('admin.authors.store') }}"]');
    const nameInput = form ? form.querySelector('input[name="name"]') : null;
    const slugInput = form ? form.querySelector('input[name="slug"]') : null;
    const avatarInput = document.getElementById('avatar');
    const avatarPreview = document.getElementById('avatarPreview');
    function slugify(text){
        return text.toString().toLowerCase().trim()
            .replace(/[\s\_]+/g,'-')
            .replace(/[^\w\-]+/g,'')
            .replace(/\-\-+/g,'-')
            .replace(/^-+|-+$/g,'');
    }
    if (nameInput && slugInput) {
        nameInput.addEventListener('input', function(){
            if (!slugInput.dataset.manual) {
                slugInput.value = slugify(this.value);
            }
        });
        slugInput.addEventListener('input', function(){
            this.dataset.manual = '1';
        });
    }
    if (avatarInput && avatarPreview) {
        avatarInput.addEventListener('change', function(){
            const file = this.files && this.files[0];
            if (!file) {
                avatarPreview.src = '';
                avatarPreview.classList.add('hidden');
                return;
            }
            const url = URL.createObjectURL(file);
            avatarPreview.src = url;
            avatarPreview.classList.remove('hidden');
        });
    }
    if (form && nameInput) {
        form.addEventListener('submit', function(e){
            if (!nameInput.value.trim()) {
                e.preventDefault();
                alert('Нэр оруулна уу.');
                nameInput.focus();
            }
        });
    }
});
</script>
@endsection