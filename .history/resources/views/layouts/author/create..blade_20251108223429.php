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
            <label class="block text-sm font-medium">Slug</label>
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
            <label class="block text-sm font-medium">Товч танилцуулга</label>
            <textarea name="bio" rows="4" class="mt-1 block w-full border rounded px-3 py-2">{{ old('bio') }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium">Нийтлэсэн бүтээлүүд (комма/мөрөөр)</label>
            <textarea name="notable_works_text" rows="3" class="mt-1 block w-full border rounded px-3 py-2">{{ old('notable_works_text') }}</textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium">Үндэс</label>
                <input name="nationality" value="{{ old('nationality') }}" class="mt-1 block w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium">Нас барсан огноо</label>
                <input type="date" name="death_date" value="{{ old('death_date') }}" class="mt-1 block w-full border rounded px-3 py-2">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium">Зураг (avatar)</label>
            <input type="file" name="avatar" class="mt-1 block" accept="image/*">
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Хадгалах</button>
            <a href="{{ route('admin.authors.index') }}" class="text-gray-600">Буцах</a>
        </div>
    </form>
</div>
@endsection
