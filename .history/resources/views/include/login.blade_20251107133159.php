@extends('layouts.app')

@section('title', 'Нэвтрэх')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 bg-gradient-to-b from-gray-50 to-white dark:from-slate-950 dark:to-slate-900 transition-colors duration-500">
  <div class="max-w-md mx-auto mt-16 bg-white dark:bg-slate-900 p-8 rounded shadow transition-colors duration-200 border border-transparent dark:border-slate-800">
    
    <!-- Гарчиг -->
    <h2 class="text-2xl font-bold mb-6 text-center dark:text-white">
      Mbook-д нэвтрэх
    </h2>
    <p class="text-center text-gray-500 dark:text-gray-400 mb-8">
      Тавтай морил! Нэвтэрч номын ертөнцөд оръё.
    </p>

    <!-- Login form -->
    <form method="POST" action="{{ route('login') }}" novalidate>
         @csrf
         <div class="mb-4">
             <label for="email" class="block mb-1 font-medium dark:text-gray-200">Имэйл</label>
-            <input type="email" name="email" id="email" class="w-full border rounded px-3 py-2 dark:bg-slate-800 dark:border-slate-700 dark:text-gray-100" required autofocus>
-            @error('email')
-                <div class="text-red-500 text-sm">{{ $message }}</div>
-            @enderror
+            <input
+                type="email"
+                name="email"
+                id="email"
+                placeholder="name@example.com"
+                autocomplete="username"
+                aria-describedby="@error('email') email-error @enderror"
+                class="w-full border rounded px-3 py-2 bg-white dark:bg-slate-800 dark:border-slate-700 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-800"
+                required
+                autofocus
+            >
+            @error('email')
+                <div id="email-error" class="text-red-500 text-sm dark:text-red-400 mt-1">{{ $message }}</div>
+            @enderror
         </div>
         <div class="mb-6">
             <label for="password" class="block mb-1 font-medium dark:text-gray-200">Нууц үг</label>
-            <input type="password" name="password" id="password" class="w-full border rounded px-3 py-2 dark:bg-slate-800 dark:border-slate-700 dark:text-gray-100" required>
-            @error('password')
-                <div class="text-red-500 text-sm">{{ $message }}</div>
-            @enderror
+            <div class="relative">
+                <input
+                    type="password"
+                    name="password"
+                    id="password"
+                    placeholder="••••••••"
+                    autocomplete="current-password"
+                    aria-describedby="@error('password') password-error @enderror"
+                    class="w-full border rounded px-3 py-2 bg-white dark:bg-slate-800 dark:border-slate-700 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-800"
+                    required
+                >
+                <button type="button" id="pw-toggle" aria-label="Show password" class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-300 hover:text-gray-700 dark:hover:text-gray-100 focus:outline-none">
+                    <i class="fa-regular fa-eye" id="pw-icon"></i>
+                </button>
+            </div>
+            @error('password')
+                <div id="password-error" class="text-red-500 text-sm dark:text-red-400 mt-1">{{ $message }}</div>
+            @enderror
+            <div class="flex items-center justify-between mt-3">
+                <label class="inline-flex items-center text-sm text-gray-700 dark:text-gray-300">
+                    <input type="checkbox" name="remember" id="remember" class="form-checkbox h-4 w-4 text-blue-600 rounded focus:ring-blue-300 dark:focus:ring-blue-800" {{ old('remember') ? 'checked' : '' }}>
+                    <span class="ml-2">Намайг сана</span>
+                </label>
+                <a href="{{ route('password.request') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">Нууц үг мартсан?</a>
+            </div>
         </div>
-        <button type="submit" class="bg-blue-600 w-full py-2 text-white rounded font-bold hover:bg-blue-700">Нэвтрэх</button>
+        <button type="submit" class="bg-blue-600 w-full py-2 text-white rounded font-bold hover:bg-blue-700 dark:bg-blue-600 dark:hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-800 transition-colors">Нэвтрэх</button>
         <div class="mt-4 text-center">
-    <a href="{{ route('register') }}" class="text-blue-600 dark:text-blue-400 hover:underline">Бүртгүүлэх</a>
-</div>
+    <a href="{{ route('register') }}" class="text-blue-600 dark:text-blue-400 hover:underline transition-colors">Бүртгүүлэх</a>
+</div>
     </form>
     <div class="mt-8 flex justify-center gap-4">
     <!-- Facebook -->
     <a href="#" class="group relative w-12 h-12 flex items-center justify-center">
         <span class="absolute inset-0 border border-blue-500 rounded-lg group-hover:rotate-[-12deg] group-hover:skew-y-3 transition-transform duration-300"></span>
         <span class="text-blue-500 text-xl z-10"><i class="fab fa-facebook-f"></i></span>
     </a>
     <!-- Twitter -->
     <a href="#" class="group relative w-12 h-12 flex items-center justify-center">
         <span class="absolute inset-0 border border-sky-400 rounded-lg group-hover:rotate-[-12deg] group-hover:skew-y-3 transition-transform duration-300"></span>
         <span class="text-sky-400 text-xl z-10"><i class="fab fa-twitter"></i></span>
     </a>
     <!-- Instagram -->
     <a href="#" class="group relative w-12 h-12 flex items-center justify-center">
         <span class="absolute inset-0 border border-pink-500 rounded-lg group-hover:rotate-[-12deg] group-hover:skew-y-3 transition-transform duration-300"></span>
         <span class="text-pink-500 text-xl z-10"><i class="fab fa-instagram"></i></span>
     </a>
   </div>
 </div>
+
+<!-- Password toggle script -->
+<script>
+  (function () {
+    const pwToggle = document.getElementById('pw-toggle');
+    const pwInput = document.getElementById('password');
+    const pwIcon = document.getElementById('pw-icon');
+    if (!pwToggle || !pwInput || !pwIcon) return;
+    pwToggle.addEventListener('click', () => {
+      if (pwInput.type === 'password') {
+        pwInput.type = 'text';
+        pwIcon.classList.remove('fa-eye'); pwIcon.classList.add('fa-eye-slash');
+        pwToggle.setAttribute('aria-label', 'Hide password');
+      } else {
+        pwInput.type = 'password';
+        pwIcon.classList.remove('fa-eye-slash'); pwIcon.classList.add('fa-eye');
+        pwToggle.setAttribute('aria-label', 'Show password');
+      }
+    });
+  })();
+</script>

@include('include.footer')
@endsection
