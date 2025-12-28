{{-- 
  Improved Author Profile Blade view
  Styles based on the reference image ("zurag deerh shig styletai bolgo")
  - Modern card with shadow
  - Gradient cover
  - Simple icon blocks
  - Section spacing and clear background
  - Stats flex grid
--}}

@include('include.header')

<script>
/* Ensure dark/light theme is applied on this page based on saved preference or system setting */
(() => {
  const KEY = 'theme-preference';
  const prefersDark = window.matchMedia('(prefers-color-scheme: dark)');
  const saved = localStorage.getItem(KEY);
  const theme = saved || (prefersDark.matches ? 'dark' : 'light');
  document.documentElement.classList.toggle('dark', theme === 'dark');
})();
</script>

<style>
  /* Minimal night sky background for the author profile */
  .night-sky {
    position: relative;
    isolation: isolate;
    overflow: hidden;
    background-color: #f8fafc; /* Light mode bg */
  }
  .dark .night-sky {
    background-color: #050816; /* Dark mode bg */
  }
  .night-sky::before {
    content: "";
    position: absolute;
    inset: -25vh -20vw auto -20vw;
    height: 60vh;
    z-index: -2;
    background:
      radial-gradient(900px 480px at 50% 0%, rgba(99,102,241,0.35), transparent 60%),
      radial-gradient(600px 420px at 10% 15%, rgba(34,211,238,0.2), transparent 55%),
      radial-gradient(520px 360px at 90% 5%, rgba(59,130,246,0.2), transparent 55%);
    pointer-events: none;
    opacity: 0.5; /* Lower opacity for light mode */
  }
  .dark .night-sky::before {
    opacity: 1;
  }
  .night-sky::after {
    content: "";
    position: absolute;
    inset: -10vh -10vw 0 -10vw;
    z-index: -2;
    opacity: 0.45;
    pointer-events: none;
    background-image:
      radial-gradient(2px 2px at 20px 30px, rgba(255,255,255,0.6) 1px, transparent 2px),
      radial-gradient(2px 2px at 140px 80px, rgba(255,255,255,0.55) 1px, transparent 2px),
      radial-gradient(2px 2px at 260px 140px, rgba(255,255,255,0.5) 1px, transparent 2px),
      radial-gradient(2px 2px at 80px 200px, rgba(255,255,255,0.55) 1px, transparent 2px),
      radial-gradient(2px 2px at 200px 20px, rgba(255,255,255,0.6) 1px, transparent 2px);
    background-size: 300px 300px, 340px 340px, 380px 380px, 420px 420px, 460px 460px;
    animation: twinkle 9s linear infinite;
  }
  @keyframes twinkle {
    0%, 100% { opacity: 0.45; }
    50% { opacity: 0.25; }
  }
  .night-sky__orbs {
    pointer-events: none;
    position: absolute;
    inset: 0;
    z-index: -1;
  }
  .night-sky__orb {
    position: absolute;
    width: 18rem;
    height: 18rem;
    border-radius: 9999px;
    filter: blur(60px);
    opacity: 0.45;
    mix-blend-mode: screen;
  }
  .night-sky__orb--violet {
    top: 5%;
    left: 10%;
    background: radial-gradient(circle, rgba(168,85,247,0.55), transparent 65%);
  }
  .night-sky__orb--cyan {
    top: 15%;
    right: 12%;
    background: radial-gradient(circle, rgba(6,182,212,0.45), transparent 70%);
  }
  .night-sky__orb--pink {
    bottom: 0;
    left: 35%;
    background: radial-gradient(circle, rgba(244,114,182,0.35), transparent 65%);
  }
  @media (prefers-reduced-motion: reduce) {
    .night-sky::after { animation: none; }
  }
</style>

@php
  $notableWorks = $author->notable_works ? preg_split('/\r\n|\r|\n/', $author->notable_works) : [];
  $notableWorks = array_filter(array_map('trim', $notableWorks));
  $awards = $author->awards ? preg_split('/\r\n|\r|\n/', $author->awards) : [];
  $awards = array_filter(array_map('trim', $awards));
  $socialLinks = $author->social_links ?? [];
@endphp

