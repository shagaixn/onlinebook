@include('include.header')

<style>
/* Theme-aware variables */
:root { --card-bg: #ffffff; }
.dark { --card-bg: #0b0b0c; }
 
/* 3D Card Hover Effect */
.card-3d { perspective: 1000px; }
.card-3d__inner {
  position: relative;
  transform-style: preserve-3d;
  transform: rotateX(var(--rx, 0deg)) rotateY(var(--ry, 0deg));
  border-radius: 1rem;
  overflow: hidden;
  background: var(--card-bg);
  transition: transform 120ms ease-out, box-shadow 200ms ease, filter 200ms ease;
  will-change: transform;
}
.card-3d:hover .card-3d__inner {
  box-shadow: 0 18px 55px rgba(0,0,0,.45);
  filter: saturate(1.05) contrast(1.02);
}

/* Media area (cover) */
.card-3d__media { position: relative; height: 14rem; }
.card-3d__cover {
  display: block;
  width: 100%;
  height: 100%;
  object-fit: cover;
  transform: translateZ(20px);
  transition: transform .25s ease;
}
.card-3d:hover .card-3d__cover { transform: translateZ(40px); }

/* Bottom shade for readability */
.card-3d__shade {
  position: absolute;
  inset: 0;
  background: linear-gradient(to top, rgba(0,0,0,.45), rgba(0,0,0,0) 50%);
  transform: translateZ(50px);
  pointer-events: none;
}

/* Shine following cursor */
.card-3d__shine {
  position: absolute;
  inset: 0;
  background: radial-gradient(600px circle at var(--mx,50%) var(--my,50%), rgba(255,255,255,.20), transparent 45%);
  mix-blend-mode: screen;
  opacity: 0;
  transition: opacity .2s ease;
  transform: translateZ(60px);
  pointer-events: none;
}
.card-3d:hover .card-3d__shine { opacity: .85; }

/* Title on image */
.card-3d__title {
  position: absolute;
  bottom: .75rem;
  left: .75rem;
  right: .75rem;
  color: #fff;
  font-weight: 700;
  letter-spacing: .03em;
  text-shadow: 0 2px 12px rgba(0,0,0,.55);
  transform: translateZ(70px);
}

/* Below-image content slightly raised */
.card-3d__content { position: relative; transform: translateZ(40px); }
/* Country badge */
.card-3d__badge {
  position: absolute;
  top: .75rem;
  right: .75rem;
  background: rgba(255,255,255,0.9);
  color: #374151;
  font-size: 0.7rem;
  font-weight: 600;
  padding: 0.25rem 0.5rem;
  border-radius: 9999px;
  transform: translateZ(80px);
}
.dark .card-3d__badge {
  background: rgba(30,41,59,0.9);
  color: #e2e8f0;
}
/* Accessibility */
@media (prefers-reduced-motion: reduce) {
  .card-3d__inner, .card-3d__cover { transition: none !important; }
}
</style>
<main class="night-sky min-h-[100svh]  max-w-9xl mx-auto px-4 py-15">
<div class="max-w-7xl mx-auto px-6 py-10">
    <div class="text-center mb-10">
        <h1 class="text-4xl font-bold mb-3 text-slate-900 dark:text-slate-100">📚 Зохиолчид</h1>
        <p class="text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">Алдарт зохиолчид болон тэдний бүтээлүүдтэй танилцана уу</p>
    </div>

    <!-- Search form -->
    <form method="GET" action="{{ route('authors.index') }}" class="mb-8 max-w-3xl mx-auto">
        <!-- Search Type Tabs -->
        <div class="flex justify-center mb-4">
            <div class="inline-flex bg-white dark:bg-slate-800 rounded-xl p-1 border border-gray-200 dark:border-slate-700">
                <button type="button" onclick="switchSearchType('authors')" id="tab-authors" class="search-tab active px-6 py-2 rounded-lg text-sm font-medium transition-all">
                    👥 Зохиолчид
                </button>
                <button type="button" onclick="switchSearchType('books')" id="tab-books" class="search-tab px-6 py-2 rounded-lg text-sm font-medium transition-all">
                    📚 Ном
                </button>
                <button type="button" onclick="switchSearchType('both')" id="tab-both" class="search-tab px-6 py-2 rounded-lg text-sm font-medium transition-all">
                    🔍 Бүгд
                </button>
            </div>
        </div>
        <input type="hidden" name="type" id="search-type" value="{{ request('type', 'authors') }}">
        
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <input 
                    type="text" 
                    name="q" 
                    id="search-input"
                    value="{{ request('q') }}"
                    placeholder="Хайх..." 
                    class="w-full pl-12 pr-4 py-3 rounded-xl border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition-all"
                />
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-medium rounded-xl transition-all shadow-lg shadow-blue-500/20 hover:shadow-blue-500/40 hover:-translate-y-0.5">
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Хайх
                    </span>
                </button>
                @if(request('q'))
                    <a href="{{ route('authors.index') }}" class="px-6 py-3 bg-slate-600 hover:bg-slate-700 dark:bg-slate-700 dark:hover:bg-slate-600 text-white font-medium rounded-xl transition-all flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Цэвэрлэх
                    </a>
                @endif
            </div>
        </div>
        @if(request('q'))
            <p class="mt-3 text-sm text-gray-600 dark:text-slate-400 text-center">
                Хайлтын үр дүн: <span class="font-semibold text-blue-600 dark:text-blue-400">"{{ request('q') }}"</span>
                <span class="text-gray-500 dark:text-slate-500">- {{ $authors->total() }} зохиолч олдлоо</span>
            </p>
        @endif
    </form>

<style>
.search-tab {
    color: #64748b;
}
.dark .search-tab {
    color: #94a3b8;
}
.search-tab:hover {
    background: rgba(59, 130, 246, 0.1);
    color: #3b82f6;
}
.search-tab.active {
    background: linear-gradient(to right, #2563eb, #9333ea);
    color: white;
    shadow: 0 4px 6px rgba(59, 130, 246, 0.2);
}
</style>

<script>
function switchSearchType(type) {
    document.getElementById('search-type').value = type;
    
    // Update active tab
    document.querySelectorAll('.search-tab').forEach(tab => {
        tab.classList.remove('active');
    });
    document.getElementById('tab-' + type).classList.add('active');
    
    // Update placeholder
    const input = document.getElementById('search-input');
    if (type === 'authors') {
        input.placeholder = 'Зохиолчийн нэрээр хайх...';
    } else if (type === 'books') {
        input.placeholder = 'Номын нэрээр хайх...';
    } else {
        input.placeholder = 'Ном эсвэл зохиолчоор хайх...';
    }
}

// Set initial state based on request
document.addEventListener('DOMContentLoaded', function() {
    const currentType = '{{ request("type", "authors") }}';
    switchSearchType(currentType);
});
</script>

    {{-- Books Results --}}
    @if(isset($books) && $books->count() > 0 && in_array(request('type'), ['books', 'both']))
        <div class="mb-12">
            <h2 class="text-2xl font-bold mb-6 text-slate-900 dark:text-slate-100 flex items-center gap-2">
                <span>📚</span> Номнууд
                <span class="text-sm font-normal text-gray-500 dark:text-gray-400">({{ $books->total() }} олдлоо)</span>
            </h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
                @foreach($books as $book)
                    <a href="{{ route('books.show', $book->id) }}" class="group block">
                        <div class="relative aspect-[2/3] rounded-lg overflow-hidden shadow-md group-hover:shadow-xl transition-all duration-300 group-hover:-translate-y-1">
                            @if($book->cover_image)
                                <img src="{{ asset('storage/' . $book->cover_image) }}" 
                                     alt="{{ $book->title }}" 
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                                    <svg class="w-16 h-16 text-white opacity-50" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white line-clamp-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                            {{ $book->title }}
                        </h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $book->author_display }}</p>
                    </a>
                @endforeach
            </div>
            <div class="mt-6">
                {{ $books->links() }}
            </div>
        </div>
    @endif

    {{-- Authors Results --}}
    @if(isset($authors) && $authors->count() === 0 && (!isset($books) || $books->count() === 0))
        <div class="p-12 text-center bg-white dark:bg-dark rounded-2xl border border-gray-100 dark:border-slate-800">
            <svg class="mx-auto h-16 w-16 text-gray-400 dark:text-slate-500 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <p class="text-gray-500 dark:text-gray-400 text-lg">Хайлтын үр дүн олдсонгүй.</p>
        </div>
    @elseif(isset($authors) && $authors->count() > 0 && in_array(request('type', 'authors'), ['authors', 'both']))
        <div>
            @if(request('type') === 'both')
                <h2 class="text-2xl font-bold mb-6 text-slate-900 dark:text-slate-100 flex items-center gap-2">
                    <span>👥</span> Зохиолчид
                    <span class="text-sm font-normal text-gray-500 dark:text-gray-400">({{ $authors->total() }} олдлоо)</span>
                </h2>
            @endif
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($authors as $author)
                <a href="{{ route('authors.show', $author->slug) }}"
                   class="card-3d block rounded-2xl border border-gray-100 dark:border-slate-800 hover:-translate-y-0.5 transition">
                    <div class="card-3d__inner bg-white dark:bg-slate-900">
                        <div class="card-3d__media">
                            @if($author->profile_image)
                              <img
                                src="{{ asset(\Illuminate\Support\Str::startsWith($author->profile_image, ['http://', 'https://', '/']) ? $author->profile_image : 'storage/' . ltrim($author->profile_image, '/') ) }}"
                                alt="{{ $author->name }}"
                                class="card-3d__cover" />
                            @else
                              <div class="card-3d__cover bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                                <svg class="w-24 h-24 text-white opacity-90" fill="currentColor" viewBox="0 0 20 20">
                                  <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                </svg>
                              </div>
                            @endif

                            <div class="card-3d__shade"></div>
                            <div class="card-3d__shine"></div>
                            <div class="card-3d__title">
                                <h2 class="text-lg">{{ $author->name }}</h2>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-10">
            {{ $authors->links() }}
        </div>
    @endif
</div>

<script>
/* Cursor-based 3D tilt + glow */
(() => {
  const supportsHover = window.matchMedia("(hover: hover) and (pointer: fine)").matches;

  document.querySelectorAll(".card-3d").forEach((card) => {
    const inner = card.querySelector(".card-3d__inner");
    if (!inner) return;

    const maxTilt = 12; // degrees

    function onMove(e) {
      const rect = card.getBoundingClientRect();
      const x = (e.clientX - rect.left) / rect.width;  // 0..1
      const y = (e.clientY - rect.top) / rect.height;  // 0..1

      const ry = (x - 0.5) * (maxTilt * 2);  // left/right
      const rx = -(y - 0.5) * (maxTilt * 2); // up/down

      inner.style.setProperty("--ry", ry.toFixed(2) + "deg");
      inner.style.setProperty("--rx", rx.toFixed(2) + "deg");

      // shine position
      inner.style.setProperty("--mx", (x * 100).toFixed(1) + "%");
      inner.style.setProperty("--my", (y * 100).toFixed(1) + "%");
    }

    function reset() {
      inner.style.setProperty("--ry", "0deg");
      inner.style.setProperty("--rx", "0deg");
    }

    if (supportsHover) {
      card.addEventListener("mousemove", onMove);
      card.addEventListener("mouseleave", reset);
    }
  });
})();
</script>
</main>
@include('include.footer')