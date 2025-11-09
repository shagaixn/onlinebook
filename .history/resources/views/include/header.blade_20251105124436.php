<!DOCTYPE html>
<html lang="mn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mbook</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Tailwind CDN ... Font links, Styles ... -->
    <style>
      /* ... Таны хуучин style-ууд ... */
    </style>
</head>
<body class="bg-gradient-to-b from-[#f6f9ff] to-white dark:from-slate-950 dark:to-slate-900 min-h-screen text-slate-800 dark:text-slate-100">

  <!-- Header -->
  <header class="dark:bg-slate-900 border-b border-gray-100 dark:border-slate-800 px-0 pt-4 pb-2">
    <div class="max-w-7xl mx-auto flex items-center justify-between px-8">
      <div class="flex items-center gap-2">
        <a href="pages.home"><span class="text-2xl font-bold text-blue-600">M</span>
        <span class="text-xl font-semibold">book</span></a>
      </div>

      <div class="flex items-center gap-4">
        <div class="relative hidden md:block" style="width: 18rem;">
          <input
            type="text"
            class="search-input pr-10 h-10"
            placeholder="Ном хайх..."
            autocomplete="off"
          >
          <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400">
            <!-- search icon ... -->
          </span>
        </div>

        <!-- Theme toggle -->
        <button id="dark-toggle" aria-label="Theme солих">🌙/☀️</button>
        <script>
          document.getElementById('dark-toggle').onclick = function() {
            document.body.classList.toggle('dark');
          }
        </script>

        <!-- Hamburger button (visible on mobile) -->
        <button
          id="nav-toggle"
          class="md:hidden ml-2 p-2 rounded bg-gray-100 dark:bg-slate-800 focus:outline-none"
          aria-label="Цэс нээх"
        >
          <!-- Hamburger icon -->
          <svg class="w-6 h-6 text-gray-800 dark:text-gray-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
          </svg>
        </button>
      </div>
    </div>

    <!-- NAVIGATION -->
    <nav 
      id="main-nav"
      class="max-w-7xl mx-auto px-8 mt-4 flex-col md:flex-row flex items-center gap-8 text-gray-700 dark:text-gray-300 font-medium
      md:flex md:static md:flex-row md:gap-8 md:bg-transparent
      absolute md:relative top-full left-0 w-full md:w-auto bg-white dark:bg-slate-900 z-20
      transition-all duration-200
      hidden md:flex"
    >
      <a href="{{ route('home') }}" class="nav-link px-2 py-1 rounded-full transition-colors hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-slate-800 dark:hover:text-blue-400 text-blue-600 dark:te[...]">Нүүр</a>
      <a href="{{ route('book') }}" class="nav-link px-2 py-1 rounded-full transition-colors hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-slate-800 dark:hover:text-blue-400">Ном</a>
      <a href="{{ route('subscription') }}" class="nav-link px-2 py-1 rounded-full transition-colors hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-slate-800 dark:hover:text-blue-400">Subscription</a>
      <a href="" class="nav-link px-2 py-1 rounded-full transition-colors hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-slate-800 dark:hover:text-blue-400">Зохиолчид</a>
    </nav>
    <!-- ... бусад script-үүд ... -->
    <script>
      // Hamburger toggle
      document.addEventListener("DOMContentLoaded", function () {
        var navToggle = document.getElementById("nav-toggle");
        var mainNav = document.getElementById("main-nav");

        navToggle && navToggle.addEventListener("click", function () {
          if (mainNav.classList.contains("hidden")) {
            mainNav.classList.remove("hidden");
          } else {
            mainNav.classList.add("hidden");
          }
        });

        // Заавал дэлгэц өөрчлөгдвөл nav-г нуух
        window.addEventListener("resize", function () {
          if (window.innerWidth >= 768) {
            mainNav.classList.remove("hidden");
          } else {
            mainNav.classList.add("hidden");
          }
        });
      });
    </script>
  </header>
</body>
</html>