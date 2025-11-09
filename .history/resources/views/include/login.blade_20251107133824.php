@include('include.header')

<div class="max-w-md mx-auto mt-16 bg-white dark:bg-slate-900 p-8 rounded shadow transition-colors duration-200 border border-transparent dark:border-slate-700">
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
// ...existing code...
  <!-- 🌓 No-flash theme setup -->
  <script id="theme-init">
    (function () {
      try {
        function getCookie(name) {
          const v = document.cookie.match('(^|;)\\s*' + name + '\\s*=\\s*([^;]+)');
          return v ? decodeURIComponent(v[2]) : null;
        }
        const cookieTheme = getCookie('theme');
        let saved = null;
        try { saved = cookieTheme || localStorage.getItem('theme'); } catch (_) { saved = cookieTheme || null; }
        const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
        const useDark = (saved === 'dark') || (!saved && prefersDark);
        if (useDark) {
          document.documentElement.classList.add('dark');
          document.documentElement.setAttribute('data-theme', 'dark');
        } else {
          document.documentElement.classList.remove('dark');
          document.documentElement.setAttribute('data-theme', 'light');
        }
      } catch (e) { /* silent */ }
    })();
  </script>
...
  <script>
    (function () {
      const toggle = document.getElementById('theme-toggle');
      if (!toggle) return;
      const root = document.documentElement;

      function setCookie(name, value, days) {
        try {
          if (value === null) {
            document.cookie = name + '=;path=/;max-age=0';
            return;
          }
          const maxAge = days ? (60*60*24*days) : (60*60*24*365);
          document.cookie = name + '=' + encodeURIComponent(value) + ';path=/;max-age=' + maxAge + ';SameSite=Lax';
        } catch (_) {}
      }

      function updateToggleUI(isDark) {
        try {
          const moon = toggle.querySelector('.fa-moon');
          const sun = toggle.querySelector('.fa-sun');
          if (moon) moon.classList.toggle('hidden', isDark);
          if (sun) sun.classList.toggle('hidden', !isDark);
          toggle.setAttribute('aria-pressed', isDark ? 'true' : 'false');
        } catch (_) {}
      }

      function setTheme(mode, doReload = true) {
        if (mode === 'dark') {
          root.classList.add('dark');
          root.setAttribute('data-theme', 'dark');
          try { localStorage.setItem('theme', 'dark'); } catch (_) {}
          setCookie('theme', 'dark', 365);
          updateToggleUI(true);
        } else {
          root.classList.remove('dark');
          root.setAttribute('data-theme', 'light');
          try { localStorage.setItem('theme', 'light'); } catch (_) {}
          // remove cookie so server can fallback to preference/light
          setCookie('theme', null);
          updateToggleUI(false);
        }
        if (doReload) {
          // reload so server-side Blade will apply the cookie-based <html class="dark"> on next render
          location.reload();
        }
      }

      // initialize toggle UI on load
      updateToggleUI(root.classList.contains('dark'));

      toggle.addEventListener('click', (e) => {
        const isDark = root.classList.contains('dark');
        // by default reload so all pages / server-render reflect new theme
        setTheme(isDark ? 'light' : 'dark', /* doReload= */ true);
      });
    })();
  </script>
...existing code...

@include('include.footer')