@include('include.header')

<style>
  @media (prefers-reduced-motion: reduce) {
    .animate-spin-slow,
    .float-slow,
    .animate-marquee {
      animation:  none !important;
    }
  }

  .scrollbar-hide: :-webkit-scrollbar {
    display: none;
  }

  .scrollbar-hide {
    -ms-overflow-style:  none;
    scrollbar-width: none;
  }

  /* Performance improvement: Use will-change sparingly */
  .group-hover\:scale-110:hover {
    will-change: transform;
  }

  @keyframes marquee {
    0% { transform: translateX(0); }
    100% { transform: translateX(-50%); }
  }

  .animate-marquee {
    display: flex;
    width: max-content;
    animation: marquee 40s linear infinite;
  }

  .animate-marquee:hover {
    animation-play-state: paused;
  }
</style>

@php
  $wishlistIds = $wishlistIds ?? [];
  $currentUser = auth()->user();
  $isAuthenticated = $currentUser !== null;
@endphp

<main class="night-sky min-h-[100svh] max-w-9xl mx-auto px-4 py-16 animate-fade-in">

  {{-- ================= HERO ================= --}}
  <section class="relative max-w-5xl mx-auto px-6 pt-24 pb-16 text-center z-10">
    <div class="absolute inset-0 -z-10 bg-[radial-gradient(circle_at_top,rgba(56,189,248,0.25),transparent_55%)]"></div>
    
    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white dark:bg-white/10 border border-slate-200 dark:border-white/20 backdrop-blur text-xs font-medium text-cyan-600 dark:text-cyan-300 mb-6 shadow-sm dark:shadow-none">
      <span class="relative flex h-2 w-2">
        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-cyan-400 opacity-75"></span>
        <span class="relative inline-flex rounded-full h-2 w-2 bg-cyan-500"></span>
      </span>
      Шинэ номууд нэмэгдлээ
    </div>

    <h1 class="text-5xl sm:text-6xl md:text-7xl font-black tracking-tight bg-gradient-to-br from-slate-900 via-cyan-600 to-blue-600 dark:from-white dark:via-cyan-200 dark:to-blue-400 bg-clip-text text-transparent drop-shadow-sm dark:drop-shadow-[0_10px_40px_rgba(0,0,0,0.6)]">
      #Мэдрэмж,<br class="hidden sm:block"> Мэдлэгийг өнгөлнө. 
    </h1>

    <p class="mt-6 text-lg text-slate-600 dark:text-slate-300 max-w-2xl mx-auto">
      Хил хязгаар, цаг хугацааг үл харгалзан мэдлэгийг түгээх <span class="font-semibold text-slate-900 dark:text-white">Book Plus</span>
    </p>

    <div class="mt-10 flex flex-col sm:flex-row gap-4 justify-center">
      @if($isAuthenticated)
        <a href="{{ route('subscription') }}" class="px-8 py-4 rounded-full bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-bold hover:scale-105 transition shadow-lg focus:outline-none focus:ring-2 focus:ring-cyan-400">
          Subscription →
        </a>
      @else
        <a href="{{ route('login') }}" class="px-8 py-4 rounded-full bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-bold hover:scale-105 transition shadow-lg focus:outline-none focus:ring-2 focus:ring-cyan-400">
          Нэвтрэх →
        </a>
      @endif
      
      <a href="{{ route('book') }}" class="px-8 py-4 rounded-full bg-white dark:bg-white/10 text-slate-900 dark:text-white border border-slate-200 dark:border-white/30 backdrop-blur hover:bg-slate-50 dark:hover:bg-white/20 transition focus:outline-none focus:ring-2 focus:ring-cyan-400 shadow-sm dark:shadow-none">
        Номуудыг үзэх
      </a>
    </div>
  </section>

  {{-- ================= CONTINUE READING ================= --}}
  @if($continueReading)
    <section class="max-w-6xl mx-auto px-6 mt-12">
      <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-6 flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-cyan-500 dark:text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
        </svg>
        Үргэлжлүүлэн унших
      </h2>

      <div class="bg-white dark:bg-white/5 backdrop-blur border border-slate-200 dark:border-white/10 rounded-2xl p-6 flex flex-col sm:flex-row gap-6 items-center hover:border-cyan-400/30 transition group shadow-sm dark:shadow-none">
        <div class="w-24 h-36 flex-shrink-0 rounded-lg overflow-hidden shadow-lg">
          <img 
            src="{{ $continueReading->book->cover_image ?  asset('storage/'.$continueReading->book->cover_image) : asset('images/placeholder-book.png') }}" 
            alt="{{ $continueReading->book->title }} cover"
            class="w-full h-full object-cover group-hover:scale-110 transition duration-500"
            loading="lazy">
        </div>

        <div class="flex-1 w-full">
          <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-1">{{ $continueReading->book->title }}</h3>
          <p class="text-slate-500 dark:text-slate-400 text-sm mb-4">Хуудас: {{ $continueReading->current_page }}</p>
          
          <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-2.5 mb-4" role="progressbar" aria-valuenow="{{ $continueReading->percentage }}" aria-valuemin="0" aria-valuemax="100">
            <div class="bg-cyan-500 dark:bg-cyan-400 h-2.5 rounded-full transition-all" style="width: {{ $continueReading->percentage }}%"></div>
          </div>

          <a href="{{ route('books.read', $continueReading->book_id) }}" class="inline-block bg-cyan-500 hover:bg-cyan-400 text-white dark:text-slate-900 font-bold py-2 px-6 rounded-full transition focus:outline-none focus:ring-2 focus:ring-cyan-300">
            Үргэлжлүүлэх
          </a>
        </div>
      </div>
    </section>
  @endif

  {{-- ================= CATEGORIES ================= --}}
  
    

  {{-- ================= WISHLIST BOOKS ================= --}}
  @if(isset($wishlistBooks) && $wishlistBooks->count() > 0)
    <section class="max-w-6xl mx-auto px-6 mt-24">
      <h2 class="text-2xl font-bold text-slate-90 dark:text-white mb-6 flex items-center gap-2">
        <span aria-hidden="true">❤️</span> Таны хадгалсан номууд
      </h2>

      <div class="flex overflow-x-auto gap-6 pb-8 scrollbar-hide snap-x" role="region" aria-label="Wishlist books carousel">
        @foreach($wishlistBooks as $book)
          <div class="min-w-[180px] snap-start">
            <a href="{{ route('books.show', $book->id) }}" class="block group focus:outline-none focus:ring-2 focus:ring-pink-400 rounded-xl">
              <div class="relative aspect-[2/3] rounded-xl overflow-hidden mb-3 border border-slate-200 dark:border-white/10 group-hover:border-pink-500/50 group-focus:border-pink-400 transition">
                <img 
                  src="{{ $book->cover_image ? asset('storage/'.$book->cover_image) : asset('images/placeholder-book.png') }}" 
                  alt="{{ $book->title }}"
                  class="w-full h-full object-cover transition duration-500 group-hover:scale-110"
                  loading="lazy">
              </div>
              <h3 class="text-slate-900 dark:text-white font-semibold truncate group-hover:text-pink-500 dark:group-hover:text-pink-400 transition">{{ $book->title }}</h3>
              <p class="text-slate-500 dark:text-slate-400 text-sm truncate">{{ $book->author ??  $book->authorModel?->name ??  'Unknown' }}</p>
            </a>
          </div>
        @endforeach
      </div>
    </section>
  @endif

  {{-- ================= TOP RATED ================= --}}
  <section class="max-w-6xl mx-auto px-6 mt-24">
    <div class="flex justify-between items-end mb-6">
      <h2 class="text-3xl font-bold text-slate-900 dark:text-white">Шилдэг үнэлгээтэй</h2>
      <a href="{{ route('book', ['sort' => 'rating']) }}" class="text-cyan-600 dark:text-cyan-300 hover:underline text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400 rounded px-2">
        Бүгдийг харах
      </a>
    </div>

    <div class="relative overflow-hidden w-full">
      <div class="flex gap-6 animate-marquee w-max hover:[animation-play-state:paused]">
        @if($topRatedBooks->isNotEmpty())
          @foreach(range(1, 2) as $i)
            @foreach($topRatedBooks as $book)
              <div class="w-[200px] flex-shrink-0">
                <a href="{{ route('books.show', $book->id) }}" class="block group focus:outline-none focus:ring-2 focus:ring-yellow-400 rounded-xl">
                  <div class="relative aspect-[2/3] rounded-xl overflow-hidden mb-3">
                    <img 
                      src="{{ $book->cover_image ?  asset('storage/'.$book->cover_image) : asset('images/placeholder-book.png') }}" 
                      alt="{{ $book->title }}"
                      class="w-full h-full object-cover transition duration-500 group-hover:scale-110"
                      loading="lazy">
                    <div class="absolute top-2 right-2 bg-black/60 backdrop-blur px-2 py-1 rounded-lg flex items-center gap-1">
                      <span class="text-yellow-400 text-xs" aria-hidden="true">★</span>
                      <span class="text-white text-xs font-bold">{{ number_format($book->reviews_avg_rating ??  0, 1) }}</span>
                    </div>
                  </div>
                  <h3 class="text-slate-900 dark:text-white font-semibold truncate group-hover:text-cyan-600 dark:group-hover:text-cyan-300 transition">{{ $book->title }}</h3>
                  <p class="text-slate-500 dark:text-slate-400 text-sm truncate">{{ $book->author ?? $book->authorModel?->name ?? 'Unknown' }}</p>
                </a>
              </div>
            @endforeach
          @endforeach
        @else
          <div class="w-full text-center py-12 text-slate-500">Одоогоор ном байхгүй байна.</div>
        @endif
      </div>
    </div>
  </section>

  {{-- ================= NEW BOOKS ================= --}}
  <section class="max-w-6xl mx-auto px-6 mt-12 mb-24">
    <div class="flex justify-between items-end mb-6">
      <h2 class="text-3xl font-bold text-slate-900 dark:text-white">Шинээр нэмэгдсэн</h2>
      <a href="{{ route('book') }}" class="text-cyan-600 dark:text-cyan-300 hover:underline text-sm focus:outline-none focus: ring-2 focus:ring-cyan-400 rounded px-2">
        Бүгдийг харах
      </a>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
      @forelse($newBooks as $book)
        @php 
            $inWishlist = in_array($book->id, $wishlistIds); 
            $isNew = $book->created_at && $book->created_at->diffInHours(now()) < 24;
        @endphp
        
        <div class="group relative" data-book-id="{{ $book->id }}">
          <a href="{{ route('books.show', $book->id) }}" class="block focus:outline-none">
            <!-- Image Container -->
            <div class="relative aspect-[2/3] rounded-xl overflow-hidden mb-3 shadow-md transition-all duration-300 group-hover:shadow-xl group-hover:-translate-y-1">
                <!-- Badge -->
                @if($isNew)
                    <div class="absolute top-0 left-0 bg-red-500 text-white text-[10px] font-bold px-2 py-1 rounded-br-lg z-10 uppercase tracking-wider">
                        Latest 24hours
                    </div>
                @else
                    <div class="absolute top-0 left-0 bg-slate-800/80 backdrop-blur text-white text-[10px] font-bold px-2 py-1 rounded-br-lg z-10 uppercase tracking-wider">
                        {{ $book->created_at ? $book->created_at->format('m.d D') : 'N/A' }}
                    </div>
                @endif

                <img 
                    src="{{ $book->cover_image ? asset('storage/'.$book->cover_image) : asset('images/placeholder-book.png') }}" 
                    alt="{{ $book->title }}"
                    class="w-full h-full object-cover transition duration-500 group-hover:scale-110"
                    loading="lazy">
                
                <!-- Gradient Overlay -->
                <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            </div>

            <!-- Content -->
            <div>
                <h3 class="font-bold text-slate-900 dark:text-white text-base leading-tight mb-1 line-clamp-1 group-hover:text-cyan-600 dark:group-hover:text-cyan-400 transition-colors">
                    {{ $book->title }}
                </h3>
                <p class="text-slate-500 dark:text-slate-400 text-xs font-medium mb-2 line-clamp-1">
                    {{ $book->author ?? $book->authorModel?->name ?? 'Unknown' }}
                </p>
                
                <div class="flex items-center gap-3 text-xs">
                    <span class="font-bold text-slate-900 dark:text-white">
                        #{{ str_pad($loop->iteration, 3, '0', STR_PAD_LEFT) }}
                    </span>
                    <span class="text-slate-500 dark:text-slate-400">
                        {{ number_format($book->price) }}₮
                    </span>
                </div>
            </div>
          </a>
        </div>
      @empty
        <div class="col-span-full text-center py-12 text-slate-500">Одоогоор ном байхгүй байна.</div>
      @endforelse
    </div>
  </section>

  {{-- ================= AUTHORS ================= --}}
  <section class="max-w-6xl mx-auto px-6 mt-24 mb-24">
    <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-8 text-center">Онцлох зохиолчид</h2>
    <div class="flex flex-wrap justify-center gap-8">
      @forelse($featuredAuthors as $author)
        <a 
          href="{{ route('authors.show', $author->slug ?? $author->id) }}" 
          class="group flex flex-col items-center focus:outline-none focus:ring-2 focus:ring-cyan-400 rounded-full px-4 py-4">
          <div class="w-24 h-24 rounded-full overflow-hidden border-2 border-slate-200 dark:border-white/20 group-hover:border-cyan-400 group-focus:border-cyan-400 transition shadow-lg mb-3">
            <img 
              src="{{ $author->image ? asset('storage/'.$author->image) : 'https://ui-avatars.com/api/? name='.urlencode($author->name).'&background=random' }}" 
              alt="{{ $author->name }}"
              class="w-full h-full object-cover group-hover:scale-110 transition duration-500"
              loading="lazy">
          </div>
          <span class="text-slate-600 dark:text-slate-300 font-medium group-hover:text-slate-900 dark:group-hover:text-white transition">{{ $author->name }}</span>
          <span class="text-xs text-slate-500">{{ $author->books_count }} ном</span>
        </a>
      @empty
        <div class="text-center py-8 text-slate-500">Зохиолч байхгүй байна.</div>
      @endforelse
    </div>
  </section>

