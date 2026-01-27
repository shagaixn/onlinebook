@include('include.header')

<main class="night-sky min-h-[100svh] flex items-center justify-center px-4">
    <div class="text-center">
        <div class="mb-8 relative inline-block">
            <div class="absolute inset-0 bg-blue-500 blur-3xl opacity-20 rounded-full animate-pulse"></div>
            <svg class="w-24 h-24 text-blue-400 relative z-10 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path>
            </svg>
        </div>
        
        <h1 class="text-5xl md:text-7xl font-bold text-slate-900 dark:text-white mb-6 tracking-tight">
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-cyan-500 dark:from-blue-400 dark:to-cyan-300">Podcast</span>
        </h1>
        
        <p class="text-xl text-slate-600 dark:text-slate-300 mb-8 font-light">
            Тун удахгүй...
        </p>
        
        <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-slate-900 bg-white/10 hover:bg-slate-800 dark:hover:bg-white/10 border border-transparent dark:border-white/10 text-white transition-all duration-300 backdrop-blur-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Нүүр хуудас руу буцах
        </a>
    </div>
</main>
 Div 


@include('include.footer')
