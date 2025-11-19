<!DOCTYPE html>
<html lang="mn" class="@if ( (isset($_COOKIE['theme']) && $_COOKIE['theme']=='dark') || (!isset($_COOKIE['theme']) && request()->cookie('theme', '') === '' && (request()->header('sec-ch-prefers-color-scheme')=='dark')) ) dark @endif">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', $title ?? 'Mbook')</title>

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
      color: #e5e7eb;
    }
    .dark header {
      background: #1e293b;
      color: #e5e7eb;
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
      border-color: #334155;
      color: #e5e7eb;
    }
    .dark .search-input::placeholder {
      color: #94a3b8;
    }
  </style>
</head>

<body class="bg-gradient-to-b from-[#f6f9ff] to-white dark:from-slate-950 dark:to-slate-900 min-h-screen text-slate-800 dark:text-slate-100">

  <!-- Header -->
  <header class="border-b border-gray-100 dark:border-slate-800 px-0 pt-4 pb-2">
    <div class="max-w-7xl mx-auto flex items-center justify-between px-8">
      <!-- Logo -->
      <a href="/" class="flex items-center gap-2">
        <span class="text-2xl font-bold text-blue-600">M</span>
        <span class="text-xl font-semibold">book</span>
      </a>

      <!-- Search -->
      <div class="relative">
        <input type="text" class="search-input pr-10 h-10" placeholder="Ном хайх...">
        <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 dark:text-gray-400">
          <i class="fa-solid fa-magnifying-glass"></i>
        </span>
      </div>

      <!-- Profile + Theme -->
      <div class="flex items-center gap-4">
        <!-- 🌗 Theme toggle -->
        <button id="theme-toggle" aria-label="Theme солих" class="text-xl">
          <i class="fa-regular fa-moon dark:hidden"></i>
          <i class="fa-regular fa-sun hidden dark:inline"></i>
        </button>

        @auth
          <a href="{{ url('/profile') }}" class="text-blue-600 font-semibold flex items-center gap-2">
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
    <nav
    class="sticky top-0 z-50 border-b border-slate-200/60 dark:border-slate-800/60 bg-white/70 dark:bg-slate-900/60 backdrop-blur-xl supports-[backdrop-filter]:bg-white/60 dark:supports-[backdrop-filter]:bg-slate-900/50"
    role="navigation"
    aria-label="Primary"
