@extends('layouts.sidebar')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">✏️ Зохиолч засах</h1>
    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded">
            <strong>Алдаа:</strong>
            <ul class="mt-2 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
     <form action="{{ route('admin.authors.update', $author->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6 bg-white dark:bg-slate-800 p-6 rounded-lg shadow">
        @csrf
        @method('PUT')
         {{-- Үндсэн мэдээлэл --}}
        <div class="border-b pb-4 mb-4">
            <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-3">📝 Үндсэн мэдээлэл</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Нэр <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $author->name) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Slug (URL)</label>
                    <input type="text" name="slug" value="{{ old('slug', $author->slug) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="john-doe">
                    <p class="text-xs text-gray-500 mt-1">Хоосон бол автоматаар нэрнээс үүснэ</p>
                </div>
            </div>
                {{-- Хувийн мэдээлэл --}}
        <div class="border-b pb-4 mb-4">
            <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-3">👤 Хувийн мэдээлэл</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Төрсөн огноо</label>
                    <input type="date" name="birth_date" value="{{ old('birth_date', $author->birth_date ? \Carbon\Carbon::parse($author->birth_date)->format('Y-m-d') : '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Нас барсан огноо</label>
                    <input type="date" name="death_date" value="{{ old('death_date', $author->death_date ? \Carbon\Carbon::parse($author->death_date)->format('Y-m-d') : '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Төрсөн газар</label>
                    <input type="text" name="birth_place" value="{{ old('birth_place', $author->birth_place) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Улаанбаатар, Монгол">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Үндэс</label>
                    <input type="text" name="nationality" value="{{ old('nationality', $author->nationality) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Монгол">
                </div>
                 <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Алдартай бүтээлүүд</label>
                    <textarea name="notable_works_text" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Ном1&#10;Ном2&#10;Ном3">{{ old('notable_works_text', $author->notable_works) }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">Мөр бүр тусдаа бүтээл</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Шагнал, цол</label>
                    <textarea name="awards_text" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Шагнал1&#10;Шагнал2">{{ old('awards_text', $author->awards) }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">Мөр бүр тусдаа шагнал</p>
                </div>
            </div>
                
               {{-- Холбоо барих --}}
        <div class="border-b pb-4 mb-4">
            <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-3">📧 Холбоо барих</h2>
            @php
                $socialLinks = $author->social_links ?? [];
            @endphp
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Имэйл хаяг</label>
                    <input type="email" name="email" value="{{ old('email', $author->email) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="author@example.com">
                </div>
        </div>
        
        
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Avatar</label>
            <div class="flex items-center gap-4 mt-2">
                <div class="w-20 h-20 rounded-full overflow-hidden border">
                    <img src="{{ $author->avatar ? Storage::disk('public')->url($author->avatar) : 'https://cdn-icons-png.flaticon.com/512/149/149071.png' }}" alt="avatar" class="w-full h-full object-cover">
                </div>
                <div class="flex-1">
                    <input type="file" name="avatar" accept="image/*" class="block w-full text-sm text-gray-600">
                    <p class="text-xs text-gray-500 mt-1">Шаардлагагүй. Шаардлагатай бол шинэ зураг оруулна.</p>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <label class="inline-flex items-center">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $author->is_active) ? 'checked' : '' }} class="form-checkbox h-4 w-4 text-blue-600">
                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Идэвхтэй</span>
            </label>
            <div class="ml-auto flex items-center gap-2">
                <a href="{{ route('admin.authors.index') }}" class="px-4 py-2 rounded-md bg-gray-200 hover:bg-gray-300 text-sm">Цуцлах</a>
                <button type="submit" class="px-4 py-2 rounded-md bg-blue-600 hover:bg-blue-700 text-white text-sm">Хадгалах</button>
            </div>
        </div>
    </form>
</div>
@endsection
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form[action="{{ route('admin.authors.update', $author->id) }}"]');
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