@extends('include.header')
@section('content')
<div class="max-w-md mx-auto mt-16 bg-white dark:bg-slate-900 p-8 rounded-2xl shadow-2xl">
    <h2 class="text-2xl font-bold mb-6 text-center">Нууц үг солих</h2>
    <form action="{{ route('profile.changePassword') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label>Одоогийн нууц үг</label>
            <input type="password" name="current_password" required class="w-full p-2 rounded border">
        </div>
        <div class="mb-4">
            <label>Шинэ нууц үг</label>
            <input type="password" name="new_password" required class="w-full p-2 rounded border">
        </div>
        <div class="mb-4">
            <label>Шинэ нууц үг давтах</label>
            <input type="password" name="new_password_confirmation" required class="w-full p-2 rounded border">
        </div>
        <button type="submit" class="bg-gray-700 text-white px-6 py-2 rounded">Солих</button>
    </form>
</div>
@endsection
