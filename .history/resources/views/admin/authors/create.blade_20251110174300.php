{{-- @extends('layouts.admin')

@section('content')
<div class="max-w-2xl mx-auto py-10">
    <h1 class="text-2xl font-bold mb-6">Зохиолч нэмэх</h1>
    <form method="POST" action="{{ route('admin.authors.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        <div>
            <label class="block mb-1 font-medium">Нэр</label>
            <input type="text" name="name" class="w-full border rounded px-3 py-2" value="{{ old('name') }}" required>
            @error('name')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
        </div>
        <div>
            <label class="block mb-1 font-medium">Slug</label>
            <input type="text" name="slug" class="w-full border rounded px-3 py-2" value="{{ old('slug') }}">
            @error('slug')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
        </div>
        <div>
            <label class="block mb-1 font-medium">Үндэс</label>
            <input type="text" name="nationality" class="w-full border rounded px-3 py-2" value="{{ old('nationality') }}">
            @error('nationality')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
        </div>
        <div>
            <label class="block mb-1 font-medium">Танилцуулга</label>
            <textarea name="biography" class="w-full border rounded px-3 py-2" rows="4">{{ old('biography') }}</textarea>
            @error('biography')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
        </div>
        <div>
            <label class="block mb-1 font-medium">Зураг</label>
            <input type="file" name="avatar" class="w-full border rounded px-3 py-2">
            @error('avatar')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Хадгалах</button>
        </div>
    </form>
</div>
@endsection --}}
