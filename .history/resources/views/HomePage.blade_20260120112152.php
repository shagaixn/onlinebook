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

  /* Marquee Animations */
  @keyframes marquee-left {
    from { transform: translateX(0); }
    to { transform: translateX(-50%); } /* Move half width because we duplicated content */
  }
  @keyframes marquee-right {
    from { transform: translateX(-50%); } 
    to { transform: translateX(0); }
  }

  .animate-marquee-left {
    animation: marquee-left 40s linear infinite;
  }
  .animate-marquee-right {
    animation: marquee-right 40s linear infinite;
  }
  /* Pause on hover */
  .marquee-container:hover .marquee-content {
      animation-play-state: paused;
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
    <div class="relative w-full mt-12 mb-8 overflow-hidden">
      {{-- Canvas Background --}}
      <canvas id="authors-canvas" class="absolute inset-0 w-full h-full pointer-events-none opacity-30"></canvas>
      
      {{-- Marquee Container --}}
      <div class="flex flex-col gap-6 relative z-10 py-4">
        
        {{-- Row 1: Left to Right (or Right to Left) --}}
        <div class="marquee-container flex overflow-hidden select-none">
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

        {{-- Row 2: Right to Left (or Left to Right) --}}
        <div class="marquee-container flex overflow-hidden select-none">
            <div class="marquee-content flex gap-6 animate-marquee-right">
                @foreach($newBooks as $book)
                   @include('components.book-pill', ['book' => $book])
                @endforeach
                 {{-- Duplicate --}}
                @foreach($newBooks as $book)
                   @include('components.book-pill', ['book' => $book])
                @endforeach
                 @foreach($newBooks as $book)
                   @include('components.book-pill', ['book' => $book])
                @endforeach
            </div>
        </div>

      </div>
    </div>

    {{-- End of Marquee --}}
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
  <section class="max-w-6xl mx-auto px-6 py-16 border-t border-gray-200 dark:border-white/20 relative z-10">
    <h2 class="text-2xl font-light text-gray-900 dark:text-white mb-12 text-center">Зохиолчид</h2>
    
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
    // === 2. CANVAS CONSTELLATION LOGIC ===
    const canvas = document.getElementById('authors-canvas');
    if (!!canvas) {
        const ctx = canvas.getContext('2d');
        let width, height;
        let particles = [];
        
        const particleCount = 40;
        const connectionDistance = 150;
        const mouseDistance = 200;

        const resize = () => {
            if(!canvas.parentElement) return;
            const rect = canvas.parentElement.getBoundingClientRect();
            width = canvas.width = rect.width;
            height = canvas.height = rect.height;
        };
        resize();
        window.addEventListener('resize', resize);

        let mouse = { x: null, y: null };
        canvas.parentElement.addEventListener('mousemove', (e) => {
            const rect = canvas.getBoundingClientRect();
            mouse.x = e.clientX - rect.left;
            mouse.y = e.clientY - rect.top;
        });
        canvas.parentElement.addEventListener('mouseleave', () => {
             mouse.x = null; mouse.y = null;
        });

        class Particle {
            constructor() {
                this.x = Math.random() * width;
                this.y = Math.random() * height;
                this.vx = (Math.random() - 0.5) * 0.5;
                this.vy = (Math.random() - 0.5) * 0.5;
                this.size = Math.random() * 2 + 1;
                // Cyan/Blue theme
                this.baseColor = 'rgba(100, 200, 255, '; 
            }
            update() {
                this.x += this.vx;
                this.y += this.vy;
                if (this.x < 0 || this.x > width) this.vx *= -1;
                if (this.y < 0 || this.y > height) this.vy *= -1;

                if (mouse.x != null) {
                    let dx = mouse.x - this.x;
                    let dy = mouse.y - this.y;
                    let distance = Math.sqrt(dx * dx + dy * dy);
                    if (distance < mouseDistance) {
                        const forceDirectionX = dx / distance;
                        const forceDirectionY = dy / distance;
                        const force = (mouseDistance - distance) / mouseDistance;
                        const directionX = forceDirectionX * force * 0.5;
                        const directionY = forceDirectionY * force * 0.5;
                        this.vx -= directionX;
                        this.vy -= directionY;
                    }
                }
            }
            draw() {
                ctx.fillStyle = this.baseColor + '0.3)';
                ctx.beginPath();
                ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                ctx.fill();
            }
        }

        for (let i = 0; i < particleCount; i++) particles.push(new Particle());

        function animate() {
            ctx.clearRect(0, 0, width, height);
            for (let i = 0; i < particles.length; i++) {
                particles[i].update();
                particles[i].draw();
                for (let j = i; j < particles.length; j++) {
                    let dx = particles[i].x - particles[j].x;
                    let dy = particles[i].y - particles[j].y;
                    let distance = Math.sqrt(dx * dx + dy * dy);
                    if (distance < connectionDistance) {
                        ctx.beginPath();
                        let opacity = 1 - (distance / connectionDistance);
                        ctx.strokeStyle = 'rgba(100, 200, 255,' + (opacity * 0.15) + ')'; 
                        ctx.lineWidth = 1;
                        ctx.moveTo(particles[i].x, particles[i].y);
                        ctx.lineTo(particles[j].x, particles[j].y);
                        ctx.stroke();
                    }
                }
            }
            requestAnimationFrame(animate);
        }
        animate();
    }
});
</script>

@include('include.footer')