@include('include.header')

<style>
  /* Marquee Animations */
  @keyframes marquee-left {
    0% { transform: translateX(0); }
    100% { transform: translateX(-33.33333%); }
  }
  @keyframes marquee-right {
    0% { transform: translateX(-33.33333%); }
    100% { transform: translateX(0); }
  }

  .animate-marquee-left {
    animation: marquee-left 40s linear infinite;
  }
  .animate-marquee-right {
    animation: marquee-right 45s linear infinite;
  }
  
  /* Pause on hover */
  .marquee-container:hover .marquee-content {
    animation-play-state: paused;
  }
  
  /* Drag cursor */
  .marquee-container {
    cursor: grab;
    user-select: none;
  }
  .marquee-container:active {
    cursor: grabbing;
  }
  
  /* Gradient fade edges */
  .marquee-fade-mask {
    mask-image: linear-gradient(to right, transparent 0%, black 5%, black 95%, transparent 100%);
    -webkit-mask-image: linear-gradient(to right, transparent 0%, black 5%, black 95%, transparent 100%);
  }
</style>

@php
  $wishlistIds = $wishlistIds ?? [];
  $currentUser = auth()->user();
  $isAuthenticated = $currentUser !== null;
@endphp

<main class="night-sky min-h-[100svh] w-full relative">

  {{-- ================= HERO ================= --}}
  <section class="w-full min-h-[100svh] flex flex-col justify-center items-center px-4 relative z-10 overflow-hidden">
    <section id="hero" class="absolute inset-0 w-full h-full -z-10">
      <div class="absolute inset-0 bg-gradient-to-b from-transparent via-white/5 to-transparent dark:via-white/5"></div>
      {{-- Optional: Add background image or pattern here if needed covering full screen --}}
    </section>
    
    <div class="w-full max-w-4xl mx-auto text-center pt-20">
    <h1 class="text-4xl md:text-6xl font-light tracking-tight text-gray-900 dark:text-white mb-6">
      Book Plus<br>
    </h1>

    <p class="text-lg text-gray-600 dark:text-gray-300 max-w-lg mx-auto mb-12 leading-relaxed">
      Мэдлэг бол хамгийн том баялаг. Түүнийг бүгдтэй хуваалцъя.
    </p>

    <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mb-16">
      @if($isAuthenticated)
      @else
        <a href="{{ route('login') }}" 
           class="px-8 py-3  border border-gray-300 dark:border-white/30 text-gray-700 dark:text-white rounded-full font-medium hover:border-gray-400 dark:hover:border-white/50 hover:bg-gray-50 dark:hover:bg-white/10 transition-all duration-200 backdrop-blur-sm">
          Нэвтрэх
        </a>
      @endif
      
      <a href="{{ route('book') }}" 
         class="px-8 py-3  border border-gray-300 dark:border-white/30 text-gray-700 dark:text-white rounded-full font-medium hover:border-gray-400 dark:hover:border-white/50 hover:bg-gray-50 dark:hover:bg-white/10 transition-all duration-200 backdrop-blur-sm">
        Номууд үзэх
      </a>
    </div>
    </div>

    {{-- MARQUEE SECTION --}}
    <div class="w-full mt-auto mb-8 overflow-hidden">
      <div class="flex flex-col gap-6 relative z-10 py-4 w-full marquee-fade-mask">
        
        {{-- Row 1: Scroll Left --}}
        <div class="marquee-container flex overflow-hidden">
            <div class="marquee-content flex gap-6 animate-marquee-left">
                @foreach($featuredAuthors as $author)
                   @include('components.author-pill', ['author' => $author])
                @endforeach
                {{-- Duplicate for smooth loop --}}
                @foreach($featuredAuthors as $author)
                   @include('components.author-pill', ['author' => $author])
                @endforeach
                @foreach($featuredAuthors as $author)
                   @include('components.author-pill', ['author' => $author])
                @endforeach
            </div>
        </div>

        {{-- Row 2: Scroll Right --}}
        <div class="marquee-container flex overflow-hidden">
            <div class="marquee-content flex gap-6 animate-marquee-right">
                @foreach($newBooks as $book)
                   @include('components.book-pill', ['book' => $book])
                @endforeach
                {{-- Duplicate for smooth loop --}}
                @foreach($newBooks as $book)
                   @include('components.book-pill', ['book' => $book])
                @endforeach
                @foreach($newBooks as $book)
                   @include('components.book-pill', ['book' => $book])
                @endforeach
            </div>
        </div>

        {{-- Row 3: Wishlist Books - Scroll Left --}}
        @if($wishlistBooks && $wishlistBooks->isNotEmpty())
        <div class="marquee-container flex overflow-hidden">
            <div class="marquee-content flex gap-6 animate-marquee-left">
                @foreach($wishlistBooks as $book)
                   @include('components.book-pill', ['book' => $book])
                @endforeach
                {{-- Duplicate for smooth loop --}}
                @foreach($wishlistBooks as $book)
                   @include('components.book-pill', ['book' => $book])
                @endforeach
                @foreach($wishlistBooks as $book)
                   @include('components.book-pill', ['book' => $book])
                @endforeach
            </div>
        </div>
        @endif

      </div>
    </div>
  </section>

  {{-- ================= FEATURED BOOKS SECTION ================= --}}
  @if($featuredBooks && $featuredBooks->isNotEmpty())
  <section class="relative w-full py-20 px-4">
    <div class="max-w-7xl mx-auto">
      <h2 class="text-3xl md:text-4xl font-bold text-center mb-16 text-gray-900 dark:text-white">
        🌟 Онцлох номууд
      </h2>
      
      <div class="space-y-32">
        @foreach($featuredBooks as $index => $book)
          <div class="featured-book-item opacity-0 translate-y-20" data-index="{{ $index }}">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-center {{ $index % 2 === 0 ? '' : 'lg:grid-flow-dense' }}">
              
              {{-- Book Image --}}
              <div class="{{ $index % 2 === 0 ? '' : 'lg:col-start-2' }} group">
                <a href="{{ route('books.show', $book->id) }}" class="block">
                  <div class="relative aspect-[3/4] rounded-2xl overflow-hidden shadow-2xl group-hover:shadow-3xl transition-all duration-500 transform group-hover:scale-105">
                    <img 
                      src="{{ $book->cover_image ? asset('storage/'.$book->cover_image) : asset('images/placeholder-book.png') }}" 
                      alt="{{ $book->title }}"
                      class="w-full h-full object-cover"
                      loading="lazy">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                  </div>
                </a>
              </div>

              {{-- Book Info --}}
              <div class="{{ $index % 2 === 0 ? '' : 'lg:col-start-1 lg:row-start-1' }} space-y-6">
                <div class="inline-block px-4 py-2 bg-yellow-400 text-yellow-900 rounded-full text-sm font-bold">
                  Онцлох
                </div>
                
                <h3 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white leading-tight">
                  {{ $book->title }}
                </h3>
                
                <p class="text-lg text-gray-600 dark:text-gray-300">
                  <span class="font-semibold">Зохиолч:</span> {{ $book->author ?? $book->authorModel?->name ?? 'Unknown' }}
                </p>
                
                @if($book->description)
                  <p class="text-gray-700 dark:text-gray-300 leading-relaxed line-clamp-4">
                    {{ $book->description }}
                  </p>
                @endif
                
                <div class="flex flex-wrap items-center gap-4">
                  @if($book->reviews_avg_rating)
                    <div class="flex items-center gap-2 bg-yellow-50 dark:bg-yellow-900/20 px-4 py-2 rounded-lg">
                      <span class="text-yellow-500 text-xl">★</span>
                      <span class="font-bold text-gray-900 dark:text-white">{{ number_format($book->reviews_avg_rating, 1) }}</span>
                    </div>
                  @endif
                  
                  @if($book->category)
                    <span class="px-4 py-2 bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300 rounded-lg text-sm font-medium">
                      {{ $book->category->name ?? '' }}
                    </span>
                  @endif
                </div>
                
                <div class="flex gap-4 pt-4">
                  <a href="{{ route('books.show', $book->id) }}" 
                     class="px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    Дэлгэрэнгүй үзэх
                  </a>
                  <a href="{{ route('books.read', $book->id) }}" 
                     class="px-8 py-4 border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:border-blue-600 hover:text-blue-600 dark:hover:border-blue-400 dark:hover:text-blue-400 rounded-xl font-semibold transition-all duration-200">
                    Унших
                  </a>
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </section>
  @endif

  {{-- ================= FEATURED BOOKS ================= --}}
  {{-- <section class="max-w-6xl mx-auto px-6 mt-24">
    <div class="flex justify-between items-end mb-6">
      <h2 class="text-3xl font-bold text-slate-900 dark:text-white">Шилдэг үнэлгээтэй</h2>
      <a href="{{ route('book', ['sort' => 'rating']) }}" 
         class="text-cyan-600 dark:text-cyan-300 hover:underline text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400 rounded px-2">
        Бүгдийг харах
      </a>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6">
      @if($topRatedBooks->isNotEmpty())
        @foreach($topRatedBooks->take(6) as $book)
          <div class="book-card">
            <a href="{{ route('books.show', $book->id) }}" class="block group focus:outline-none focus:ring-2 focus:ring-yellow-400 rounded-xl">
              <div class="relative aspect-[2/3] rounded-xl overflow-hidden mb-3">
                <img 
                  src="{{ $book->cover_image ? asset('storage/'.$book->cover_image) : asset('images/placeholder-book.png') }}" 
                  alt="{{ $book->title }}"
                  class="w-full h-full object-cover transition duration-500 group-hover:scale-110"
                  loading="lazy">
                <div class="absolute top-2 right-2 bg-black/60 backdrop-blur px-2 py-1 rounded-lg flex items-center gap-1">
                  <span class="text-yellow-400 text-xs" aria-hidden="true">★</span>
                  <span class="text-white text-xs font-bold">{{ number_format($book->reviews_avg_rating ?? 0, 1) }}</span>
                </div>
              </div>
              <h3 class="text-slate-900 dark:text-white font-semibold truncate group-hover:text-cyan-600 dark:group-hover:text-cyan-300 transition">{{ $book->title }}</h3>
              <p class="text-slate-500 dark:text-slate-400 text-sm truncate">{{ $book->author ?? $book->authorModel?->name ?? 'Unknown' }}</p>
            </a>
          </div>
        @endforeach
      @else
        <div class="col-span-full text-center py-12 text-slate-500">Одоогоор ном байхгүй байна.</div>
      @endif
    </div>
  </section> --}}

  {{-- ================= NEW BOOKS ================= --}}
  {{-- <section class="max-w-6xl mx-auto px-6 py-16 border-t border-gray-200 dark:border-white/20 relative z-10">
    <div class="flex justify-between items-center mb-12">
      <h2 class="text-2xl font-light text-gray-900 dark:text-white">Шинэ номууд</h2>
      <a href="{{ route('book') }}" 
         class="text-sm text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white transition-colors">
        Бүгдийг үзэх →
      </a>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6">
      @forelse($newBooks as $book)
        @php 
            $inWishlist = in_array($book->id, $wishlistIds); 
            $isNew = $book->created_at && $book->created_at->diffInHours(now()) < 24;
        @endphp
        
        <div class="book-card" data-book-id="{{ $book->id }}">
          <a href="{{ route('books.show', $book->id) }}" class="block group">
            <div class="aspect-[2/3] rounded-lg overflow-hidden mb-4 bg-gray-100 dark:bg-white/10 border dark:border-white/10 relative">
              @if($isNew)
                <div class="absolute top-2 left-2 bg-red-500 text-white text-xs px-2 py-1 rounded-full z-10 shadow-lg">
                  Шинэ
                </div>
              @endif
              
              <img 
                src="{{ $book->cover_image ? asset('storage/'.$book->cover_image) : asset('images/placeholder-book.png') }}" 
                alt="{{ $book->title }}"
                class="w-full h-full object-cover"
                loading="lazy">
            </div>

            <h3 class="font-medium text-gray-900 dark:text-white text-sm mb-1 line-clamp-2 group-hover:text-gray-600 dark:group-hover:text-gray-300 transition-colors">
              {{ $book->title }}
            </h3>
            <p class="text-gray-500 dark:text-gray-400 text-xs mb-2">
              {{ $book->author ?? $book->authorModel?->name ?? 'Unknown' }}
            </p>
            
            <div class="flex items-center justify-between">
              <span class="text-gray-900 dark:text-white font-medium text-sm">
                {{ number_format($book->price) }}₮
              </span>
            </div>
          </a>
        </div>
      @empty
        <div class="col-span-full text-center py-16 text-gray-500">Одоогоор ном байхгүй байна.</div>
      @endforelse
    </div>
  </section> --}}

  {{-- ================= AUTHORS ================= --}}
  {{-- <section class="max-w-6xl mx-auto px-6 py-16 border-t border-gray-200 dark:border-white/20 relative z-10">
    <h2 class="text-2xl font-light text-gray-900 dark:text-white mb-12 text-center">Зохиолчид</h2> --}}
    
    {{-- <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-8">
      @forelse($featuredAuthors as $author)
        <a href="{{ route('authors.show', $author->slug ?? $author->id) }}" 
           class="group text-center">
          <div class="w-16 h-16 mx-auto rounded-full overflow-hidden border border-gray-200 dark:border-white/30 mb-3 group-hover:border-gray-400 dark:group-hover:border-white/50 transition-colors">
            <img 
              src="{{ $author->image ? asset('storage/'.$author->image) : 'https://ui-avatars.com/api/?name='.urlencode($author->name).'&background=random' }}" 
              alt="{{ $author->name }}"
              class="w-full h-full object-cover"
              loading="lazy">
          </div>
          <h3 class="text-gray-900 dark:text-white font-medium text-sm mb-1 group-hover:text-gray-600 dark:group-hover:text-gray-300 transition-colors">
            {{ $author->name }}
          </h3>
          <p class="text-gray-500 dark:text-gray-400 text-xs">{{ $author->books_count }} ном</p>
        </a>
      @empty
        <div class="col-span-full text-center py-16 text-gray-500 dark:text-gray-400">Зохиолч байхгүй байна.</div>
      @endforelse
    </div> --}}
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

