@extends('layouts.sidebar')

@section('content')
<div class="p-6">
  <h1 class="text-3xl font-bold text-gray-800 mb-6">⚙️ Тохиргоо</h1>

  <!-- Profile Settings -->
  <div class="bg-white rounded-2xl shadow p-6 mb-8">
    <h2 class="text-xl font-semibold text-gray-700 mb-4">👤 Хувийн тохиргоо</h2>
    <form class="space-y-4">
      <div>
        <label class="block text-gray-600 font-medium mb-1">Нэр</label>
        <input type="text" placeholder="Админ нэр"
          class="w-full border-gray-300 rounded-xl shadow-sm focus:ring focus:ring-blue-200 focus:border-blue-500" />
      </div>
      <div>
        <label class="block text-gray-600 font-medium mb-1">Имэйл</label>
        <input type="email" placeholder="admin@example.com"
          class="w-full border-gray-300 rounded-xl shadow-sm focus:ring focus:ring-blue-200 focus:border-blue-500" />
      </div>
      <div>
        <label class="block text-gray-600 font-medium mb-1">Нууц үг солих</label>
        <input type="password" placeholder="Шинэ нууц үг"
          class="w-full border-gray-300 rounded-xl shadow-sm focus:ring focus:ring-blue-200 focus:border-blue-500" />
      </div>
      <div class="pt-3">
        <button type="submit"
          class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-xl shadow transition">Хадгалах</button>
      </div>
    </form>
  </div>

  <!-- System Settings -->
  <div class="bg-white rounded-2xl shadow p-6 mb-8">
    <h2 class="text-xl font-semibold text-gray-700 mb-4">💻 Системийн тохиргоо</h2>
    <form class="space-y-4">
      <div>
        <label class="block text-gray-600 font-medium mb-1">Вэбсайтын нэр</label>
        <input type="text" placeholder="Online Book Store"
          class="w-full border-gray-300 rounded-xl shadow-sm focus:ring focus:ring-blue-200 focus:border-blue-500" />
      </div>
      <div>
        <label class="block text-gray-600 font-medium mb-1">Хэл сонгох</label>
        <select
          class="w-full border-gray-300 rounded-xl shadow-sm focus:ring focus:ring-blue-200 focus:border-blue-500">
          <option>Монгол</option>
          <option>English</option>
        </select>
      </div>
      <div>
        <label class="block text-gray-600 font-medium mb-1">Theme</label>
        <select
          class="w-full border-gray-300 rounded-xl shadow-sm focus:ring focus:ring-blue-200 focus:border-blue-500">
          <option>Light</option>
          <option>Dark</option>
        </select>
      </div>
      <div class="pt-3">
        <button type="submit"
          class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-xl shadow transition">Шинэчлэх</button>
      </div>
    </form>
  </div>

  <!-- Notification Settings -->
  <div class="bg-white rounded-2xl shadow p-6">
    <h2 class="text-xl font-semibold text-gray-700 mb-4">🔔 Мэдэгдэл</h2>
    <div class="space-y-3">
      <label class="flex items-center space-x-3">
        <input type="checkbox" class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500" checked>
        <span class="text-gray-700">Шинэ хэрэглэгч бүртгэгдэхэд мэдэгдэл илгээх</span>
      </label>
      <label class="flex items-center space-x-3">
        <input type="checkbox" class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500">
        <span class="text-gray-700">Ном нэмэгдэхэд мэдэгдэл илгээх</span>
      </label>
      <label class="flex items-center space-x-3">
        <input type="checkbox" class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500">
        <span class="text-gray-700">Тохиргоо өөрчлөгдөхөд баталгаажуулалт илгээх</span>
      </label>
    </div>
    <div class="pt-4">
      <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-xl shadow transition">Хадгалах</button>
    </div>
  </div>
</div>
@endsection
