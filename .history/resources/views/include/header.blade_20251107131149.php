<!DOCTYPE html>
<html lang="mn">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mbook</title>

  <!-- Tailwind CDN -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Tailwind dark mode config -->
  <script>
    tailwind.config = { darkMode: 'class' };
  </script>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

  <style>
    body {
      font-family: 'Inter', sans-serif;
      transition: background-color 0.3s, color 0.3s;
    }

    /* Навигацийн холбоосууд */
    .nav-link {
      @apply px-3 py-1 rounded-full font-medium transition-colors;
    }
    .nav-link:hover {
      @apply bg-blue-50 text-blue-600 dark:bg-slate-800 dark:text-blue-400;
    }

    /* Хайлтын хэсэг */
    .search-input {
      @apply pl-10 pr-4 py-2 w-72 rounded-full border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-gray-800 dark:text-gray-200 outline-none;
    }
    .search-input:focus {
      @apply border-blue-500 ring-1 ring-blue-400;
    }
  </style>
</head>

<body class="bg-[#f6f9ff] dark:bg-slate-950 text-slate-800 dark:text-slate-100 transition-colors duration-300">

  <!-- HEADER -->
  <header class="border-b border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-900 py-4">
    <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between px-8 gap-4">

      <!-- Logo хэсэг -->
      <a href="/" class="flex items-center gap-2">
        <span class="text-2xl font-bold text-blue-600">M</span>
        <span class="text-xl font-semibold">book</span>
      </a>

      <!-- Хайлтын хэсэг -->
      <div class="relative">
        <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-slate-400"></i>
        <input type="text" class="search-input" placeholder="Ном хайх...">
      </div>

      <!-- Profile + Theme -->
      <div class="flex items-center gap-4">
        <button id="themeToggle" class="text-xl text-gray-600 dark:text-gray-300 hover:text-blue-500 transition">
          <i class="fa-regular fa-moon dark:hidden"></i>
          <i class="fa-regular fa-sun hidden dark:inline"></i>
        </button>

        @auth
          <a href="profile" class="text-blue-600 font-semibold flex items-center gap-2 hover:text-blue-700">
            <i class="fa-regular fa-user text-blue-500"></i>
            Профайл
          </a>
        @else
          <a href="{{ route('login') }}" class="text-blue-600 font-semibold flex items-center gap-2 hover:text-blue-700">
            <i class="fa-solid fa-right-to-bracket text-blue-500"></i>
            Нэвтрэх
          </a>
        @endauth
      </div>
    </div>

    <!-- NAVIGATION -->
    <nav class="max-w-7xl mx-auto mt-3 flex justify-center gap-8 text-gray-700 dark:text-gray-300">
      <a href="{{ route('home') }}" class="nav-link text-blue-600 dark:text-blue-400 font-semibold">Танд зориулав</a>
      <a href="{{ route('book') }}" class="nav-link">Ном</a>
      <a href="{{ route('subscription') }}" class="nav-link">Subscription</a>
      <a href="#" class="nav-link">Зохиолчид</a>
    </nav>
  </header>

  <!-- THEME TOGGLE SCRIPT -->
  <script>
    const toggle = document.getElementById('themeToggle');
    const htmlEl = document.documentElement;

    if (localStorage.theme === 'dark' || (!('theme' in localStorage)
      && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
      htmlEl.classList.add('dark');
    }

    toggle.addEventListener('click', () => {
      htmlEl.classList.toggle('dark');
      localStorage.theme = htmlEl.classList.contains('dark') ? 'dark' : 'light';
    });
  </script>
</body>
</html>
