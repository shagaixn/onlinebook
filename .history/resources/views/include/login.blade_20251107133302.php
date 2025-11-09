@extends('')

@section('title', 'Нэвтрэх')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 bg-gradient-to-b from-gray-50 to-white dark:from-slate-950 dark:to-slate-900 transition-colors duration-500">
  <div class="w-full max-w-md bg-white/70 dark:bg-slate-900/80 backdrop-blur-lg rounded-2xl shadow-xl p-8 border border-gray-100 dark:border-slate-700">
    
    <!-- Гарчиг -->
    <h2 class="text-3xl font-bold mb-6 text-center text-gray-800 dark:text-white">
      Mbook-д нэвтрэх
    </h2>
    <p class="text-center text-gray-500 dark:text-gray-400 mb-8">
      Тавтай морил! Нэвтэрч номын ертөнцөд оръё.
    </p>

    <!-- Login form -->
    <form method="POST" action="{{ route('login') }}" class="space-y-5">
      @csrf

      <!-- Имэйл -->
      <div>
        <label for="email" class="block mb-1 font-medium text-gray-700 dark:text-gray-200">Имэйл</label>
        <input
          type="email"
          name="email"
          id="email"
          placeholder="name@example.com"
          class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-slate-700 dark:bg-slate-800 dark:text-gray-100 focus:border-blue-500 focus:ring focus:ring-blue-200 dark:focus:ring-blue-800 outline-none transition-all duration-200"
          required
          autofocus
        >
        @error('email')
          <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
      </div>

      <!-- Нууц үг -->
      <div>
        <label for="password" class="block mb-1 font-medium text-gray-700 dark:text-gray-200">Нууц үг</label>
        <input
          type="password"
          name="password"
          id="password"
          placeholder="••••••••"
          class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-slate-700 dark:bg-slate-800 dark:text-gray-100 focus:border-blue-500 focus:ring focus:ring-blue-200 dark:focus:ring-blue-800 outline-none transition-all duration-200"
          required
        >
        @error('password')
          <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
      </div>

      <!-- Submit -->
      <button
        type="submit"
        class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow transition-all duration-200"
      >
        Нэвтрэх
      </button>
    </form>

    <!-- Register link -->
    <div class="mt-6 text-center">
      <p class="text-gray-600 dark:text-gray-400">
        Шинэ хэрэглэгч үү?
        <a href="{{ route('register') }}" class="text-blue-600 dark:text-blue-400 hover:underline font-medium">
          Бүртгүүлэх
        </a>
      </p>
    </div>

    <!-- Social login -->
    <div class="mt-8">
      <p class="text-center text-gray-500 dark:text-gray-400 mb-4">— эсвэл —</p>
      <div class="flex justify-center gap-4">
        <!-- Facebook -->
        <a href="#" class="group relative w-12 h-12 flex items-center justify-center rounded-xl border border-blue-500 hover:bg-blue-500 transition-all duration-300">
          <i class="fab fa-facebook-f text-blue-500 group-hover:text-white text-lg"></i>
        </a>
        <!-- Twitter -->
        <a href="#" class="group relative w-12 h-12 flex items-center justify-center rounded-xl border border-sky-400 hover:bg-sky-400 transition-all duration-300">
          <i class="fab fa-twitter text-sky-400 group-hover:text-white text-lg"></i>
        </a>
        <!-- Instagram -->
        <a href="#" class="group relative w-12 h-12 flex items-center justify-center rounded-xl border border-pink-500 hover:bg-pink-500 transition-all duration-300">
          <i class="fab fa-instagram text-pink-500 group-hover:text-white text-lg"></i>
        </a>
      </div>
    </div>

  </div>
</div>
@endsection
