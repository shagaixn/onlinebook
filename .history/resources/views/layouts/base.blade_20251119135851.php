<!DOCTYPE html>
<html lang="mn" class="@if ( (isset($_COOKIE['theme']) && $_COOKIE['theme']=='dark') || (!isset($_COOKIE['theme']) && request()->cookie('theme', '') === '' && (request()->header('sec-ch-prefers-color-scheme')=='dark')) ) dark @endif">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', $title ?? 'Mbook')</title>

  <script>tailwind = window.tailwind || {}; tailwind.config = { darkMode: 'class' };</script>
  <script>window.__FORCE_THEME_FLAG__ = @json($forceTheme ?? false);</script>
  <script id="theme-init">(function(){try{const FORCE=!!window.__FORCE_THEME_FLAG__;function gc(n){const v=document.cookie.match('(^|;)\\s*'+n+'\\s*=\\s*([^;]+)');return v?decodeURIComponent(v[2]):null;}const c=gc('theme');let s=null;try{s=c||localStorage.getItem('theme');}catch(_){s=c||null;}const prefersDark=window.matchMedia&&window.matchMedia('(prefers-color-scheme: dark)').matches;let useDark=(s==='dark')||(!s&&prefersDark);if(FORCE)useDark=true;if(useDark){document.documentElement.classList.add('dark');document.documentElement.setAttribute('data-theme','dark');}else{document.documentElement.classList.remove('dark');document.documentElement.setAttribute('data-theme','light');}}catch(e){}})();</script>

  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
  @vite(['resources/css/app.css','resources/js/app.js'])
  @stack('page-scripts')
  <style>body{font-family:'Inter',sans-serif;transition:background-color .3s,color .3s;}body{background:#f6f9ff;color:#23272f;}header{background:#fff;color:#23272f}.dark body{background:#0f172a;color:#f1f5f9}.dark header{background:#1e293b;color:#f1f5f9}.search-input{width:18rem;padding:.5rem 1rem .5rem 2.5rem;border:1px solid #d1d5db;border-radius:9999px;font-size:1rem;color:#374151;background:#fff;outline:none;transition:border-color .2s,background .3s}.search-input:focus{border-color:#3b82f6}.dark .search-input{background:#0f172a;border-color:#475569;color:#f1f5f9}.dark .search-input::placeholder{color:#cbd5e1}</style>
</head>
<body class="bg-gradient-to-b from-[#f6f9ff] to-white dark:from-slate-950 dark:to-slate-900 min-h-screen text-slate-800 dark:text-slate-100">
  <header class="border-b border-gray-100 dark:border-slate-800 px-0 pt-4 pb-2">
    <div class="max-w-7xl mx-auto flex items-center justify-between px-8">
      <a href="/" class="flex items-center gap-2"><span class="text-2xl font-bold text-blue-600">M</span><span class="text-xl font-semibold">book</span></a>
      <div class="relative"><input type="text" class="search-input pr-10 h-10" placeholder="Ном хайх..."><span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 dark:text-gray-400"><i class="fa-solid fa-magnifying-glass"></i></span></div>
      <div class="flex items-center gap-4">
        <button id="theme-toggle" aria-label="Theme солих" class="text-xl"><i class="fa-regular fa-moon dark:hidden"></i><i class="fa-regular fa-sun hidden dark:inline"></i></button>
        @auth <a href="{{ url('/profile') }}" class="text-blue-600 font-semibold flex items-center gap-2"><i class="fa-regular fa-user text-blue-500"></i></a>
        @else <a href="{{ route('login') }}" class="text-blue-600 font-semibold flex items-center gap-2"><i class="fa-solid fa-right-to-bracket text-blue-500"></i>Нэвтрэх</a> @endauth
      </div>
    </div>
    <nav class="max-w-7xl mx-auto px-8 mt-4 flex items-center gap-8 text-gray-700 dark:text-gray-300 font-medium">
      <a href="{{ route('home') }}" class="nav-link px-2 py-1 rounded-full hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-slate-800 dark:hover:text-blue-400 text-blue-600 dark:text-blue-400 font-semibold">Танд зориулав</a>
      <a href="{{ route('book') }}" class="nav-link px-2 py-1 rounded-full hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-slate-800 dark:hover:text-blue-400">Ном</a>
      <a href="{{ route('subscription') }}" class="nav-link px-2 py-1 rounded-full hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-slate-800 dark:hover:text-blue-400">Subscription</a>
      <a href="{{ route('authors.index') }}" class="nav-link px-2 py-1 rounded-full hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-slate-800 dark:hover:text-blue-400">Зохиолчид</a>
    </nav>
  </header>
  <main class="max-w-7xl mx-auto px-8 py-8">@yield('content')</main>
  <script>(function(){const t=document.getElementById('theme-toggle');if(!t)return;const r=document.documentElement;function c(n,v,d){try{if(v===null){document.cookie=n+'=;path=/;max-age=0';return;}const m=d?(60*60*24*d):(60*60*24*365);document.cookie=n+'='+encodeURIComponent(v)+';path=/;max-age='+m+';SameSite=Lax';}catch(_){}}function u(d){try{const m=t.querySelector('.fa-moon');const s=t.querySelector('.fa-sun');if(m)m.classList.toggle('hidden',d);if(s)s.classList.toggle('hidden',!d);t.setAttribute('aria-pressed',d?'true':'false');}catch(_){}}function setTheme(mode,reload=true){if(mode==='dark'){r.classList.add('dark');r.setAttribute('data-theme','dark');try{localStorage.setItem('theme','dark');}catch(_){ }c('theme','dark',365);u(true);}else{r.classList.remove('dark');r.setAttribute('data-theme','light');try{localStorage.setItem('theme','light');}catch(_){ }c('theme',null);u(false);}if(reload)location.reload();}u(r.classList.contains('dark'));t.addEventListener('click',()=>{const isDark=r.classList.contains('dark');setTheme(isDark?'light':'dark',true);});})();</script>
  @stack('body-scripts')
</body>
</html>
