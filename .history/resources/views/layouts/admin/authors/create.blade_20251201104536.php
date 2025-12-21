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
    <form action="{{ route('admin.authors.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4 bg-white dark:bg-slate-800 p-6 rounded-lg shadow">
        @csrf
        <div>
            <label class="block text-sm font-medium">Нэр <span class="text-red-500">*</span></label>
            <input name="name" value="{{ old('name') }}" maxlength="255" required class="mt-1 block w-full border rounded px-3 py-2 @error('name') border-red-500 @enderror">
            @error('name') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium">Slug (URL)</label>
            <input name="slug" value="{{ old('slug') }}" maxlength="255" class="mt-1 block w-full border rounded px-3 py-2 @error('slug') border-red-500 @enderror" placeholder="john-doe">
            <p class="text-xs text-gray-500 mt-1">Хоосон бол автоматаар нэрнээс үүснэ. Зөв тэмдэгт: a-z, 0-9, -, _</p>
            @error('slug') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium">Төрсөн огноо</label>
                <input type="date" name="birth_date" value="{{ old('birth_date') }}" class="mt-1 block w-full border rounded px-3 py-2 @error('birth_date') border-red-500 @enderror">
                @error('birth_date') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium">Төрсөн газар</label>
                <input name="birth_place" value="{{ old('birth_place') }}" class="mt-1 block w-full border rounded px-3 py-2 @error('birth_place') border-red-500 @enderror">
                @error('birth_place') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium">Био</label>
            <textarea name="bio" rows="4" class="mt-1 block w-full border rounded px-3 py-2 @error('bio') border-red-500 @enderror">{{ old('bio') }}</textarea>
            @error('bio') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium">Нийтлэсэн бүтээлүүд (мөр бүр)</label>
            <textarea name="notable_works_text" rows="3" class="mt-1 block w-full border rounded px-3 py-2 @error('notable_works_text') border-red-500 @enderror" placeholder="Ном1&#10;Ном2">{{ old('notable_works_text') }}</textarea>
            <p class="text-xs text-gray-500 mt-1">Мөр тус бүр тусдаа бүтээл. Эсвэл JSON/комма ашиглаж болно.</p>
            @error('notable_works_text') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium">Зураг (avatar)</label>
            <input type="file" name="avatar" id="avatar" class="mt-1 block @error('avatar') border-red-500 @enderror" accept="image/*">
            @error('avatar') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            <img id="avatarPreview" src="" alt="Avatar preview" class="mt-2 w-32 h-32 object-cover rounded hidden border">
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