<main class="night-sky relative overflow-hidden min-h-screen bg-neutral-50">
  <div class="night-sky__orbs" aria-hidden="true">
    <span class="night-sky__orb night-sky__orb--violet"></span>
    <span class="night-sky__orb night-sky__orb--cyan"></span>
    <span class="night-sky__orb night-sky__orb--pink"></span>
  </div>
  <div class=" max-w-5xl mx-auto px-6 py-10">
    <a href="{{ route('authors.index') }}" class="inline-flex items-center gap-2 text-blue-600 hover:underline mb-6">
      ←  буцах
    </a>
    <div class=" dark:bg-white/5 backdrop-blur-lg text-slate-900 dark:text-slate-100 rounded-2xl shadow-2xl border border-gray-200 dark:border-white/10 overflow-hidden">
      {{-- Gradient Cover --}}
      <div class="relative">
        <div class="h-32 bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600"></div>
        <div class="flex flex-col md:flex-row gap-6 px-6 -mt-16 pb-6 items-end">
          <div class="flex-shrink-0">
            @if($author->profile_image)
              <img src="{{ asset('storage/'.$author->profile_image) }}" alt="{{ $author->name }}"
                class="w-32 h-32 md:w-40 md:h-40 rounded-xl object-cover border-4 border-white shadow-lg bg-gray-100" />
            @else
              <div class="w-32 h-32 md:w-40 md:h-40 rounded-xl bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center border-4 border-white shadow-lg">
                <svg class="w-16 h-16 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                </svg>
              </div>
            @endif
          </div>
          <div class="flex-1 pt-4">
            <h1 class="text-3xl md:text-4xl font-bold text-slate-900 dark:text-white">{{ $author->name }}</h1>
            @if($author->position)
              <p class="text-blue-600 dark:text-cyan-300 font-medium mt-1">{{ $author->position }}</p>
            @endif
            <div class="flex flex-wrap items-center gap-4 mt-3 text-sm text-slate-600 dark:text-slate-300">
              @if($author->nationality)
                <span class="flex items-center gap-1"><span></span>{{ $author->nationality }}</span>
              @endif
              @if($author->country)
                <span class="flex items-center gap-1"><span>🌏</span>{{ $author->country }}</span>
              @endif
              @if($author->birth_place)
                <span class="flex items-center gap-1"><span>📍</span>{{ $author->birth_place }}</span>
              @endif
            </div>
          </div>
        </div>
      </div>
      {{-- Card Body --}}
      <div class="p-8 grid grid-cols-1 md:grid-cols-3 gap-10">
        <div class="space-y-6 md:col-span-2">
          {{-- Date block --}}
          @if($author->birth_date || $author->death_date)
            <div class="flex items-center gap-4 p-4 bg-dark dark:bg-white/10 border border-gray-200 dark:border-white/10 rounded-xl mb-3">
              <span class="text-2xl inline-block bg-dark dark:bg-white/10 text-slate-900 dark:text-white w-12 h-12 rounded-full flex items-center justify-center border border-gray-200 dark:border-white/20">📅</span>
              <div>
                <span class="text-slate-500 dark:text-slate-300 text-sm">Амьдралын он</span>
                <div class="font-semibold text-slate-900 dark:text-white">
                  {{ optional($author->birth_date)->format('Y оны m сарын d') ?? 'Тодорхойгүй' }} —
                  {{ optional($author->death_date)->format('Y оны m сарын d') ?? 'Одоо' }}
                </div>
              </div>
            </div>
          @endif

          {{-- Biography --}}
          @if(!empty($author->biography))
            <div>
              <h2 class="text-base font-semibold text-slate-900 dark:text-white mb-2 flex items-center gap-2">
                <span>📖</span> Намтар
              </h2>
              <div class="prose dark:prose-invert max-w-none text-slate-600 dark:text-slate-200 leading-relaxed whitespace-pre-line">
                {{ $author->biography }}
              </div>
            </div>
          @endif
          {{-- Notable Works --}}
          @if(!empty($notableWorks))
            <div>
              <h2 class="text-base font-semibold text-slate-900 dark:text-white mb-2 flex items-center gap-2">
                <span>📚</span> Алдартай бүтээлүүд
              </h2>
              <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-3">
                @foreach($notableWorks as $work)
                  <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-lg hover:bg-gray-100 dark:hover:bg-white/10 transition">
                    <span class="inline-block bg-blue-100 dark:bg-blue-500/20 w-8 h-8 flex items-center justify-center rounded text-blue-600 dark:text-blue-200 font-bold">📙</span>
                    <span class="text-slate-700 dark:text-slate-100">{{ $work }}</span>
                  </div>
                @endforeach
              </div>
            </div>
          @endif
          {{-- Awards --}}
          @if(!empty($awards))
            <div>
              <h2 class="text-base font-semibold text-slate-900 dark:text-white mb-2 flex items-center gap-2">
                <span>🏆</span> Шагнал, цол
              </h2>
              <div class="space-y-2">
                @foreach($awards as $award)
                  <div class="flex items-center gap-3 p-3 bg-amber-50 dark:bg-amber-400/10 rounded border border-amber-200 dark:border-amber-200/30">
                    <span class="text-amber-500 dark:text-amber-300">🥇</span>
                    <span class="text-slate-700 dark:text-slate-100">{{ $award }}</span>
                  </div>
                @endforeach
              </div>
            </div>
          @endif
        </div>
        {{-- Sidebar --}}
        <div class="flex flex-col space-y-6">
          <div class="bg-white dark:bg-white/5 border border-gray-200 dark:border-dark/10 rounded-xl p-5">
            <h3 class="text-base font-semibold text-slate-900 dark:text-white mb-3 flex items-center gap-2">
              <span>📊</span> Статистик
            </h3>
            <div class="grid grid-cols-2 gap-4">
              <div class="text-center p-3 bg-dark dark:bg-white/10 rounded-lg border border-gray-200 dark:border-white/10">
                 <p class="text-2xl font-bold text-blue-600 dark:text-cyan-300">{{ $author->notable_works_count }}</p>
                <p class="text-xs text-slate-500 dark:text-slate-300">Бүтээл</p>
              </div>
              <div class="text-center p-3 bg-dark dark:bg-white/10 rounded-lg border border-gray-200 dark:border-white/10">
                 <p class="text-2xl font-bold text-amber-500 dark:text-amber-300">{{ $author->awards_count }}</p>
                <p class="text-xs text-slate-500 dark:text-slate-300">Шагнал</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>

@include('include.footer')