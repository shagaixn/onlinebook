<!DOCTYPE html>
<html lang="mn">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mbook</title>

  <!-- Tailwind dark mode config -->
  <script>
    tailwind = window.tailwind || {};
    tailwind.config = { darkMode: 'class' };
  </script>

  <!-- 🌓 No-flash theme setup -->
  <script>
    (function () {
      try {
        const saved = localStorage.getItem('theme');
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        if (saved === 'dark' || (!saved && prefersDark)) {
          document.documentElement.classList.add('dark');
        } else {
          document.documentElement.classList.remove('dark');
        }
      } catch (_) {}
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

  <!-- 🌓 Theme toggle script -->
  <script>
    (function () {
      const toggle = document.getElementById('theme-toggle');
      const root = document.documentElement; // ✅ dark-г HTML root дээр тавина

      function setTheme(mode) {
        if (mode === 'dark') {
          root.classList.add('dark');
          localStorage.setItem('theme', 'dark');
        } else {
          root.classList.remove('dark');
          localStorage.setItem('theme', 'light');
        }
      }

      toggle.addEventListener('click', () => {
        const isDark = root.classList.contains('dark');
        setTheme(isDark ? 'light' : 'dark');
      });
    })();
  </script>

</body>
</html>