<script>
document.addEventListener('DOMContentLoaded', () => {
    const marqueeContainers = document.querySelectorAll('.marquee-container');
    
    marqueeContainers.forEach(container => {
        const content = container.querySelector('.marquee-content');
        if (!content) return;
        
        let isDown = false;
        let startX;
        let scrollLeft;
        let velocity = 0;
        let lastX = 0;
        let animationFrame;

        const applyMomentum = () => {
            if (Math.abs(velocity) > 0.5) {
                container.scrollLeft += velocity;
                velocity *= 0.95;
                animationFrame = requestAnimationFrame(applyMomentum);
            } else {
                velocity = 0;
                setTimeout(() => {
                    if (!isDown) {
                        content.style.animationPlayState = 'running';
                    }
                }, 200);
            }
        };

        container.addEventListener('mousedown', (e) => {
            isDown = true;
            content.style.animationPlayState = 'paused';
            
            startX = e.pageX - container.offsetLeft;
            lastX = startX;
            scrollLeft = container.scrollLeft;
            velocity = 0;
            
            if (animationFrame) {
                cancelAnimationFrame(animationFrame);
            }
            
            e.preventDefault();
        });

        const handleMouseUp = () => {
            if (isDown) {
                isDown = false;
                applyMomentum();
            }
        };

        container.addEventListener('mouseleave', handleMouseUp);
        container.addEventListener('mouseup', handleMouseUp);

        container.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            
            const x = e.pageX - container.offsetLeft;
            const walk = (x - startX) * 1.5;
            container.scrollLeft = scrollLeft - walk;
            
            velocity = x - lastX;
            lastX = x;
        });
    });
});
</script>



@include('include.footer')