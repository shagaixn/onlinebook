@include('include.header')

<style>
  .book-card {
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
  }
  
  .book-card:hover {
    transform: translateY(-2px);
  }

  .fade-in {
    animation: fadeIn 0.6s ease-out;
  }

  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
  }
</style>

@php
  $wishlistIds = $wishlistIds ?? [];
  $currentUser = auth()->user();
  $isAuthenticated = $currentUser !== null;
@endphp

<main class="night-sky min-h-[100svh] max-w-9xl mx-auto px-4 py-15">

  {{-- ================= HERO ================= --}}
  <section class="max-w-4xl mx-auto px-6 pt-32 pb-24 text-center relative z-10">
    <section id="hero" class="relative overflow-hidden">
      <div class="absolute inset-0 -z-10 bg-gradient-to-b from-transparent via-white/5 to-transparent dark:via-white/5 rounded-3xl"></div>
    
    <h1 class="text-4xl md:text-6xl font-light tracking-tight text-gray-900 dark:text-white mb-6">
      Мэдлэгийн<br>
      <span class="font-medium bg-gradient-to-r from-gray-900 to-gray-700 dark:from-white dark:to-gray-300 bg-clip-text text-transparent">Шинэ ертөнц</span>
    </h1>

    <p class="text-lg text-gray-600 dark:text-gray-300 max-w-lg mx-auto mb-12 leading-relaxed">
      Цифр номын сан - хүссэн цагтаа, хүссэн газартаа
    </p>

    <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
      @if($isAuthenticated)
        <a href="{{ route('subscription') }}" 
           class="px-8 py-3  border border-gray-300 dark:border-white/30 text-gray-700 dark:text-white rounded-full font-medium hover:border-gray-400 dark:hover:border-white/50 hover:bg-gray-50 dark:hover:bg-white/10 transition-all duration-200 backdrop-blur-sm">
          Subscription
        </a>
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
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-8">
      @forelse($featuredAuthors as $author)
        <a href="{{ route('authors.show', $author->slug ?? $author->id) }}" 
           class="group text-center">
          <div class="w-16 h-16 mx-auto rounded-full overflow-hidden border border-gray-200 dark:border-white/30 mb-3 group-hover:border-gray-400 dark:group-hover:border-white/50 transition-colors">
            <img
                                src="{{ asset(\Illuminate\Support\Str::startsWith($author->profile_image, ['http://', 'https://', '/']) ? $author->profile_image : 'storage/' . ltrim($author->profile_image, '/') ) }}"
                                alt="{{ $author->name }}"
                                class="card-3d__cover" />
          </div>
          <h3 class="text-gray-900 dark:text-white font-medium text-sm mb-1 group-hover:text-gray-600 dark:group-hover:text-gray-300 transition-colors">
            {{ $author->name }}
          </h3>
          <p class="text-gray-500 dark:text-gray-400 text-xs">{{ $author->books_count }} ном</p>
        </a>
      @empty
        <div class="col-span-full text-center py-16 text-gray-500 dark:text-gray-400">Зохиолч байхгүй байна.</div>
      @endforelse
    </div>
  </section>

  {{-- ================= FEATURED BOOKS ================= --}}
  <section class="max-w-6xl mx-auto px-6 mt-24">
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
  </section>

  {{-- ================= NEW BOOKS ================= --}}
  <section class="max-w-6xl mx-auto px-6 py-16 border-t border-gray-200 dark:border-white/20 relative z-10">
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
  </section>

  {{-- ================= AUTHORS ================= --}}
  <section class="max-w-6xl mx-auto px-6 py-16 border-t border-gray-200 dark:border-white/20 relative z-10 perspective-container">
    <h2 class="text-2xl font-light text-gray-900 dark:text-white mb-12 text-center opacity-0 translate-y-4 transition-all duration-700 ease-out" id="authors-title">Зохиолчид</h2>
    
    <div id="authors-grid" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-8 perspective-1000">
      @forelse($featuredAuthors as $author)
        <a href="{{ route('authors.show', $author->slug ?? $author->id) }}" 
           class="author-card group text-center relative block opacity-0 translate-y-8"
           style="transform-style: preserve-3d;">
          <div class="author-avatar w-20 h-20 mx-auto rounded-full overflow-hidden border-2 border-gray-200 dark:border-white/30 mb-4 group-hover:border-cyan-400 dark:group-hover:border-cyan-400 transition-colors shadow-sm relative z-10 bg-white dark:bg-gray-800">
            <img 
               src="{{ asset(\Illuminate\Support\Str::startsWith($author->profile_image ?? '', ['http://', 'https://', '/']) ? $author->profile_image : 'storage/' . ltrim($author->profile_image ?? '', '/') ) }}"
               alt="{{ $author->name }}"
               class="w-full h-full object-cover transform transition-transform duration-500 group-hover:scale-110"
               loading="lazy">
          </div>
          <h3 class="text-gray-900 dark:text-white font-medium text-base mb-1 group-hover:text-cyan-600 dark:group-hover:text-cyan-300 transition-colors transform translate-z-4">
            {{ $author->name }}
          </h3>
          <p class="text-gray-500 dark:text-gray-400 text-xs transform translate-z-2">{{ $author->books_count }} ном</p>
          
          {{-- Hover Glow Effect --}}
          <div class="absolute inset-0 -z-10 bg-cyan-400/0 group-hover:bg-cyan-400/5 rounded-xl blur-xl transition-all duration-500 opacity-0 group-hover:opacity-100"></div>
        </a>
      @empty
        <div class="col-span-full text-center py-16 text-gray-500 dark:text-gray-400">Зохиолч байхгүй байна.</div>
      @endforelse
    </div>
  </section>

  {{-- Authors Animation Script --}}
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const grid = document.getElementById('authors-grid');
      const title = document.getElementById('authors-title');
      const cards = document.querySelectorAll('.author-card');
      
      if (!grid) return;

      // 1. Intersection Observer for stagger fade-in
      const observerOptions = {
        root: null,
        rootMargin: '0px',
        threshold: 0.1
      };

      const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            // Animate title
            if (title) {
              title.classList.remove('opacity-0', 'translate-y-4');
            }
            
            // Stagger animate cards
            cards.forEach((card, index) => {
              setTimeout(() => {
                card.classList.remove('opacity-0', 'translate-y-8');
                card.classList.add('transition-all', 'duration-700', 'ease-out');
              }, index * 100); // 100ms delay between each
            });
            
            observer.unobserve(entry.target);
          }
        });
      }, observerOptions);

      observer.observe(grid);

      // 2. 3D Tilt & Magnetic Effect
      cards.forEach(card => {
        card.addEventListener('mousemove', (e) => {
          const rect = card.getBoundingClientRect();
          const x = e.clientX - rect.left;
          const y = e.clientY - rect.top;
          
          // Calculate percentages (0 to 1)
          const xPct = x / rect.width;
          const yPct = y / rect.height;
          
          // Calculate rotation (Top-left is -rotateX, +rotateY, etc.)
          // We want the card to tilt towards the mouse.
          // Mouse left (xPct < 0.5) -> rotateY should be negative (tilt left edge away? No, tilt left edge down? RotateY rotates around Y axis.)
          // Let's standard tilt:
          const maxTilt = 20; // degrees
          const xTilt = (0.5 - yPct) * maxTilt; // Tilt X axis based on Y position
          const yTilt = (xPct - 0.5) * maxTilt; // Tilt Y axis based on X position
          
          // Magnetic Translation (limit to 10px)
          const transX = (xPct - 0.5) * 10;
          const transY = (yPct - 0.5) * 10;

          card.style.transform = `
            perspective(1000px) 
            rotateX(${xTilt}deg) 
            rotateY(${yTilt}deg) 
            scale3d(1.1, 1.1, 1.1)
            translate3d(${transX}px, ${transY}px, 0)
          `;
        });

        card.addEventListener('mouseleave', () => {
          card.style.transform = 'perspective(1000px) rotateX(0) rotateY(0) scale3d(1, 1, 1) translate3d(0, 0, 0)';
          // Add transition for smooth return
          card.style.transition = 'transform 0.5s ease-out';
          setTimeout(() => {
            card.style.transition = ''; // Remove transition to allow instant mousemove updates
          }, 500);
        });
        
        // Remove transition during movement for snappy response
        card.addEventListener('mouseenter', () => {
          card.style.transition = 'none';
        });
      });
    });
  </script>

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