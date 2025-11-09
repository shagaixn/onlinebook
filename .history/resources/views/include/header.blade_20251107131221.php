<!DOCTYPE html>
<html lang="mn">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mbook</title>

  <!-- Tailwind CDN -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Dark mode config -->
  <script>
    tailwind.config = {
      darkMode: 'class',
      theme: {
        extend: {
          fontFamily: {
            sans: ['Inter', 'system-ui', 'sans-serif'],
          },
        },
      },
    }
  </script>

  <!-- Fonts & Icons -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

  <style>
    html {
      scroll-behavior: smooth;
    }
    .nav-link {
      @apply px-3 py-2 rounded-full text-gray-700 dark:text-gray-300 hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-slate-800 dark:hover:text-blue-400 transition-colors;
    }
    .search-input {
      @apply w-64 pl-10 pr-4 py-2 rounded-full border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none;
    }
    .search-icon {
      @apply absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 dark:text-slate-400;
    }
  </style>
</head>
<body class="bg-gradient-to-b from-[#f6f9ff] to-white dark:from-slate-950 dark:to-slate-900 min-h-screen font-sans text-slate-800 dark:text-slate-100 transition-colors duration-300">

  <!-- HEADER -->
  <header class="sticky top-0 z-50 bg-white/80 dark:bg-slate-900/80 backdrop-blur border-b border-gray-100 dark:border-slate-800">
    <div class="max-w-7xl mx-auto flex items-center justify-between px-6 py-3">
      
      <!-- Logo -->
      <a href="/" class="flex items-center gap-2">
        <span class="text-2xl font-bold text-blue-600">M</span>
        <span class="text-xl font-semibold">book</span>
      </a>

      <!-- Search -->
      <div class="hidden md:block relative">
        <span class="search-icon">
          <i class="fa-solid fa-magnifying-glass"></i>
        </span>
        <input type="text" class="search-input" placeholder="Ном хайх..." />
      </div>

      <!-- Right controls -->
      <div class="flex items-center gap-4">
        <!-- Theme toggle -->
        <button id="themeToggle" class="text-xl hover:text-blue-500 transition" title="Theme солих">
          <i class="fa-regular fa-moon dark:hidden"></i>
          <i class="fa-regular fa-sun hidden dark:inline"></i>
        </button>

        <!-- Profile / Login -->
        @auth
          <a href="profile" class="flex items-center gap-2 font-semibold text-blue-600 hover:text-blue-700">
            <i class="fa-regular fa-user"></i> Профайл
          </a>
        @else
          <a href="{{ route('login') }}" class="flex items-center gap-2 font-semibold text-blue-600 hover:text-blue-700">
            <i class="fa-solid fa-right-to-bracket"></i> Нэвтрэх
          </a>
        @endauth

        <!-- Mobile menu button -->
        <button id="menuBtn" class="md:hidden text-2xl hover:text-blue-500">
          <i class="fa-solid fa-bars"></i>
        </button>
      </div>
    </div>

    <!-- NAVIGATION -->
    <nav id="mobileNav" class="max-w-7xl mx-auto px-6 pb-3 md:flex md:items-center md:justify-center md:pb-0 hidden">
      <a href="{{ route('home') }}" class="nav-link font-semibold text-blue-600 dark:text-blue-400">Танд зориулав</a>
      <a href="{{ route('book') }}" class="nav-link">Ном</a>
      <a href="{{ route('subscription') }}" class="nav-link">Subscription</a>
      <a href="#" class="nav-link">Зохиолчид</a>
    </nav>
  </header>

  <!-- THEME TOGGLE SCRIPT -->
  <script>
    const themeToggle = document.getElementById('themeToggle');
    const htmlEl = document.documentElement;
    if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
      htmlEl.classList.add('dark');
    }
    themeToggle.addEventListener('click', () => {
      htmlEl.classList.toggle('dark');
      localStorage.theme = htmlEl.classList.contains('dark') ? 'dark' : 'light';
    });
  </script>

  <!-- MOBILE MENU SCRIPT -->
  <script>
    const menuBtn = document.getElementById('menuBtn');
    const mobileNav = document.getElementById('mobileNav');
    menuBtn.addEventListener('click', () => {
      mobileNav.classList.toggle('hidden');
    });
  </script>

</body>
</html>