>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            <!-- Left: Brand -->
            <div class="flex items-center gap-3">
                <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                    <span
                        class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 text-white shadow-sm shadow-blue-500/20 ring-1 ring-inset ring-white/10"
                        aria-hidden="true"
                    >
                        <!-- Simple brand mark -->
                        <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M4 7a3 3 0 0 1 3-3h10a3 3 0 0 1 3 3v10a3 3 0 0 1-3 3H7a3 3 0 0 1-3-3V7Z" class="opacity-70"/>
                            <path d="M8 10h8M8 14h5" stroke-linecap="round"/>
                        </svg>
                    </span>
                    <span class="text-lg font-semibold text-slate-800 dark:text-slate-100">
                        Унших
                    </span>
                </a>
            </div>

            <!-- Desktop Nav -->
            <div class="hidden md:flex items-center gap-2">
                @foreach ($items as $item)
                    @php
                        $active = $isActive($item['pattern']);
                    @endphp
                    <a
                        href="{{ $item['href'] }}"
                        @if($active) aria-current="page" @endif
                        class="group relative inline-flex items-center gap-2 px-3 py-2 rounded-full text-sm font-medium transition-colors duration-200
                               focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500/60
                               {{ $active
                                  ? 'text-blue-600 dark:text-blue-400 bg-blue-50/70 dark:bg-slate-800/60 shadow-[inset_0_0_0_1px] shadow-blue-500/10'
                                  : 'text-slate-700 dark:text-slate-300 hover:text-blue-600 hover:bg-blue-50/60 dark:hover:text-blue-400 dark:hover:bg-slate-800/60' }}"
                    >
                        {{ $item['label'] }}
                        <span class="absolute -bottom-1 left-4 right-4 h-0.5 rounded-full
                                     {{ $active ? 'bg-gradient-to-r from-blue-500/0 via-blue-500/60 to-blue-500/0' : 'bg-transparent group-hover:bg-blue-500/30' }}">
                        </span>
                    </a>
                @endforeach
            </div>

            <!-- Right: Search + Toggle -->
            <div class="flex items-center gap-2">
                <!-- Search (hidden on small screens) -->
                <form action="{{ url('/search') }}" method="GET" class="hidden sm:block">
                    <label class="relative block">
                        <span class="sr-only">Хайх</span>
                        <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-slate-400">
                            <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8">
                                <circle cx="11" cy="11" r="7" />
                                <path d="M20 20l-3-3" stroke-linecap="round"/>
                            </svg>
                        </span>
                        <input
                            name="q"
                            type="search"
                            placeholder="Хайх..."
                            class="w-56 lg:w-72 rounded-xl border border-slate-200/70 dark:border-slate-700/60 bg-white/70 dark:bg-slate-900/50 backdrop-blur placeholder:text-slate-400
                                   pl-10 pr-3 py-2 text-sm text-slate-900 dark:text-slate-100
                                   focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-400/60"
                        />
                    </label>
                </form>

                <!-- Mobile menu toggle -->
                <button
                    id="nav-toggle"
                    class="md:hidden inline-flex items-center justify-center rounded-xl p-2 text-slate-600 dark:text-slate-300
                           hover:bg-blue-50/60 dark:hover:bg-slate-800/60 hover:text-blue-600 dark:hover:text-blue-400
                           focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500/60"
                    aria-controls="nav-menu"
                    aria-expanded="false"
                    aria-label="Цэсийг солих"
                    type="button"
                >
                    <svg id="nav-toggle-icon-open" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path d="M4 7h16M4 12h16M4 17h16" stroke-linecap="round"/>
                    </svg>
                    <svg id="nav-toggle-icon-close" class="h-6 w-6 hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path d="M6 6l12 12M18 6l-12 12" stroke-linecap="round"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Nav -->
        <div id="nav-menu" class="md:hidden hidden pb-4">
            <div class="mt-2 grid gap-1">
                @foreach ($items as $item)
                    @php
                        $active = $isActive($item['pattern']);
                    @endphp
                    <a
                        href="{{ $item['href'] }}"
                        @if($active) aria-current="page" @endif
                        class="block rounded-xl px-3 py-2 text-sm font-medium transition-colors
                               focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500/60
                               {{ $active
                                  ? 'text-blue-600 dark:text-blue-400 bg-blue-50/70 dark:bg-slate-800/60'
                                  : 'text-slate-700 dark:text-slate-300 hover:text-blue-600 hover:bg-blue-50/60 dark:hover:text-blue-400 dark:hover:bg-slate-800/60' }}"
                    >
                        {{ $item['label'] }}
                    </a>
                @endforeach

                <!-- Mobile Search -->
                <form action="{{ url('/search') }}" method="GET" class="pt-1">
                    <label class="relative block">
                        <span class="sr-only">Хайх</span>
                        <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-slate-400">
                            <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8">
                                <circle cx="11" cy="11" r="7" />
                                <path d="M20 20l-3-3" stroke-linecap="round"/>
                            </svg>
                        </span>
                        <input
                            name="q"
                            type="search"
                            placeholder="Хайх..."
                            class="w-full rounded-xl border border-slate-200/70 dark:border-slate-700/60 bg-white/70 dark:bg-slate-900/50 backdrop-blur
                                   placeholder:text-slate-400 pl-10 pr-3 py-2 text-sm text-slate-900 dark:text-slate-100
                                   focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-400/60"
                        />
                    </label>
                </form>
            </div>
        </div>
    </div>
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

</body>
</html>
