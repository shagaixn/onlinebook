<!DOCTYPE html>
<html lang="mn" class="@if ( (isset($_COOKIE['theme']) && $_COOKIE['theme']=='dark') || (!isset($_COOKIE['theme']) && request()->cookie('theme', '') === '' && (request()->header('sec-ch-prefers-color-scheme')=='dark')) ) dark @endif">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', $title ?? 'Book Plus')</title>

  <!-- Tailwind dark mode config -->
  <script>
    tailwind = window.tailwind || {};
    tailwind.config = { darkMode: 'class' };
  </script>
  

  {{-- Inject per-page force flag (set by views before including this header) --}}
  <script>window.__FORCE_THEME_FLAG__ = @json($forceTheme ?? false);</script>

<script id="theme-init">
  (function () {
    try {
      const FORCE_THEME_FLAG = typeof window !== 'undefined' && window.__FORCE_THEME_FLAG__ ? true : false;
      function getCookie(name) {
        const v = document.cookie.match('(^|;)\\s*' + name + '\\s*=\\s*([^;]+)');
        return v ? decodeURIComponent(v[2]) : null;
      }
      const cookieTheme = getCookie('theme');
      let saved = null;
      try { saved = cookieTheme || localStorage.getItem('theme'); } catch (_) { saved = cookieTheme || null; }
      const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
      let useDark = (saved === 'dark') || (!saved && prefersDark);
      if (window.__FORCE_THEME_FLAG__ === true) { useDark = true; }
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

  <!-- Tailwind CDN -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Fonts / Icons -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

  {{-- Core compiled assets via Vite (night-sky imported inside app.css) --}}
  @vite(['resources/css/app.css','resources/js/app.js'])
  {{-- Per-page script stack (e.g. home.js) --}}
  @stack('page-scripts')

  <style>
    body {
      font-family: 'Inter', sans-serif;
      transition: background-color 0.3s, color 0.3s;
    }

    /* 🌞 Light mode */
    body {
      background: #f6f9ff;
      color: #23272f;
    }
    header {
      background: #fff;
      color: #23272f;
    }

    /* 🌙 Dark mode */
    .dark body {
      background: #0f172a;
      color: #f1f5f9; /* brighter text */
    }
    .dark header {
      background: #1e293b;
      color: #f1f5f9; /* brighter header text */
    }

    /* 🔍 Search input */
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
    .search-input:focus {
      border-color: #3b82f6;
    }
    .dark .search-input {
      background: #0f172a;
      border-color: #475569; /* slightly lighter border */
      color: #f1f5f9; /* brighter input text */
    }
    .dark .search-input::placeholder {
      color: #cbd5e1; /* lighter placeholder */
    }
  </style>
</head>

<body class="bg-gradient-to-b from-[#f6f9ff] to-white dark:from-slate-950 dark:to-slate-900 min-h-screen text-slate-800 dark:text-slate-100">

  <!-- Header -->
  <header class="border-b border-gray-100 dark:border-slate-800 px-0 pt-4 pb-2">
    <div class="max-w-7xl mx-auto flex items-center justify-between px-8">
      <!-- Logo -->
      <a href="/" class="flex items-center gap-2">
        <span class="text-2xl font-bold text-blue-600">Book</span>
        <span class="text-xl font-semibold">Plus</span>
      </a>

      <!-- Search -->
      <form class="relative" action="{{ route('book') }}" method="GET">
        <input
          type="text"
          name="q"
          value="{{ request('q') }}"
          class="search-input pr-10 h-10"
          placeholder="Номын нэрээр хайх..."
          aria-label="Ном хайх"
        >
        <button type="submit" class="absolute right-3 top-1/1 -translate-y-1/2 text-gray-400 dark:text-gray-400 hover:text-blue-500 transition-colors" aria-label="Хайх">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </button>
      </form>

      <!-- Profile + Theme -->
      <div class="flex items-center gap-4">
        <!-- 🌗 Theme toggle -->
        <button id="theme-toggle" aria-label="Theme солих" class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors">
          <svg id="theme-moon" class="w-6 h-6 text-slate-600 dark:text-slate-300 dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
          <svg id="theme-sun" class="w-6 h-6 text-yellow-400 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
        </button>

        @auth
          <a href="{{ url('/profile') }}" class="text-blue-600 font-semibold flex items-center gap-2 hover:text-blue-700 transition-colors">
            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
              <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            </div>
            <span>{{ auth()->user()->name ?? 'Профайл' }}</span>
          </a>
        @else
          <a href="{{ route('login') }}" class="text-blue-600 font-semibold flex items-center gap-2 hover:text-blue-700 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
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
  <a href="{{ route('authors.index') }}" class="nav-link px-2 py-1 rounded-full hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-slate-800 dark:hover:text-blue-400">Зохиолчид</a>
  <a href="{{ route('podcast') }}" class="nav-link px-2 py-1 rounded-full hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-slate-800 dark:hover:text-blue-400">Podcast</a>
  <a href="{{ route('manga') }}" class="nav-link px-2 py-1 rounded-full hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-slate-800 dark:hover:text-blue-400">Manga</a>
    </nav>
  </header>

  <!-- 🌓 Theme toggle script -->
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
          const moon = document.getElementById('theme-moon');
          const sun = document.getElementById('theme-sun');
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

</body>
</html>