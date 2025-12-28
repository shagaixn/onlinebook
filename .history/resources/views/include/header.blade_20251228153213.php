<script>
  (function () {
    const root = document.documentElement;
    const lsTheme = localStorage.getItem('theme');
    const match = document.cookie.match('(^|;)\\s*theme\\s*=\\s*([^;]+)');
    const cookieTheme = match ? decodeURIComponent(match[2]) : null;
    const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
    const initial = lsTheme || cookieTheme || (prefersDark ? 'dark' : 'light');
    if (initial === 'dark') {
      root.classList.add('dark');
      root.setAttribute('data-theme', 'dark');
    } else {
      root.classList.remove('dark');
      root.setAttribute('data-theme', 'light');
    }
  })();
</script>

<header class="sticky top-0 z-50 border-b border-gray-100 dark:border-slate-800 px-0 pt-4 pb-2 bg-white dark:bg-slate-900">
  <div class="max-w-7xl mx-auto flex items-center justify-between px-8">
    <a href="/" class="flex items-center gap-2">
      <span class="text-2xl font-bold text-blue-600">Book</span>
      <span class="text-xl font-semibold">Plus</span>
    </a>

    <form class="relative" action="{{ route('book') }}" method="GET">
      <input
        type="text"
        name="q"
        value="{{ request('q') }}"
        class="h-10 pl-10 pr-4 rounded-full border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-gray-800 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 w-64 sm:w-80"
        placeholder="Номын нэрээр хайх..."
        aria-label="Ном хайх"
      >
      <button type="submit" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-400 hover:text-blue-500 transition-colors" aria-label="Хайх">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/></svg>
      </button>
    </form>

    <div class="flex items-center gap-4">
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

  <nav class="max-w-7xl mx-auto px-8 mt-4 flex items-center gap-8 text-gray-700 dark:text-gray-300 font-medium">
    <a href="{{ route('home') }}"
       class="nav-link px-2 py-1 rounded-full hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-slate-800 dark:hover:text-blue-400 {{ request()->routeIs('home') ? 'text-blue-600 dark:text-blue-400 font-semibold' : '' }}"
       aria-current="{{ request()->routeIs('home') ? 'page' : 'false' }}">Нүүр</a>
    <a href="{{ route('book') }}"
       class="nav-link px-2 py-1 rounded-full hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-slate-800 dark:hover:text-blue-400 {{ request()->routeIs('book') ? 'text-blue-600 dark:text-blue-400 font-semibold' : '' }}"
       aria-current="{{ request()->routeIs('book') ? 'page' : 'false' }}">Ном</a>
    <a href="{{ route('subscription') }}"
       class="nav-link px-2 py-1 rounded-full hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-slate-800 dark:hover:text-blue-400 {{ request()->routeIs('subscription') ? 'text-blue-600 dark:text-blue-400 font-semibold' : '' }}"
       aria-current="{{ request()->routeIs('subscription') ? 'page' : 'false' }}">Subscription</a>
    <a href="{{ route('authors.index') }}"
       class="nav-link px-2 py-1 rounded-full hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-slate-800 dark:hover:text-blue-400 {{ request()->routeIs('authors.*') ? 'text-blue-600 dark:text-blue-400 font-semibold' : '' }}"
       aria-current="{{ request()->routeIs('authors.*') ? 'page' : 'false' }}">Зохиолчид</a>
    <a href="{{ route('podcast') }}"
       class="nav-link px-2 py-1 rounded-full hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-slate-800 dark:hover:text-blue-400 {{ request()->routeIs('podcast') ? 'text-blue-600 dark:text-blue-400 font-semibold' : '' }}"
       aria-current="{{ request()->routeIs('podcast') ? 'page' : 'false' }}">Podcast</a>
    <a href="{{ route('manga') }}"
       class="nav-link px-2 py-1 rounded-full hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-slate-800 dark:hover:text-blue-400 {{ request()->routeIs('manga') ? 'text-blue-600 dark:text-blue-400 font-semibold' : '' }}"
       aria-current="{{ request()->routeIs('manga') ? 'page' : 'false' }}">Manga</a>
  </nav>
</header>

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
    toggle.addEventListener('click', (e) => {
      e.preventDefault();
      const isDark = root.classList.contains('dark');
      setTheme(isDark ? 'light' : 'dark');
    });
  })();

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