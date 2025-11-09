@include('include.header')

<div class="max-w-2xl mx-auto mt-16 bg-white dark:bg-slate-900 p-10 rounded-2xl shadow-2xl">
    <h2 class="text-3xl font-bold mb-8 text-center dark:text-white">Профайл засах</h2>
    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-4">
            <label class="dark:text-gray-200">Нэр</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full p-2 rounded border dark:bg-slate-800 dark:border-slate-700 dark:text-gray-100">
        </div>
        <div class="mb-4">
            <label class="dark:text-gray-200">Зураг</label>
            <input type="file" name="profile_image" class="w-full p-2 rounded border dark:bg-slate-800 dark:border-slate-700 dark:text-gray-100">
        </div>
        <div class="mb-4">
            <label class="dark:text-gray-200">Хүйс</label>
            <input type="text" name="gender" value="{{ old('gender', $user->gender) }}" class="w-full p-2 rounded border dark:bg-slate-800 dark:border-slate-700 dark:text-gray-100">
        </div>
        <div class="mb-4">
            <label class="dark:text-gray-200">Нас</label>
            <input type="number" name="age" value="{{ old('age', $user->age) }}" class="w-full p-2 rounded border dark:bg-slate-800 dark:border-slate-700 dark:text-gray-100">
        </div>
        <div class="mb-4">
            <label class="dark:text-gray-200">Утас</label>
            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full p-2 rounded border dark:bg-slate-800 dark:border-slate-700 dark:text-gray-100">
        </div>
        <div class="mb-4">
            <label class="dark:text-gray-200">Хаяг</label>
            <input type="text" name="address" value="{{ old('address', $user->address) }}" class="w-full p-2 rounded border dark:bg-slate-800 dark:border-slate-700 dark:text-gray-100">
        </div>
        <div class="mb-4">
            <label class="dark:text-gray-200">Танилцуулга</label>
            <textarea name="bio" class="w-full p-2 rounded border dark:bg-slate-800 dark:border-slate-700 dark:text-gray-100">{{ old('bio', $user->bio) }}</textarea>
        </div>
        <div class="mb-4">
            <label class="dark:text-gray-200">Сонирхол</label>
            <input type="text" name="interests" value="{{ old('interests', $user->interests) }}" class="w-full p-2 rounded border dark:bg-slate-800 dark:border-slate-700 dark:text-gray-100">
        </div>
        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Хадгалах</button>
    </form>
</div>

@include('include.footer')
