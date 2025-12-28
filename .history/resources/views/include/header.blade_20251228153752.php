<!DOCTYPE html>
<html lang="mn" class="@if ( (isset($_COOKIE['theme']) && $_COOKIE['theme']=='dark') || (!isset($_COOKIE['theme']) && request()->cookie('theme', '') === '' && (request()->header('sec-ch-prefers-color-scheme')=='dark')) ) dark @endif">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', $title ?? 'Book Plus')</title>
  <script>tailwind = window.tailwind || {}; tailwind.config = { darkMode: 'class' };</script>
  <script>window.__FORCE_THEME_FLAG__ = @json($forceTheme ?? false);</script>
  <script id="theme-init">(function(){try{const FORCE_THEME_FLAG=typeof window!=='undefined'&&window.__FORCE_THEME_FLAG__?true:false;function getCookie(n){const v=document.cookie.match('(^|;)\\s*'+n+'\\s*=\\s*([^;]+)');return v?decodeURIComponent(v[2]):null}const cookieTheme=getCookie('theme');let saved=null;try{saved=cookieTheme||localStorage.getItem('theme')}catch(_){saved=cookieTheme||null}const prefersDark=window.matchMedia&&window.matchMedia('(prefers-color-scheme: dark)').matches;let useDark=(saved==='dark')||(!saved&&prefersDark);if(window.__FORCE_THEME_FLAG__===true){useDark=true}if(useDark){document.documentElement.classList.add('dark');document.documentElement.setAttribute('data-theme','dark')}else{document.documentElement.classList.remove('dark');document.documentElement.setAttribute('data-theme','light')}}catch(e){}})();</script>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
  @vite(['resources/css/app.css','resources/js/app.js'])
  @stack('page-scripts')
  <style>
    body{font-family:'Inter',sans-serif;transition:background-color .3s,color .3s;font-weight:700}
    body{background:#f6f9ff;color:#000} header{background:#fff;color:#000}
    .dark body{background:#0f172a;color:#fff} .dark header{background:#1e293b;color:#fff}
    .search-input{width:18rem;padding:.5rem 1rem .5rem 2.5rem;border:1px solid #d1d5db;border-radius:9999px;font-size:1rem;color:#374151;background:#fff;outline:none;transition:border-color .2s,background .3s}
    .search-input:focus{border-color:#3b82f6}
    .dark .search-input{background:#0f172a;border-color:#475569;color:#f1f5f9}
    .dark .search-input::placeholder{color:#cbd5e1}
    body,p,h1,h2,h3,h4,h5,h6,span,a,li,div,button,input,textarea,select{font-weight:700!important}
    body{color:#000}
    :not(.dark) .text-slate-50,:not(.dark) .text-slate-100,:not(.dark) .text-slate-200,:not(.dark) .text-slate-300,:not(.dark) .text-slate-400,
    :not(.dark) .text-gray-50,:not(.dark) .text-gray-100,:not(.dark) .text-gray-200,:not(.dark) .text-gray-300,:not(.dark) .text-gray-400,
    :not(.dark) .text-white{color:#000!important}
    .dark body{color:#fff}
    .dark .text-black,.dark .text-slate-950,.dark .text-slate-900,.dark .text-slate-800,.dark .text-slate-700,.dark .text-slate-600,.dark .text-slate-500,
    .dark .text-gray-950,.dark .text-gray-900,.dark .text-gray-800,.dark .text-gray-700,.dark .text-gray-600,.dark .text-gray-500,
    .dark .text-zinc-950,.dark .text-zinc-900,.dark .text-zinc-800,.dark .text-zinc-700,.dark .text-zinc-600,.dark .text-zinc-500,
    .dark .text-neutral-950,.dark .text-neutral-900,.dark .text-neutral-800,.dark .text-neutral-700,.dark .text-neutral-600,.dark .text-neutral-500,
    .dark .text-stone-950,.dark .text-stone-900,.dark .text-stone-800,.dark .text-stone-700,.dark .text-stone-600,.dark .text-stone-500{color:#fff!important}
  </style>
</head>
<body class="bg-gradient-to-b from-[#f6f9ff] to-white dark:from-slate-950 dark:to-slate-900 min-h-screen text-black dark:text-white">

  <!-- Header -->
  <!-- max-w-7xl -> max-w-screen-2xl болголоо -->
  <header class="sticky top-0 z-50 border-b border-gray-100 dark:border-slate-800 px-0 pt-4 pb-2 bg-white dark:bg-slate-900">
    <div class="max-w-screen-2xl mx-auto flex items-center justify-between px-8">
      <a href="/" class="flex items-center gap-2">
        <span class="text-2xl font-bold text-blue-600">Book</span>
        <span class="text-xl font-semibold">Plus</span>
      </a>

      <form class="relative" action="{{ route('book') }}" method="GET">
        <input type="text" name="q" value="{{ request('q') }}" class="search-input h-10" placeholder="Номын нэрээр хайх..." aria-label="Ном хайх">
        <button type="submit" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-400 hover:text-blue-500 transition-colors" aria-label="Хайх">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
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
    <!-- Энд мөн max-w-7xl -> max-w-screen-2xl -->
    <nav class="max-w-screen-2xl mx-auto px-8 mt-4 flex items-center gap-8 text-gray-700 dark:text-gray-300 font-medium">
      <a href="{{ route('home') }}" class="nav-link px-2 py-1 rounded-full hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-slate-800 dark:hover:text-blue-400 text-blue-600 dark:text-blue-400 font-semibold">Танд зориулав</a>
      <a href="{{ route('book') }}" class="nav-link px-2 py-1 rounded-full hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-slate-800 dark:hover:text-blue-400">Ном</a>
      <a href="{{ route('subscription') }}" class="nav-link px-2 py-1 rounded-full hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-slate-800 dark:hover:text-blue-400">Subscription</a>
      <a href="{{ route('authors.index') }}" class="nav-link px-2 py-1 rounded-full hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-slate-800 dark:hover:text-blue-400">Зохиолчид</a>
      <a href="{{ route('podcast') }}" class="nav-link px-2 py-1 rounded-full hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-slate-800 dark:hover:text-blue-400">Podcast</a>
      <a href="{{ route('manga') }}" class="nav-link px-2 py-1 rounded-full hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-slate-800 dark:hover:text-blue-400">Manga</a>
    </nav>
  </header>

  <script>
    (function(){const t=document.getElementById('theme-toggle');if(!t)return;const r=document.documentElement;function setCookie(n,v,d){const m=d?(60*60*24*d):(60*60*24*365);document.cookie=n+'='+encodeURIComponent(v)+';path=/;max-age='+m+';SameSite=Lax'}function setTheme(m){if(m==='dark'){r.classList.add('dark');r.setAttribute('data-theme','dark');localStorage.setItem('theme','dark');setCookie('theme','dark',365)}else{r.classList.remove('dark');r.setAttribute('data-theme','light');localStorage.setItem('theme','light');setCookie('theme','light',365)}}const saved=localStorage.getItem('theme')||(document.cookie.match('(^|;)\\s*theme\\s*=\\s*([^;]+)')?decodeURIComponent(document.cookie.match('(^|;)\\s*theme\\s*=\\s*([^;]+)')[2]):null);if(saved){setTheme(saved)}t.addEventListener('click',(e)=>{e.preventDefault();const isDark=r.classList.contains('dark');setTheme(isDark?'light':'dark')})})();
    (function(){const h=document.querySelector('header');if(!h)return;function onScroll(){if(window.scrollY>0){h.classList.add('shadow-sm')}else{h.classList.remove('shadow-sm')}}window.addEventListener('scroll',onScroll,{passive:true});onScroll()})();
  </script>
</body>
</html>