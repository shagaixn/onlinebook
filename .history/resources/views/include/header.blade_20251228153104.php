<!DOCTYPE html>
<html lang="mn" class="@if ( (isset($_COOKIE['theme']) && $_COOKIE['theme']=='dark') || (!isset($_COOKIE['theme']) && request()->cookie('theme', '') === '' && (request()->header('sec-ch-prefers-color-scheme') === 'dark')) ) dark @endif">
<head>
  <!-- ... бусад толгой хэсэг хэвээр ... -->
</head>

<body class="bg-gradient-to-b from-[#f6f9ff] to-white dark:from-slate-950 dark:to-slate-900 min-h-screen text-black dark:text-white">

  <!-- Header -->
  <!-- sticky top-0 z-50 нэмлээ; мөн фондыг классаар тодруулж, давхарга зохицууллаа -->
  <header class="sticky top-0 z-50 border-b border-gray-100 dark:border-slate-800 px-0 pt-4 pb-2 bg-white dark:bg-slate-900">
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
          class="search-input h-10"
          placeholder="Номын нэрээр хайх..."
          aria-label="Ном хайх"
        >
        <button type="submit" class="absolute left-3 top-1/3 -translate-y-2/2 text-gray-400 dark:text-gray-400 hover:text-blue-500 transition-colors" aria-label="Хайх">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/></svg>
        </button>
      </form>

      <!-- Right side -->
      <div class="flex items-center gap-4">
        <!-- 🌗 Theme toggle -->
        <button id="theme-toggle" aria-label="Theme солих" class="text-xl">
          <i class="fa-regular fa-moon dark:hidden"></i>
          <i class="fa-regular fa-sun hidden dark:inline"></i>
        </button>

        @auth
          <a href="{{ url('/profile') }}" class="text-blue-600 font-semibold flex items-center gap-2 hover:text-blue-700 transition-colors">
            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
              <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0"/></svg>
            </div>
            <span>{{ auth()->user()->name ?? 'Профайл' }}</span>
          </a>
        @else
          <a href="{{ route('login') }}" class="text-blue-600 font-semibold flex items-center gap-2 hover:text-blue-700 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 006 0"/></svg>
            Нэвтрэх
          </a>
        @endauth
      </div>
    </div>

    <!-- Navigation -->
    <nav class="max-w-7xl mx-auto px-8 mt-4 flex items-center gap-8 text-gray-700 dark:text-gray-300 font-medium">
      <a href="{{ route('home') }}" class="nav-link px-2 py-1 rounded-full hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-slate-800 dark:hover:text-blue-400 text-blue-600 dark:text-blue-400 font-semibold">Нүүр</a>
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
      if (savedTheme) {
        setTheme(savedTheme);
      }

      toggle.addEventListener('click', (e) => {
        e.preventDefault();
        const isDark = root.classList.contains('dark');
        setTheme(isDark ? 'light' : 'dark');
      });
    })();

    // Гүйлгэхэд толгой хэсэгт сүүдэр нэмэх (сонголтоор)
    (function () {
      const headerEl = document.querySelector('header');
      if (!headerEl) return;
      function onScroll() {
        if (window.scrollY > 0) {
          headerEl.classList.add('shadow-sm');
        } else {
          headerEl.classList.remove('shadow-sm');
        }
      }
      window.addEventListener('scroll', onScroll, { passive: true });
      onScroll();
    })();
  </script>

</body>
</html>