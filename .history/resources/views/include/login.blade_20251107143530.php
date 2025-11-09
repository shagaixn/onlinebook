@include('include.header')

<div class="max-w-md mx-auto mt-16 bg-dark   dark:bg-slate-900 p-8 rounded shadow transition-colors duration-200 border border-transparent dark:border-slate-700">
    <h2 class="text-2xl font-bold mb-6 text-center text-gray-800 dark:text-white">Нэвтрэх</h2>
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-4">
            <label for="email" class="block mb-1 font-medium dark:text-gray-200">Имэйл</label>
            <input type="email" name="email" id="email" placeholder="name@example.com" autocomplete="username"
                   class="w-full border rounded px-3 py-2 bg-white dark:bg-slate-800 border-gray-300 dark:border-slate-700 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-800 transition-colors"
                   required autofocus>
            @error('email')
                <div class="text-red-500 text-sm dark:text-red-400">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-6">
            <label for="password" class="block mb-1 font-medium dark:text-gray-200">Нууц үг</label>
            <input type="password" name="password" id="password" placeholder="••••••••" autocomplete="current-password"
                   class="w-full border rounded px-3 py-2 bg-white dark:bg-slate-800 border-gray-300 dark:border-slate-700 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-800 transition-colors"
                   required>
            @error('password')
                <div class="text-red-500 text-sm dark:text-red-400">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="bg-blue-600 w-full py-2 text-white rounded font-bold hover:bg-blue-700 dark:bg-blue-600 dark:hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-800 transition-colors">Нэвтрэх</button>
        <div class="mt-4 text-center">
    <a href="{{ route('register') }}" class="text-blue-600 dark:text-blue-400 hover:underline">Бүртгүүлэх</a>
</div>
    </form>
    <div class="mt-8 flex justify-center gap-4">
    <!-- Facebook -->
    <a href="#" class="group relative w-12 h-12 flex items-center justify-center">
        <span class="absolute inset-0 border border-blue-500 dark:border-blue-400 rounded-lg group-hover:rotate-[-12deg] group-hover:skew-y-3 transition-transform duration-300"></span>
        <span class="text-blue-500 dark:text-blue-400 text-xl z-10"><i class="fab fa-facebook-f"></i></span>
    </a>
    <!-- Twitter -->
    <a href="#" class="group relative w-12 h-12 flex items-center justify-center">
        <span class="absolute inset-0 border border-sky-400 dark:border-sky-300 rounded-lg group-hover:rotate-[-12deg] group-hover:skew-y-3 transition-transform duration-300"></span>
        <span class="text-sky-400 dark:text-sky-300 text-xl z-10"><i class="fab fa-twitter"></i></span>
    </a>
    <!-- Instagram -->
    <a href="#" class="group relative w-12 h-12 flex items-center justify-center">
        <span class="absolute inset-0 border border-pink-500 dark:border-pink-400 rounded-lg group-hover:rotate-[-12deg] group-hover:skew-y-3 transition-transform duration-300"></span>
        <span class="text-pink-500 dark:text-pink-400 text-xl z-10"><i class="fab fa-instagram"></i></span>
    </a>
</div>
</div>


@include('include.footer')