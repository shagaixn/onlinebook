@include('include.header')
<main class="min-h-screen bg-gradient-to-b from-gray-50 to-white dark:from-slate-950 dark:to-slate-900">
<div class="max-w-5xl mx-auto px-6 py-10">
    <a href="{{ route('authors.index') }}" class="inline-flex items-center gap-2 text-blue-600 dark:text-blue-300 hover:underline mb-6">
        ← Буцах
            </a>
   
    </a>
       <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-lg border border-gray-100 dark:border-slate-800 overflow-hidden">
        {{-- Header хэсэг --}}
        <div class="relative">
            {{-- Cover gradient --}}
            <div class="h-32 bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600"></div>
            
            {{-- Profile section --}}
            <div class="flex flex-col md:flex-row items-start md:items-end gap-6 px-6 -mt-16 pb-6">
                {{-- Зураг --}}
                <div class="flex-shrink-0">
                    @if($author->profile_image)
                        <img src="{{ asset('storage/'.$author->profile_image) }}" alt="{{ $author->name }}" 
                             class="w-32 h-32 md:w-40 md:h-40 rounded-xl object-cover border-4 border-white dark:border-slate-800 shadow-lg">
                    @else
                        <div class="w-32 h-32 md:w-40 md:h-40 rounded-xl bg-gradient-to-br from-gray-200 to-gray-300 dark:from-slate-700 dark:to-slate-800 flex items-center justify-center border-4 border-white dark:border-slate-800 shadow-lg">
                            <svg class="w-16 h-16 text-gray-400 dark:text-slate-500" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                            </svg>
                        </div>
                    @endif
                </div>
                {{-- Нэр & Үндсэн мэдээлэл --}}
                <div class="flex-1 pt-4 md:pt-0">
                    <h1 class="text-3xl md:text-4xl font-bold text-slate-900 dark:text-white">{{ $author->name }}</h1>
                    @if($author->position)
                        <p class="text-blue-600 dark:text-blue-400 font-medium mt-1">{{ $author->position }}</p>
                    @endif
                    <div class="flex flex-wrap items-center gap-3 mt-3 text-sm text-gray-600 dark:text-gray-400">
                        @if($author->nationality)
                            <span class="flex items-center gap-1">
                                <span>🏛️</span> {{ $author->nationality }}
                            </span>
                        @endif
                        @if($author->country)
                            <span class="flex items-center gap-1">
                                <span>🌍</span> {{ $author->country }}
                            </span>
                        @endif
                        @if($author->birth_place)
                            <span class="flex items-center gap-1">
                                <span>📍</span> {{ $author->birth_place }}
                            </span>
                        @endif
                    </div>
                </div>
                </div>
            </div>
        </div>
           @if($author->birth_date || $author->death_date)
                    <div class="mt-3 text-sm text-gray-600 dark:text-gray-300">
                <h1 class="text-3xl font-bold text-slate-900 dark:text-white">{{ $author->name }}</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-300">{{ $author->nationality ?? 'Үндэс тодорхойгүй' }}</p>
        {{-- Үндсэн агуулга --}}
        <div class="p-6 grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Зүүн талын агуулга --}}
            <div class="lg:col-span-2 space-y-8">
                {{-- Амьдралын он сар --}}
                     <div class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-slate-800 rounded-xl">
                        <div class="flex-shrink-0 w-12 h-12 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                            <span class="text-xl">📅</span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Амьдралын он</p>
                            <p class="font-medium text-slate-800 dark:text-slate-200">
                                {{ optional($author->birth_date)->format('Y оны m сарын d') ?? 'Тодорхойгүй' }}
                                —
                                {{ optional($author->death_date)->format('Y оны m сарын d') ?? 'одоо' }}
                            </p>
                        </div>
                    </div>
                @endif
                    </div>
                {{-- Намтар --}}
                @if(!empty($author->biography))
                  <div>
                        <h2 class="text-xl font-semibold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                            <span>📖</span> Намтар
                        </h2>
                        <div class="prose prose-slate dark:prose-invert max-w-none text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-line">
                            {{ $author->biography }}
                        </div>
                    </div>
                @endif
                {{-- Алдартай бүтээлүүд --}}
                @php
                    $notableWorks = $author->notable_works ? preg_split('/\r\n|\r|\n/', $author->notable_works) : [];
                    $notableWorks = array_filter(array_map('trim', $notableWorks));
                @endphp
                @if(!empty($notableWorks))
                    <div>
                        <h2 class="text-xl font-semibold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                            <span>📚</span> Алдартай бүтээлүүд
                        </h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach($notableWorks as $work)
                                <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-slate-800 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-700 transition">
                                    <span class="flex-shrink-0 w-8 h-8 rounded bg-blue-100 dark:bg-blue-900 flex items-center justify-center text-blue-600 dark:text-blue-400">
                                        📕
                                    </span>
                                    <span class="text-gray-800 dark:text-gray-200">{{ $work }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                {{-- Намтар --}}
                @if(!empty($author->biography))
                    <div class="mt-5 text-gray-800 dark:text-gray-100 whitespace-pre-line leading-7">{{ $author->biography }}</div>
                @endif
                {{-- Шагнал, цол --}}
                    $awards = $author->awards ? preg_split('/\r\n|\r|\n/', $author->awards) : [];
                    $awards = array_filter(array_map('trim', $awards));
                @endphp
                @if(!empty($awards))
                 <div>
                        <h2 class="text-xl font-semibold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                            <span>🏆</span> Шагнал, цол
                        </h2>
                        <div class="space-y-2">
                            @foreach($awards as $award)
                                <div class="flex items-start gap-3 p-3 bg-gradient-to-r from-amber-50 to-yellow-50 dark:from-amber-900/20 dark:to-yellow-900/20 rounded-lg border border-amber-100 dark:border-amber-800/30">
                                    <span class="flex-shrink-0 text-amber-500">🥇</span>
                                    <span class="text-gray-800 dark:text-gray-200">{{ $award }}</span>
                                </div>
                            @endforeach
                        </ul>
                    </div>
                            @foreach($awards as $line)
                                <li>{{ $line }}</li>
                            @endforeach
                                  </div>
                    </div>
                @endif
            </div>
            {{-- Баруун талын sidebar --}}
            <div class="space-y-6">
                {{-- Холбоо барих --}}
                @if($author->email || !empty($author->social_links))
                    <div class="bg-gray-50 dark:bg-slate-800 rounded-xl p-5">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                            <span>📧</span> Холбоо барих
                        </h3>
                        <div class="space-y-3">
                            @if($author->email)
                                <a href="mailto:{{ $author->email }}" class="flex items-center gap-3 text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition">
                                    <span class="flex-shrink-0 w-10 h-10 rounded-lg bg-white dark:bg-slate-700 shadow-sm flex items-center justify-center">
                                        ✉️
                                    </span>
                                    <span class="text-sm truncate">{{ $author->email }}</span>
                                </a>
                            @endif
                            
                            @php $socialLinks = $author->social_links ?? []; @endphp
                            
                            @if(!empty($socialLinks['website']))
                                <a href="{{ $socialLinks['website'] }}" target="_blank" rel="noopener" class="flex items-center gap-3 text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition">
                                    <span class="flex-shrink-0 w-10 h-10 rounded-lg bg-white dark:bg-slate-700 shadow-sm flex items-center justify-center">
                                        🌐
                                    </span>
                                    <span class="text-sm">Вэбсайт</span>
                                </a>
                            @endif
                            @if(!empty($socialLinks['facebook']))
                                <a href="{{ $socialLinks['facebook'] }}" target="_blank" rel="noopener" class="flex items-center gap-3 text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition">
                                    <span class="flex-shrink-0 w-10 h-10 rounded-lg bg-blue-600 shadow-sm flex items-center justify-center text-white">
                                        <i class="fab fa-facebook-f"></i>
                                    </span>
                                    <span class="text-sm">Facebook</span>
                                </a>
                            @endif
                            @if(!empty($socialLinks['twitter']))
                                <a href="{{ $socialLinks['twitter'] }}" target="_blank" rel="noopener" class="flex items-center gap-3 text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition">
                                    <span class="flex-shrink-0 w-10 h-10 rounded-lg bg-black shadow-sm flex items-center justify-center text-white">
                                        <i class="fab fa-x-twitter"></i>
                                    </span>
                                    <span class="text-sm">Twitter / X</span>
                                </a>
                            @endif
                            @if(!empty($socialLinks['instagram']))
                                <a href="{{ $socialLinks['instagram'] }}" target="_blank" rel="noopener" class="flex items-center gap-3 text-gray-700 dark:text-gray-300 hover:text-pink-600 dark:hover:text-pink-400 transition">
                                    <span class="flex-shrink-0 w-10 h-10 rounded-lg bg-gradient-to-tr from-yellow-400 via-pink-500 to-purple-600 shadow-sm flex items-center justify-center text-white">
                                        <i class="fab fa-instagram"></i>
                                    </span>
                                    <span class="text-sm">Instagram</span>
                                </a>
                            @endif
                            @if(!empty($socialLinks['linkedin']))
                                <a href="{{ $socialLinks['linkedin'] }}" target="_blank" rel="noopener" class="flex items-center gap-3 text-gray-700 dark:text-gray-300 hover:text-blue-700 dark:hover:text-blue-400 transition">
                                    <span class="flex-shrink-0 w-10 h-10 rounded-lg bg-blue-700 shadow-sm flex items-center justify-center text-white">
                                        <i class="fab fa-linkedin-in"></i>
                                    </span>
                                    <span class="text-sm">LinkedIn</span>
                                </a>
                            @endif
                        </div>
                    </div>
                @endif
                {{-- Статистик --}}
                <div class="bg-gray-50 dark:bg-slate-800 rounded-xl p-5">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                        <span>📊</span> Статистик
                    </h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-center p-3 bg-white dark:bg-slate-700 rounded-lg">
                            <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ count($notableWorks) }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Бүтээл</p>
                        </div>
                        <div class="text-center p-3 bg-white dark:bg-slate-700 rounded-lg">
                            <p class="text-2xl font-bold text-amber-600 dark:text-amber-400"></p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Шагнал</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        </div>
    </div>
</div>
</main>
@include('include.footer')