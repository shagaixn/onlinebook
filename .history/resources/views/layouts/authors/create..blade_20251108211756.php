@extends('layouts.sidebar')

@section('content')
<div class="max-w-3xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Шинэ автор үүсгэх</h1>

    @if($errors->any())
        <div class="mb-4 text-red-600">
            <ul>
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.authors.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4 bg-white dark:bg-slate-800 p-6 rounded-lg shadow">
        @csrf

        <div>
            <label class="block text-sm font-medium">Нэр</label>
            <input name="name" value="{{ old('name') }}" class="mt-1 block w-full border rounded px-3 py-2" required>
        </div>

        <div>
            <label class="block text-sm font-medium">овог </label>
            <input name="slug" value="{{ old('slug') }}" class="mt-1 block w-full border rounded px-3 py-2">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium">Төрсөн огноо</label>
                <input type="date" name="birth_date" value="{{ old('birth_date') }}" class="mt-1 block w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium">Төрсөн газар</label>
                <input name="birth_place" value="{{ old('birth_place') }}" class="mt-1 block w-full border rounded px-3 py-2">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium">Био</label>
            <textarea name="bio" rows="4" class="mt-1 block w-full border rounded px-3 py-2">{{ old('bio') }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium">Нийтлэсэн бүтээлүүд (мөр бүр)</label>
            <textarea name="notable_works_text" rows="3" class="mt-1 block w-full border rounded px-3 py-2">{{ old('notable_works_text') }}</textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium">Имэйл</label>
                <input name="email" value="{{ old('email') }}" class="mt-1 block w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium">Утас</label>
                <input name="phone" value="{{ old('phone') }}" class="mt-1 block w-full border rounded px-3 py-2">
            </div>
        </div>

       

        <div>
            <label class="block text-sm font-medium">Зураг (avatar)</label>
            <input type="file" name="avatar" id="avatar" class="mt-1 block" accept="image/*">
            <!-- preview: hidden by default, will be shown by JS when a file is selected -->
            <img id="avatarPreview" src="" alt="Avatar preview" class="mt-2 w-32 h-32 object-cover rounded hidden">
        </div>

        <div class="flex items-center gap-4">
            <!-- Ensure a value is always posted: 0 when unchecked, 1 when checked -->
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

    <!-- Client-side helpers: auto-slug from name, avatar preview, simple required check -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const nameInput = document.querySelector('input[name="name"]');
        const slugInput = document.querySelector('input[name="slug"]');
        const avatarInput = document.getElementById('avatar');
        const avatarPreview = document.getElementById('avatarPreview');
        const form = document.querySelector('form');

        function slugify(text){
            return text.toString().toLowerCase().trim()
                .replace(/[\s\_]+/g,'-')
                .replace(/[^\w\-]+/g,'')
                .replace(/\-\-+/g,'-')
                .replace(/^-+|-+$/g,'')
                ;
        }

        if (nameInput && slugInput) {
            nameInput.addEventListener('input', function(){
                // if user hasn't manually edited slug, keep it in sync
                if (!slugInput.dataset.manual) {
                    slugInput.value = slugify(this.value);
                }
            });
            slugInput.addEventListener('input', function(){
                // mark manual edits so automation stops
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
</div>
@endsection