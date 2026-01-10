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
      font-weight: 700; /* Global bold text */
    }

    /* 🌞 Light mode */
    body {
      background: #f6f9ff;
      color: #000000;
    }
    header {
      background: #fff;
      color: #000000;
    }

    /* 🌙 Dark mode */
    .dark body {
      background: #0f172a;
      color: #ffffff; /* white text */
    }
    .dark header {
      background: #1e293b;
      color: #ffffff; /* white header text */
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
    .search-input:focus { border-color: #3b82f6; }
    .dark .search-input {
      background: #0f172a;
      border-color: #475569;
      color: #f1f5f9;
    }
    .dark .search-input::placeholder { color: #cbd5e1; }

    /* --- Global Text Enforcement --- */
    body, p, h1, h2, h3, h4, h5, h6, span, a, li, div, button, input, textarea, select { font-weight: 700 !important; }
    body { color: #000000; }
    :not(.dark) .text-slate-50, :not(.dark) .text-slate-100, :not(.dark) .text-slate-200, :not(.dark) .text-slate-300, :not(.dark) .text-slate-400,
    :not(.dark) .text-gray-50, :not(.dark) .text-gray-100, :not(.dark) .text-gray-200, :not(.dark) .text-gray-300, :not(.dark) .text-gray-400,
    :not(.dark) .text-white { color: #000000 !important; }

    .dark body { color: #ffffff; }
    .dark .text-black,
    .dark .text-slate-950, .dark .text-slate-900, .dark .text-slate-800, .dark .text-slate-700, .dark .text-slate-600, .dark .text-slate-500,
    .dark .text-gray-950, .dark .text-gray-900, .dark .text-gray-800, .dark .text-gray-700, .dark .text-gray-600, .dark .text-gray-500,
    .dark .text-zinc-950, .dark .text-zinc-900, .dark .text-zinc-800, .dark .text-zinc-700, .dark .text-zinc-600, .dark .text-zinc-500,
    .dark .text-neutral-950, .dark .text-neutral-900, .dark .text-neutral-800, .dark .text-neutral-700, .dark .text-neutral-600, .dark .text-neutral-500,
    .dark .text-stone-950, .dark .text-stone-900, .dark .text-stone-800, .dark .text-stone-700, .dark .text-stone-600, .dark .text-stone-500 { color: #ffffff !important; }
  </style>
</head>

<body class="bg-gradient-to-b from-[#f6f9ff] to-white dark:from-slate-950 dark:to-slate-900 min-h-screen text-black dark:text-white">

  <!-- Header -->
  <header class="sticky top-0 z-50 px-4 pt-5 pb-3 bg-transparent">
    <div class="max-w-screen-xl mx-auto">
      <div class="relative">
        <div class="flex items-center justify-between gap-4 rounded-full border border-slate-200 dark:border-slate-800 bg-white/90 dark:bg-slate-900/90 shadow-lg backdrop-blur px-4 sm:px-6 py-2.5">
          <div class="flex items-center gap-4 min-w-0">
            <a href="/" class="flex items-center gap-2 flex-shrink-0">
              <span class="text-2xl font-bold text-blue-600">Book</span>
              <span class="text-xl font-semibold">Plus</span>
            </a>
            <span aria-hidden="true" class="h-8 w-px bg-slate-200 dark:bg-slate-700"></span>

            <div class="hidden md:flex items-center gap-1 text-sm font-semibold text-slate-700 dark:text-slate-200">
              <a href="{{ route('home') }}" class="px-3 py-1.5 rounded-full hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-slate-800 dark:hover:text-blue-400 {{ request()->routeIs('home') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-slate-800' : '' }}">Танд зориулав</a>
              <a href="{{ route('book') }}" class="px-3 py-1.5 rounded-full hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-slate-800 dark:hover:text-blue-400 {{ request()->routeIs('book') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-slate-800' : '' }}">Ном</a>
              <a href="{{ route('subscription') }}" class="px-3 py-1.5 rounded-full hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-slate-800 dark:hover:text-blue-400 {{ request()->routeIs('subscription') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-slate-800' : '' }}">Subscription</a>
              <a href="{{ route('authors.index') }}" class="px-3 py-1.5 rounded-full hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-slate-800 dark:hover:text-blue-400 {{ request()->routeIs('authors.index') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-slate-800' : '' }}">Зохиолчид</a>
              <a href="{{ route('podcast') }}" class="px-3 py-1.5 rounded-full hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-slate-800 dark:hover:text-blue-400 {{ request()->routeIs('podcast') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-slate-800' : '' }}">Podcast</a>
              <a href="{{ route('manga') }}" class="px-3 py-1.5 rounded-full hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-slate-800 dark:hover:text-blue-400 {{ request()->routeIs('manga') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-slate-800' : '' }}">Manga</a>
            </div>
          </div>

          <div class="flex items-center gap-3 flex-shrink-0">
            <form action="{{ route('book') }}" method="GET" class="hidden md:block">
              <label class="sr-only" for="desktop-search">Ном хайх</label>
              <div class="relative">
                <input
                  id="desktop-search"
                  type="text"
                  name="q"
                  value="{{ request('q') }}"
                  class="search-input h-11 w-64"
                  placeholder="Номын нэр..."
                  aria-label="Ном хайх"
                >
                <button type="submit" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 hover:text-blue-600 dark:text-slate-300 dark:hover:text-blue-400">
                  <i class="fa-solid fa-magnifying-glass"></i>
                </button>
              </div>
            </form>
            <button data-theme-toggle aria-label="Theme солих" class="text-xl">
              <i class="fa-regular fa-moon dark:hidden"></i>
              <i class="fa-regular fa-sun hidden dark:inline"></i>
            </button>

            @auth
              <a href="{{ url('/profile') }}" class="hidden md:inline-flex text-blue-600 font-semibold items-center gap-2 hover:text-blue-700 transition-colors">
                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                  <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>
                <span class="truncate max-w-[120px]">{{ auth()->user()->name ?? 'Профайл' }}</span>
              </a>
            @else
              <a href="{{ route('login') }}" class="hidden md:inline-flex text-blue-600 font-semibold items-center gap-2 hover:text-blue-700 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                Нэвтрэх
              </a>
            @endauth

            <button data-menu-toggle aria-label="Цэс нээх" aria-expanded="false" class="inline-flex md:hidden items-center justify-center w-11 h-11 rounded-full border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm">
              <span class="sr-only">Цэс нээх</span>
              <div data-icon="lines" class="space-y-1.5">
                <span class="block h-0.5 w-6 bg-slate-900 dark:bg-white"></span>
                <span class="block h-0.5 w-6 bg-slate-900 dark:bg-white"></span>
                <span class="block h-0.5 w-6 bg-slate-900 dark:bg-white"></span>
              </div>
              <div data-icon="close" class="hidden">
                <span class="block h-0.5 w-6 bg-slate-900 dark:bg-white rotate-45 translate-y-[3px]"></span>
                <span class="block h-0.5 w-6 bg-slate-900 dark:bg-white -rotate-45 -translate-y-[3px]"></span>
              </div>
            </button>
          </div>
        </div>

        <div id="mobile-menu" class="hidden md:hidden fixed inset-0 z-30 bg-white/95 dark:bg-slate-950/95 backdrop-blur px-6 pt-24 pb-10">
          <div class="max-w-4xl mx-auto space-y-8">
            <form class="relative" action="{{ route('book') }}" method="GET">
              <input
                type="text"
                name="q"
                value="{{ request('q') }}"
                class="search-input h-12 w-full"
                placeholder="Номын нэрээр хайх..."
                aria-label="Ном хайх"
              >
              <button type="submit" class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-400 hover:text-blue-500 transition-colors" aria-label="Хайх">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
              </button>
            </form>

            <div class="flex flex-wrap items-center justify-center gap-3 text-base font-semibold text-slate-800 dark:text-slate-100">
              <a href="{{ route('home') }}" class="px-4 py-2 rounded-full hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-slate-800 dark:hover:text-blue-400 {{ request()->routeIs('home') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-slate-800' : '' }}">Танд зориулав</a>
              <a href="{{ route('book') }}" class="px-4 py-2 rounded-full hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-slate-800 dark:hover:text-blue-400 {{ request()->routeIs('book') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-slate-800' : '' }}">Ном</a>
              <a href="{{ route('subscription') }}" class="px-4 py-2 rounded-full hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-slate-800 dark:hover:text-blue-400 {{ request()->routeIs('subscription') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-slate-800' : '' }}">Subscription</a>
              <a href="{{ route('authors.index') }}" class="px-4 py-2 rounded-full hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-slate-800 dark:hover:text-blue-400 {{ request()->routeIs('authors.index') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-slate-800' : '' }}">Зохиолчид</a>
              <a href="{{ route('podcast') }}" class="px-4 py-2 rounded-full hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-slate-800 dark:hover:text-blue-400 {{ request()->routeIs('podcast') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-slate-800' : '' }}">Podcast</a>
              <a href="{{ route('manga') }}" class="px-4 py-2 rounded-full hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-slate-800 dark:hover:text-blue-400 {{ request()->routeIs('manga') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-slate-800' : '' }}">Manga</a>
            </div>

            <div class="flex flex-wrap items-center justify-center gap-4">
              <div class="flex items-center gap-3 rounded-2xl border border-slate-200 dark:border-slate-800 px-4 py-3">
                <span class="text-sm font-semibold">Гэрэл / Хар</span>
                <button data-theme-toggle aria-label="Theme солих" class="text-xl">
                  <i class="fa-regular fa-moon dark:hidden"></i>
                  <i class="fa-regular fa-sun hidden dark:inline"></i>
                </button>
              </div>

              @auth
                <a href="{{ url('/profile') }}" class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 dark:border-slate-800 px-4 py-3 text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 transition-colors">
                  <span class="font-semibold">{{ auth()->user()->name ?? 'Профайл' }}</span>
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
              @else
                <a href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 dark:border-slate-800 px-4 py-3 text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 transition-colors">
                  <span class="font-semibold">Нэвтрэх</span>
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
              @endauth
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>

  <script>
    (() => {
      const toggle = document.querySelector('[data-menu-toggle]');
      const panel = document.getElementById('mobile-menu');
      if (!toggle || !panel) return;
      const lineIcon = toggle.querySelector('[data-icon="lines"]');
      const closeIcon = toggle.querySelector('[data-icon="close"]');
      let open = false;

      const setState = (state) => {
        open = state;
        panel.classList.toggle('hidden', !open);
        lineIcon?.classList.toggle('hidden', open);
        closeIcon?.classList.toggle('hidden', !open);
        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
      };

      toggle.addEventListener('click', (e) => {
        e.preventDefault();
        setState(!open);
      });

      document.addEventListener('click', (e) => {
        if (!open) return;
        if (toggle.contains(e.target) || panel.contains(e.target)) return;
        setState(false);
      });

      document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && open) setState(false);
      });
    })();
  </script>

  <!-- 🌓 Theme toggle script -->
  <script>
    (function () {
      const toggles = Array.from(document.querySelectorAll('[data-theme-toggle]'));
      if (!toggles.length) return;
      const root = document.documentElement;

      function setCookie(name, value, days) {
        const maxAge = days ? (60*60*24*days) : (60*60*24*365);
        document.cookie = name + '=' + encodeURIComponent(value) + ';path=/;max-age=' + maxAge + ';SameSite=Lax';
      }

      function setTheme(mode) {
        if (mode === 'dark') {
          root.classList.add('dark');
          root.setAttribute('data-theme', 'dark');
          localStorage.setItem('theme', 'dark');
          setCookie('theme', 'dark', 365);
        } else {
          root.classList.remove('dark');
          root.setAttribute('data-theme', 'light');
          localStorage.setItem('theme', 'light');
          setCookie('theme', 'light', 365);
        }
      }

      const savedTheme = localStorage.getItem('theme') || (document.cookie.match('(^|;)\\s*theme\\s*=\\s*([^;]+)') ? decodeURIComponent(document.cookie.match('(^|;)\\s*theme\\s*=\\s*([^;]+)')[2]) : null);
      if (savedTheme) { setTheme(savedTheme); }

      toggles.forEach((btn) => {
        btn.addEventListener('click', (e) => {
          e.preventDefault();
          const isDark = root.classList.contains('dark');
          setTheme(isDark ? 'light' : 'dark');
        });
      });
    })();
  </script>
</body>
</html>