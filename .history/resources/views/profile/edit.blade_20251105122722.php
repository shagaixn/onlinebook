@extends('include.header')
@section('content')
<div class="max-w-2xl mx-auto mt-16 bg-white dark:bg-slate-900 p-10 rounded-2xl shadow-2xl">
    <h2 class="text-3xl font-bold mb-8 text-center">Профайл засах</h2>
    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-4">
            <label>Нэр</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full p-2 rounded border">
        </div>
        <div class="mb-4">
            <label>Зураг</label>
            <input type="file" name="profile_image" class="w-full p-2 rounded border">
        </div>
        <div class="mb-4">
            <label>Хүйс</label>
            <input type="text" name="gender" value="{{ old('gender', $user->gender) }}" class="w-full p-2 rounded border">
        </div>
        <div class="mb-4">
            <label>Нас</label>
            <input type="number" name="age" value="{{ old('age', $user->age) }}" class="w-full p-2 rounded border">
        </div>
        <div class="mb-4">
            <label>Утас</label>
            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full p-2 rounded border">
        </div>
        <div class="mb-4">
            <label>Хаяг</label>
            <input type="text" name="address" value="{{ old('address', $user->address) }}" class="w-full p-2 rounded border">
        </div>
        <div class="mb-4">
            <label>Танилцуулга</label>
            <textarea name="bio" class="w-full p-2 rounded border">{{ old('bio', $user->bio) }}</textarea>
        </div>
        <div class="mb-4">
            <label>Сонирхол</label>
            <input type="text" name="interests" value="{{ old('interests', $user->interests) }}" class="w-full p-2 rounded border">
        </div>
        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded">Хадгалах</button>
    </form>
</div>
@endsection
