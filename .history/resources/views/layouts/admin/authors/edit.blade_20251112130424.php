@extends('layouts.sidebar')

@section('content')
<div class="max-w-3xl mx-auto p-6">
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
    <form action="{{ route('admin.authors.update', $author->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4 bg-white dark:bg-slate-800 p-6 rounded-lg shadow">
        @csrf
        @method('PUT')
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Нэр <span class="text-red-500">*</span></label>
            <input type="text" name="name" value="{{ old('name', $author->name) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Slug</label>
            <input type="text" name="slug" value="{{ old('slug', $author->slug) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Жишээ: john-doe">
            <p class="text-xs text-gray-500 mt-1">Хоосон бол автоматаар нэрнээс үүснэ. Зөв тэмдэгт: a-z, 0-9, -, _</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Төрсөн огноо</label>
                <input type="date" name="birth_date" value="{{ old('birth_date', $author->birth_date ? \Carbon\Carbon::parse($author->birth_date)->format('Y-m-d') : '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Төрсөн газар</label>
                <input type="text" name="birth_place" value="{{ old('birth_place', $author->birth_place) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Овог / Албан тушаал</label>
            <input type="text" name="position" value="{{ old('position', $author->position) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Улс</label>
            <input type="text" name="country" value="{{ old('country', $author->country) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Товч танилцуулга</label>
            <textarea name="bio" rows="5" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ old('bio', $author->bio) }}</textarea>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ном/Ажил</label>
            <input type="text" name="notable_works" value="{{ old('notable_works', is_array($author->notable_works) ? implode(', ', $author->notable_works) : ($author->notable_works ?? '')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Коммагаар тусгаарласан: Ном1, Ном2">
            <p class="text-xs text-gray-500 mt-1">Жишээ: "Book A, Book B" — эсвэл JSON массив дамжуулж болно.</p>
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
