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

  /* Marquee Animations - Optimized */
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
    will-change: transform;
  }
  .animate-marquee-right {
    animation: marquee-right 45s linear infinite;
    will-change: transform;
  }
  
  /* Smooth pause on hover */
  .marquee-container:hover .marquee-content {
    animation-play-state: paused;
  }
  /* Smooth cursor transitions */
  .marquee-container {
    cursor: grab;
    user-select: none;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    transition: all 0.2s ease;
  }
  .marquee-container:active {
    cursor: grabbing;
  }
  
  /* Gradient Mask for fading edges - Enhanced */
  .marquee-fade-mask {
    mask-image: linear-gradient(to right, transparent 0%, black 5%, black 95%, transparent 100%);
    -webkit-mask-image: linear-gradient(to right, transparent 0%, black 5%, black 95%, transparent 100%);
  }
  
  /* Smooth hover effect for book and author cards */
  .book-card, .author-card {
    transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
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
      {{-- Canvas Background --}}
      <canvas id="authors-canvas" class="absolute inset-0 w-full h-full pointer-events-none opacity-30"></canvas>
      
      {{-- Marquee Container --}}
      <div class="flex flex-col gap-6 relative z-10 py-4 w-full marquee-fade-mask">
        
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
  <div>

  </div>

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
    // === 1. DRAG TO SCROLL FOR MARQUEE ROWS ===
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

        container.style.cursor = 'grab';

        const applyMomentum = () => {
            if (Math.abs(velocity) > 0.1) {
                container.scrollLeft += velocity;
                velocity *= 0.95; // Friction
                animationFrame = requestAnimationFrame(applyMomentum);
            } else {
                velocity = 0;
                // Resume animation smoothly
                setTimeout(() => {
                    if (!isDown) {
                        content.style.animationPlayState = 'running';
                    }
                }, 300);
            }
        };

        container.addEventListener('mousedown', (e) => {
            isDown = true;
            container.style.cursor = 'grabbing';
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
                container.style.cursor = 'grab';
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
            
            // Calculate velocity for momentum
            velocity = x - lastX;
            lastX = x;
        });
    });

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
                this.vx = (Math.random() - 0.5) * 0.4;
                this.vy = (Math.random() - 0.5) * 0.4;
                this.size = Math.random() * 2.5 + 0.5;
                this.baseColor = 'rgba(100, 200, 255, ';
                this.maxSpeed = 1.5;
            }
            update() {
                // Apply velocity with damping
                this.x += this.vx;
                this.y += this.vy;
                
                // Damping for smoother movement
                this.vx *= 0.99;
                this.vy *= 0.99;
                
                // Bounce off edges with energy loss
                if (this.x < 0 || this.x > width) {
                    this.vx *= -0.8;
                    this.x = Math.max(0, Math.min(width, this.x));
                }
                if (this.y < 0 || this.y > height) {
                    this.vy *= -0.8;
                    this.y = Math.max(0, Math.min(height, this.y));
                }

                // Mouse interaction with smoother easing
                if (mouse.x != null) {
                    let dx = mouse.x - this.x;
                    let dy = mouse.y - this.y;
                    let distance = Math.sqrt(dx * dx + dy * dy);
                    if (distance < mouseDistance && distance > 0) {
                        const force = (mouseDistance - distance) / mouseDistance;
                        const forceX = (dx / distance) * force * 0.3;
                        const forceY = (dy / distance) * force * 0.3;
                        this.vx -= forceX;
                        this.vy -= forceY;
                    }
                }
                
                // Limit speed
                const speed = Math.sqrt(this.vx * this.vx + this.vy * this.vy);
                if (speed > this.maxSpeed) {
                    this.vx = (this.vx / speed) * this.maxSpeed;
                    this.vy = (this.vy / speed) * this.maxSpeed;
                }
            }
            draw() {
                // Draw with gradient for smoother appearance
                const gradient = ctx.createRadialGradient(this.x, this.y, 0, this.x, this.y, this.size);
                gradient.addColorStop(0, this.baseColor + '0.6)');
                gradient.addColorStop(1, this.baseColor + '0)');
                ctx.fillStyle = gradient;
                ctx.beginPath();
                ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                ctx.fill();
            }
        }

        for (let i = 0; i < particleCount; i++) particles.push(new Particle());

        let lastTime = 0;
        const targetFPS = 60;
        const frameDelay = 1000 / targetFPS;

        function animate(currentTime) {
            const deltaTime = currentTime - lastTime;
            
            if (deltaTime >= frameDelay) {
                lastTime = currentTime - (deltaTime % frameDelay);
                
                ctx.clearRect(0, 0, width, height);
                
                // Update and draw particles
                for (let i = 0; i < particles.length; i++) {
                    particles[i].update();
                    particles[i].draw();
                }
                
                // Draw connections with optimized loop
                ctx.lineWidth = 0.8;
                for (let i = 0; i < particles.length; i++) {
                    for (let j = i + 1; j < particles.length; j++) {
                        let dx = particles[i].x - particles[j].x;
                        let dy = particles[i].y - particles[j].y;
                        let distanceSquared = dx * dx + dy * dy;
                        
                        if (distanceSquared < connectionDistance * connectionDistance) {
                            let distance = Math.sqrt(distanceSquared);
                            let opacity = (1 - (distance / connectionDistance)) * 0.2;
                            
                            ctx.beginPath();
                            ctx.strokeStyle = `rgba(100, 200, 255, ${opacity})`;
                            ctx.moveTo(particles[i].x, particles[i].y);
                            ctx.lineTo(particles[j].x, particles[j].y);
                            ctx.stroke();
                        }
                    }
                }
            }
            
            requestAnimationFrame(animate);
        }
        requestAnimationFrame(animate);
    }
});
</script>

@include('include.footer')