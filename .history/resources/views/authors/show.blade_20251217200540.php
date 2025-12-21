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

@php
  $notableWorks = $author->notable_works ? preg_split('/\r\n|\r|\n/', $author->notable_works) : [];
  $notableWorks = array_filter(array_map('trim', $notableWorks));
  $awards = $author->awards ? preg_split('/\r\n|\r|\n/', $author->awards) : [];
  $awards = array_filter(array_map('trim', $awards));
  $socialLinks = $author->social_links ?? [];
@endphp

<main class="night-sky min-h-screen bg-neutral-50">
  <div class="night-sky max-w-5xl mx-auto px-6 py-10">
    <a href="{{ route('authors.index') }}" class="inline-flex items-center gap-2 text-blue-600 hover:underline mb-6">
      ← Зохиолчид руу буцах
    </a>
    <div class="bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden">
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
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900">{{ $author->name }}</h1>
            @if($author->position)
              <p class="text-blue-600 font-medium mt-1">{{ $author->position }}</p>
            @endif
            <div class="flex flex-wrap items-center gap-4 mt-3 text-sm text-neutral-700">
              @if($author->nationality)
                <span class="flex items-center gap-1"><span>🇲🇳</span>{{ $author->nationality }}</span>
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
            <div class="flex items-center gap-4 p-4 bg-neutral-100 rounded-xl mb-3">
              <span class="text-2xl inline-block bg-white w-12 h-12 rounded-full flex items-center justify-center border border-gray-300">📅</span>
              <div>
                <span class="text-gray-500 text-sm">Амьдралын он</span>
                <div class="font-semibold text-gray-900">
                  {{ optional($author->birth_date)->format('Y оны m сарын d') ?? 'Тодорхойгүй' }} —
                  {{ optional($author->death_date)->format('Y оны m сарын d') ?? 'Одоо' }}
                </div>
              </div>
            </div>
          @endif

          {{-- Biography --}}
          @if(!empty($author->biography))
            <div>
              <h2 class="text-base font-semibold text-gray-800 mb-2 flex items-center gap-2">
                <span>📖</span> Намтар
              </h2>
              <div class="prose prose-slate max-w-none text-gray-700 leading-relaxed whitespace-pre-line">
                {{ $author->biography }}
              </div>
            </div>
          @endif
          {{-- Notable Works --}}
          @if(!empty($notableWorks))
            <div>
              <h2 class="text-base font-semibold text-gray-800 mb-2 flex items-center gap-2">
                <span>📚</span> Алдартай бүтээлүүд
              </h2>
              <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-3">
                @foreach($notableWorks as $work)
                  <div class="flex items-center gap-3 p-2 bg-neutral-100 rounded hover:bg-neutral-200 transition">
                    <span class="inline-block bg-blue-100 w-8 h-8 flex items-center justify-center rounded text-blue-600 font-bold">📙</span>
                    <span class="text-gray-800">{{ $work }}</span>
                  </div>
                @endforeach
              </div>
            </div>
          @endif
          {{-- Awards --}}
          @if(!empty($awards))
            <div>
              <h2 class="text-base font-semibold text-gray-800 mb-2 flex items-center gap-2">
                <span>🏆</span> Шагнал, цол
              </h2>
              <div class="space-y-2">
                @foreach($awards as $award)
                  <div class="flex items-center gap-3 p-2 bg-amber-50 rounded border border-amber-100">
                    <span class="text-amber-500">🥇</span>
                    <span class="text-gray-800">{{ $award }}</span>
                  </div>
                @endforeach
              </div>
            </div>
          @endif
        </div>
        {{-- Sidebar --}}
        <div class="flex flex-col space-y-6">
          {{-- Contact --}}
          @if($author->email || !empty($socialLinks))
            <div class="bg-neutral-50 rounded-xl p-5">
              <h3 class="text-base font-semibold text-gray-800 mb-3 flex items-center gap-2">
                <span>📧</span> Холбоо барих
              </h3>
              <div class="space-y-3">
                @if($author->email)
                  <a href="mailto:{{ $author->email }}" class="flex items-center gap-2 text-gray-700 hover:text-blue-600 transition">
                    <span class="bg-white w-8 h-8 rounded-lg flex items-center justify-center border border-gray-200">✉️</span>
                    <span class="text-sm truncate">{{ $author->email }}</span>
                  </a>
                @endif
                @if(!empty($socialLinks['website']))
                  <a href="{{ $socialLinks['website'] }}" target="_blank" rel="noopener" class="flex items-center gap-2 text-gray-700 hover:text-blue-600 transition">
                    <span class="bg-white w-8 h-8 rounded-lg flex items-center justify-center border border-gray-200">🌐</span>
                    <span class="text-sm">Вэбсайт</span>
                  </a>
                @endif
                @if(!empty($socialLinks['facebook']))
                  <a href="{{ $socialLinks['facebook'] }}" target="_blank" rel="noopener" class="flex items-center gap-2 text-gray-700 hover:text-blue-600 transition">
                    <span class="bg-blue-600 w-8 h-8 rounded-lg flex items-center justify-center text-white"><i class="fab fa-facebook-f"></i></span>
                    <span class="text-sm">Facebook</span>
                  </a>
                @endif
                @if(!empty($socialLinks['twitter']))
                  <a href="{{ $socialLinks['twitter'] }}" target="_blank" rel="noopener" class="flex items-center gap-2 text-gray-700 hover:text-blue-600 transition">
                    <span class="bg-black w-8 h-8 rounded-lg flex items-center justify-center text-white"><i class="fab fa-x-twitter"></i></span>
                    <span class="text-sm">Twitter / X</span>
                  </a>
                @endif
                @if(!empty($socialLinks['instagram']))
                  <a href="{{ $socialLinks['instagram'] }}" target="_blank" rel="noopener" class="flex items-center gap-2 text-gray-700 hover:text-pink-600 transition">
                    <span class="bg-gradient-to-tr from-yellow-400 via-pink-500 to-purple-600 w-8 h-8 rounded-lg flex items-center justify-center text-white"><i class="fab fa-instagram"></i></span>
                    <span class="text-sm">Instagram</span>
                  </a>
                @endif
                @if(!empty($socialLinks['linkedin']))
                  <a href="{{ $socialLinks['linkedin'] }}" target="_blank" rel="noopener" class="flex items-center gap-2 text-gray-700 hover:text-blue-700 transition">
                    <span class="bg-blue-700 w-8 h-8 rounded-lg flex items-center justify-center text-white"><i class="fab fa-linkedin-in"></i></span>
                    <span class="text-sm">LinkedIn</span>
                  </a>
                @endif
              </div>
            </div>
          @endif
          {{-- Stat --}}
          <div class="bg-neutral-50 rounded-xl p-5">
            <h3 class="text-base font-semibold text-gray-800 mb-3 flex items-center gap-2">
              <span>📊</span> Статистик
            </h3>
            <div class="grid grid-cols-2 gap-4">
              <div class="text-center p-2 bg-white rounded-lg border border-gray-100">
                 <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $author->notable_works_count }}</p>
                <p class="text-xs text-gray-500">Бүтээл</p>
              </div>
              <div class="text-center p-2 bg-white rounded-lg border border-gray-100">
                 <p class="text-2xl font-bold text-amber-600 dark:text-amber-400">{{ $author->awards_count }}</p>
                <p class="text-xs text-gray-500">Шагнал</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>

@include('include.footer')