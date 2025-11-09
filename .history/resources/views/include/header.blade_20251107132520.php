<!DOCTYPE html>
<html lang="mn" class="@if ( (isset($_COOKIE['theme']) && $_COOKIE['theme']=='dark') || (!isset($_COOKIE['theme']) && request()->cookie('theme', '') === '' && (request()->header('sec-ch-prefers-color-scheme')=='dark')) ) dark @endif">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $title ?? 'Mbook' }}</title>

  <!-- Tailwind dark mode config -->
  <script>
    tailwind = window.tailwind || {};
    tailwind.config = { darkMode: 'class' };
  </script>

  <!-- 🌓 No-flash theme setup -->
  <script id="theme-init">
    (function () {
      try {
        // 1) Try cookie first, then fallback to localStorage, then system preference
        const cookieMatch = document.cookie.match(/(^|;)\s*theme=([^;]+)/);
        const cookieTheme = cookieMatch ? cookieMatch[2] : null;
        const saved = cookieTheme || (function(){ try { return localStorage.getItem('theme'); } catch(_) { return null; } })();
        const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
        if (saved === 'dark' || (!saved && prefersDark)) {
          document.documentElement.classList.add('dark');
        } else {
          document.documentElement.classList.remove('dark');
        }
      } catch (_) {}
    })();
  </script>

  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

  <style>
    body {
      font-family: 'Inter', sans-serif;
      transition: background-color 0.3s, color 0.3s;
    }
    body { background: #f6f9ff; color: #23272f; }
    header { background: #fff; color: #23272f; }
    .dark body { background: #0f172a; color: #e5e7eb; }
    .dark header { background: #1e293b; color: #e5e7eb; }
    .search-input {
      width: 18rem;
      padding: 0.5rem 1rem 0.5rem 2.5rem;
      border: 1px solid #d1d5db;
      border-radius: 9999px;
      font-size: 1rem;
      color: #374151;
      background: #fff;
      outline: none;
      transition: border-color 0.2s, background 0.3s;
    }
    .search-input:focus { border-color: #3b82f6; }
    .dark .search-input { background: #0f172a; border-color: #334155; color: #e5e7eb; }
    .dark .search-input::placeholder { color: #94a3b8; }
  </style>
  @stack('head')
</head>

<body class="bg-gradient-to-b from-[#f6f9ff] to-white dark:from-slate-950 dark:to-slate-900 min-h-screen text-slate-800 dark:text-slate-100">

  <!-- Header -->
  <header class="border-b border-gray-100 dark:border-slate-800 px-0 pt-4 pb-2">
    <div class="max-w-7xl mx-auto flex items-center justify-between px-8">
      <a href="/" class="flex items-center gap-2">
        <span class="text-2xl font-bold text-blue-600">M</span>
        <span class="text-xl font-semibold">book</span>
      </a>
      <div class="relative">
        <input type="text" class="search-input pr-10 h-10" placeholder="Ном хайх...">
        <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 dark:text-gray-400">
          <i class="fa-solid fa-magnifying-glass"></i>
        </span>
      </div>
      <div class="flex items-center gap-4">
        <button id="theme-toggle" aria-label="Theme солих" class="text-xl">
          <i class="fa-regular fa-moon dark:hidden"></i>
          <i class="fa-regular fa-sun hidden dark:inline"></i>
        </button>
        @auth
          <a href="profile" class="text-blue-600 font-semibold flex items-center gap-2">
            <i class="fa-regular fa-user text-blue-500"></i>
            Профайл
          </a>
        @else
          <a href="{{ route('login') }}" class="text-blue-600 font-semibold flex items-center gap-2">
            <i class="fa-solid fa-right-to-bracket text-blue-500"></i>
            Нэвтрэх
          </a>
        @endauth
      </div>
    </div>
    <!-- Navigation -->
    <nav class="max-w-7xl mx-auto px-8 mt-4 flex items-center gap-8 text-gray-700 dark:text-gray-300 font-medium">
      <a href="{{ route('home') }}" class="nav-link px-2 py-1 rounded-full hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-slate-800 dark:hover:text-blue-400 text-blue-600 dark:text-blue-400 font-semibold">Танд зориулав</a>
      <a href="{{ route('book') }}" class="nav-link px-2 py-1 rounded-full hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-slate-800 dark:hover:text-blue-400">Ном</a>
      <a href="{{ route('subscription') }}" class="nav-link px-2 py-1 rounded-full hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-slate-800 dark:hover:text-blue-400">Subscription</a>
      <a href="#" class="nav-link px-2 py-1 rounded-full hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-slate-800 dark:hover:text-blue-400">Зохиолчид</a>
    </nav>
  </header>
  <main>
    @yield('content')
  </main>

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
          document.cookie = name + '=' + encodeURIComponent(value) + ';path=/;max-age=' + maxAge;
        } catch (_) {}
      }

      function setTheme(mode) {
        if (mode === 'dark') {
          root.classList.add('dark');
          try { localStorage.setItem('theme', 'dark'); } catch (_) {}
          setCookie('theme', 'dark', 365);
        } else {
          root.classList.remove('dark');
          try { localStorage.setItem('theme', 'light'); } catch (_) {}
          // Remove cookie so server will fallback to system preference or light
          setCookie('theme', null);
        }
      }

      toggle.addEventListener('click', () => {
        const isDark = root.classList.contains('dark');
        setTheme(isDark ? 'light' : 'dark');
      });
    })();
  </script>

  @stack('scripts')
</body>
</html>