</main>

{{-- ================= WISHLIST JS ================= --}}
<script type="module">
  (function () {
    const token = document.querySelector('meta[name="csrf-token"]')?. getAttribute('content') || '{{ csrf_token() }}';
    const wishlistUrl = '{{ route("wishlist.toggle") }}';

    const handleWishlistToggle = async (btn) => {
      const id = btn.dataset.bookId;
      
      // Early return if no ID
      if (!id) return;

      const wasDisabled = btn.disabled;
      btn.disabled = true;

      try {
        const res = await fetch(wishlistUrl, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': token,
            'Content-Type': 'application/json',
            'Accept':  'application/json'
          },
          body: JSON.stringify({ book_id: id })
        });

        if (! res.ok) {
          throw new Error(`HTTP error! status: ${res.status}`);
        }

        const data = await res.json();
        const active = data.in_wishlist === true;
        
        btn.classList.toggle('text-pink-500', active);
        btn.classList.toggle('text-slate-400', !active);
        btn.querySelector('svg').setAttribute('fill', active ? 'currentColor' : 'none');
        btn.setAttribute('aria-label', active ? 'Remove from wishlist' : 'Add to wishlist');
      } catch (e) {
        console.error('Wishlist toggle error:', e);
        // Show error feedback to user
        btn.classList.add('animate-bounce');
        setTimeout(() => btn.classList.remove('animate-bounce'), 600);
      } finally {
        btn.disabled = wasDisabled;
      }
    };

    // Event delegation for better performance
    document.addEventListener('click', (e) => {
      if (e.target.closest('.wishlist-btn')) {
        e.preventDefault();
        e.stopPropagation();
        handleWishlistToggle(e.target. closest('.wishlist-btn'));
      }
    });
  })();
</script>

@include('include.footer')