@include('include.header')
<form method="POST" action="{{ $action }}" enctype="multipart/form-data" class="space-y-4">
    @csrf
    @if($method ?? false)
        @method($method)
    @endif

    <div>
        <label class="block text-sm font-medium">Нэр</label>
        <input name="name" value="{{ old('name', $author->name ?? '') }}" class="mt-1 block w-full" required>
    </div>

    <div>
        <label class="block text-sm font-medium">Slug</label>
        <input name="slug" value="{{ old('slug', $author->slug ?? '') }}" class="mt-1 block w-full">
    </div>

    <div>
        <label class="block text-sm font-medium">Төрсөн огноо</label>
        <input type="date" name="birth_date" value="{{ old('birth_date', optional($author->birth_date)->format('Y-m-d') ?? '') }}" class="mt-1 block">
    </div>

    <div>
        <label class="block text-sm font-medium">Төрсөн газар</label>
        <input name="birth_place" value="{{ old('birth_place', $author->birth_place ?? '') }}" class="mt-1 block w-full">
    </div>

    <div>
        <label class="block text-sm font-medium">Био</label>
        <textarea name="bio" class="mt-1 block w-full" rows="4">{{ old('bio', $author->bio ?? '') }}</textarea>
    </div>

    <div>
        <label class="block text-sm font-medium">Нийтлэсэн бүтээлүүд (мөр бүр)</label>
        <textarea name="notable_works_text" class="mt-1 block w-full" rows="3">@if(old('notable_works_text')){{ old('notable_works_text') }}@elseif(!empty($author->notable_works)){{ implode("\n", $author->notable_works) }}@endif</textarea>
    </div>

    <div>
        <label class="block text-sm font-medium">Имэйл</label>
        <input name="email" value="{{ old('email', $author->email ?? '') }}" class="mt-1 block w-full">
    </div>

    <div>
        <label class="block text-sm font-medium">Утас</label>
        <input name="phone" value="{{ old('phone', $author->phone ?? '') }}" class="mt-1 block w-full">
    </div>

    <div>
        <label class="block text-sm font-medium">Вэб</label>
        <input name="website" value="{{ old('website', $author->website ?? '') }}" class="mt-1 block w-full">
    </div>

    <div>
        <label class="block text-sm font-medium">Зураг (avatar)</label>
        <input type="file" name="avatar" class="mt-1 block">
        @if(!empty($author->avatar))
            <img src="{{ asset('storage/' . $author->avatar) }}" class="w-24 h-24 mt-2 rounded-full object-cover">
        @endif
    </div>

    <div class="flex items-center gap-4">
        <label class="inline-flex items-center">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $author->is_active ?? true) ? 'checked' : '' }}>
            <span class="ml-2">Идэвхтэй</span>
        </label>
    </div>

    <div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Хадгалах</button>
        <a href="{{ route('admin.authors.index') }}" class="ml-2 text-gray-600">Буцах</a>
    </div>
</form>
@include('include.footer')
