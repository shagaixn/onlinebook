@include('include.header')

{{-- SEO Structured Data --}}
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebSite",
  "name": "Book Plus - Мэдлэгийн шинэ ертөнц",
  "description": "Монголын хамгийн том цифр номын сан. Хүссэн цагтаа, хүссэн газартаа унших боломжтой.",
  "url": "{{ url('/') }}",
  "potentialAction": {
    "@type": "SearchAction",
    "target": "{{ route('book') }}?q={search_term_string}",
    "query-input": "required name=search_term_string"
  }
}
</script>

{{-- Preload critical resources --}}
<link rel="preload" href="{{ asset('images/placeholder-book.png') }}" as="image" fetchpriority="high">
@if(isset($topRatedBooks) && $topRatedBooks->isNotEmpty())
  <link rel="preload" href="{{ $topRatedBooks->first()->cover_image ? asset('storage/'.$topRatedBooks->first()->cover_image) : asset('images/placeholder-book.png') }}" as="image" fetchpriority="high">
@endif

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

  /* Enhanced Wishlist Button */
  .wishlist-btn {
    transition: all 0.2s ease;
    backdrop-filter: blur(8px);
  }
  
  .wishlist-btn:hover {
    transform: scale(1.1);
    backdrop-filter: blur(12px);
  }
  
  .wishlist-btn.loading {
    pointer-events: none;
    opacity: 0.7;
  }

  /* Skeleton Loading */
  .skeleton {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: loading 1.5s infinite;
  }
  
  .dark .skeleton {
    background: linear-gradient(90deg, #374151 25%, #4b5563 50%, #374151 75%);
    background-size: 200% 100%;
  }
  
  @keyframes loading {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
  }

  /* Enhanced Focus Rings */
  .focus-ring:focus {
    outline: 2px solid #06b6d4;
    outline-offset: 2px;
    border-radius: 8px;
  }

  /* Improved Hover Effects */
  .book-hover {
    position: relative;
    overflow: hidden;
  }
  
  .book-hover::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    background: radial-gradient(circle, rgba(6,182,212,0.3) 0%, transparent 70%);
    transition: all 0.4s ease;
    transform: translate(-50%, -50%);
    pointer-events: none;
  }
  
  .book-hover:hover::after {
    width: 300px;
    height: 300px;
  }
</style>

@php
  $wishlistIds = $wishlistIds ?? [];
  $currentUser = auth()->user();
  $isAuthenticated = $currentUser !== null;
  $topRatedBooks = $topRatedBooks ?? collect();
  $newBooks = $newBooks ?? collect();
  $featuredAuthors = $featuredAuthors ?? collect();
@endphp

<main class="night-sky min-h-[100svh] max-w-9xl mx-auto px-4 py-15">

  {{-- ================= HERO ================= --}}
  <section class="max-w-5xl mx-auto px-6 pt-24 pb-16 text-center z-10" role="banner">
    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white dark:bg-white/10 border border-slate-200 dark:border-white/20 backdrop-blur text-xs font-medium text-cyan-600 dark:text-cyan-300 mb-6 shadow-sm dark:shadow-none" role="status" aria-live="polite">
      <span class="relative flex h-2 w-2" aria-hidden="true">
        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-cyan-400 opacity-75"></span>
        <span class="relative inline-flex rounded-full h-2 w-2 bg-cyan-500"></span>
      </span>
      Шинэ номууд нэмэгдлээ
    </div>

    <h1 class="text-5xl sm:text-6xl md:text-7xl font-black tracking-tight bg-gradient-to-br from-slate-900 via-cyan-600 to-blue-600 dark:from-white dark:via-cyan-200 dark:to-blue-400 bg-clip-text text-transparent drop-shadow-sm dark:drop-shadow-[0_10px_40px_rgba(0,0,0,0.6)]">
      <span class="sr-only">Book Plus - </span>#Мэдрэмж,<br class="hidden sm:block"> Мэдлэгийг өнгөлнө. 
    </h1>

    <p class="mt-6 text-lg text-slate-600 dark:text-slate-300 max-w-2xl mx-auto" role="complementary">
      Хил хязгаар, цаг хугацааг үл харгалзан мэдлэгийг түгээх <span class="font-semibold text-slate-900 dark:text-white">Book Plus</span>
    </p>

    <nav class="mt-10 flex flex-col sm:flex-row gap-4 justify-center" role="navigation" aria-label="Үндсэн навигаци">
      @if($isAuthenticated)
        <a href="{{ route('subscription') }}" 
           class="focus-ring px-8 py-4 rounded-full bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-bold hover:scale-105 transition shadow-lg focus:outline-none focus:ring-2 focus:ring-cyan-400"
           aria-label="Subscription хуудас руу очих">
          Subscription →
        </a>
      @else
        <a href="{{ route('login') }}" 
           class="focus-ring px-8 py-4 rounded-full bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-bold hover:scale-105 transition shadow-lg focus:outline-none focus:ring-2 focus:ring-cyan-400"
           aria-label="Нэвтрэх хуудас руу очих">
          Нэвтрэх →
        </a>
      @endif
      
      <a href="{{ route('book') }}" 
         class="focus-ring px-8 py-4 rounded-full bg-white dark:bg-white/10 text-slate-900 dark:text-white border border-slate-200 dark:border-white/30 backdrop-blur hover:bg-slate-50 dark:hover:bg-white/20 transition focus:outline-none focus:ring-2 focus:ring-cyan-400 shadow-sm dark:shadow-none"
         aria-label="Номын жагсаалт үзэх">
        Номуудыг үзэх
      </a>
    </nav>
  </section>


  {{-- ================= FEATURED BOOKS ================= --}}
  <section class="max-w-6xl mx-auto px-6 mt-24" role="region" aria-labelledby="featured-books-heading">
    <div class="flex justify-between items-end mb-6">
      <h2 id="featured-books-heading" class="text-3xl font-bold text-slate-900 dark:text-white">Шилдэг үнэлгээтэй</h2>
      <a href="{{ route('book', ['sort' => 'rating']) }}" 
         class="focus-ring text-cyan-600 dark:text-cyan-300 hover:underline text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400 rounded px-2"
         aria-label="Бүх үнэлгээтэй номуудыг харах">
        Бүгдийг харах →
      </a>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6" role="list">
      @if(isset($topRatedBooks) && $topRatedBooks->isNotEmpty())
        @foreach($topRatedBooks->take(6) as $index => $book)
          @php
            $inWishlist = in_array($book->id, $wishlistIds);
            $isFirstImage = $index === 0;
          @endphp
          
          <div class="book-card book-hover" data-book-id="{{ $book->id }}" role="listitem">
            {{-- Book Structured Data --}}
            <script type="application/ld+json">
            {
              "@context": "https://schema.org",
              "@type": "Book",
              "name": "{{ addslashes($book->title) }}",
              "author": {
                "@type": "Person",
                "name": "{{ addslashes($book->author ?? $book->authorModel?->name ?? 'Unknown') }}"
              },
              "aggregateRating": {
                "@type": "AggregateRating",
                "ratingValue": "{{ $book->reviews_avg_rating ?? 0 }}",
                "reviewCount": "{{ $book->reviews_count ?? 0 }}"
              },
              "url": "{{ route('books.show', $book->id) }}"
            }
            </script>
            
            <div class="relative group">
              <a href="{{ route('books.show', $book->id) }}" 
                 class="focus-ring block group focus:outline-none focus:ring-2 focus:ring-yellow-400 rounded-xl"
                 aria-label="{{ $book->title }} номын дэлгэрэнгүй мэдээлэл үзэх">
                <div class="relative aspect-[2/3] rounded-xl overflow-hidden mb-3">
                  <img 
                    src="{{ $book->cover_image ? asset('storage/'.$book->cover_image) : asset('images/placeholder-book.png') }}"
                    alt="{{ $book->title }} - {{ $book->author ?? $book->authorModel?->name ?? 'Тодорхойгүй зохиолч' }}"
                    class="w-full h-full object-cover transition duration-500 group-hover:scale-110"
                    loading="{{ $isFirstImage ? 'eager' : 'lazy' }}"
                    decoding="async"
                    {{ $isFirstImage ? 'fetchpriority="high"' : '' }}
                    sizes="(max-width: 640px) 50vw, (max-width: 768px) 33vw, (max-width: 1024px) 25vw, 16vw"
                    srcset="{{ $book->cover_image ? asset('storage/'.$book->cover_image) : asset('images/placeholder-book.png') }} 300w">
                  
                  {{-- Rating Badge --}}
                  @if(isset($book->reviews_avg_rating) && $book->reviews_avg_rating > 0)
                    <div class="absolute top-2 right-2 bg-black/60 backdrop-blur px-2 py-1 rounded-lg flex items-center gap-1" role="img" aria-label="Үнэлгээ {{ number_format($book->reviews_avg_rating, 1) }} од">
                      <span class="text-yellow-400 text-xs" aria-hidden="true">★</span>
                      <span class="text-white text-xs font-bold">{{ number_format($book->reviews_avg_rating, 1) }}</span>
                    </div>
                  @endif
                </div>
              </a>
              
              {{-- Wishlist Button --}}
              @auth
                <button 
                  type="button"
                  class="wishlist-btn absolute top-2 left-2 w-8 h-8 rounded-full bg-white/80 dark:bg-black/60 backdrop-blur border border-white/20 flex items-center justify-center transition-all hover:bg-white dark:hover:bg-black/80 focus:outline-none focus:ring-2 focus:ring-pink-400 {{ $inWishlist ? 'text-pink-500' : 'text-slate-400' }}"
                  data-book-id="{{ $book->id }}"
                  aria-label="{{ $inWishlist ? 'Хүслийн жагсаалтаас хасах' : 'Хүслийн жагсаалтад нэмэх' }}"
                  title="{{ $inWishlist ? 'Хүслийн жагсаалтаас хасах' : 'Хүслийн жагсаалтад нэмэх' }}">
                  <svg class="w-5 h-5" fill="{{ $inWishlist ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                  </svg>
                </button>
              @endauth
              
              <div class="space-y-1">
                <h3 class="text-slate-900 dark:text-white font-semibold text-sm line-clamp-2 group-hover:text-cyan-600 dark:group-hover:text-cyan-300 transition" title="{{ $book->title }}">
                  {{ $book->title }}
                </h3>
                <p class="text-slate-500 dark:text-slate-400 text-xs truncate" title="{{ $book->author ?? $book->authorModel?->name ?? 'Unknown' }}">
                  {{ $book->author ?? $book->authorModel?->name ?? 'Тодорхойгүй зохиолч' }}
                </p>
                
                {{-- Metadata --}}
                <div class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
                  <span>{{ number_format($book->price ?? 0) }}₮</span>
                  @if(isset($book->reviews_count) && $book->reviews_count > 0)
                    <span>{{ $book->reviews_count }} шүүмж</span>
                  @endif
                </div>
              </div>
            </div>
          </div>
        @endforeach
      @endif  
      @else
        <div class="col-span-full text-center py-12 text-slate-500" role="status">
          <p>Одоогоор ном байхгүй байна.</p>
        </div>
      @endif
    </div>
  </section>

  {{-- ================= NEW BOOKS ================= --}}
  <section class="max-w-6xl mx-auto px-6 mt-12 mb-24" role="region" aria-labelledby="new-books-heading">
    <div class="flex justify-between items-end mb-6">
      <h2 id="new-books-heading" class="text-3xl font-bold text-slate-900 dark:text-white">Шинээр нэмэгдсэн</h2>
      <a href="{{ route('book') }}" 
         class="focus-ring text-cyan-600 dark:text-cyan-300 hover:underline text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400 rounded px-2"
         aria-label="Бүх шинэ номуудыг харах">
        Бүгдийг харах →
      </a>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6" role="list">
      @forelse($newBooks as $book)
        @php 
            $inWishlist = in_array($book->id, $wishlistIds); 
            $isNew = isset($book->created_at) && $book->created_at && $book->created_at->diffInHours(now()) < 24;
            $createdDate = isset($book->created_at) && $book->created_at ? $book->created_at->format('n-р сарын j') : 'Тодорхойгүй';
        @endphp
        
        <div class="group relative book-hover" data-book-id="{{ $book->id }}" role="listitem">
          <div class="relative">
            <a href="{{ route('books.show', $book->id) }}" 
               class="focus-ring block focus:outline-none"
               aria-label="{{ $book->title }} номын дэлгэрэнгүй мэдээлэл үзэх">
              <div class="relative aspect-[2/3] rounded-xl overflow-hidden mb-3 shadow-md transition-all duration-300 group-hover:shadow-xl group-hover:-translate-y-1">
                @if($isNew)
                  <div class="absolute top-0 left-0 bg-gradient-to-r from-red-500 to-pink-500 text-white text-[10px] font-bold px-2 py-1 rounded-br-lg z-10 uppercase tracking-wider shadow-lg" role="status" aria-label="Сүүлийн 24 цагт нэмэгдсэн">
                    Сүүлийн 24 цаг
                  </div>
                @else
                  <div class="absolute top-0 left-0 bg-slate-800/80 backdrop-blur text-white text-[10px] font-bold px-2 py-1 rounded-br-lg z-10 uppercase tracking-wider" role="status" aria-label="Нэмэгдсэн огноо {{ $createdDate }}">
                    {{ $createdDate }}
                  </div>
                @endif
                
                <img 
                  src="{{ $book->cover_image ? asset('storage/'.$book->cover_image) : asset('images/placeholder-book.png') }}" 
                  alt="{{ $book->title }} - {{ $book->author ?? $book->authorModel?->name ?? 'Тодорхойгүй зохиолч' }}"
                  class="w-full h-full object-cover transition duration-500 group-hover:scale-110"
                  loading="lazy"
                  decoding="async"
                  sizes="(max-width: 640px) 50vw, (max-width: 768px) 33vw, (max-width: 1024px) 25vw, 20vw">
                  
                <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300" aria-hidden="true"></div>
              </div>
            </a>
            
            {{-- Wishlist Button --}}
            @auth
              <button 
                type="button"
                class="wishlist-btn absolute top-2 right-2 w-8 h-8 rounded-full bg-white/80 dark:bg-black/60 backdrop-blur border border-white/20 flex items-center justify-center transition-all hover:bg-white dark:hover:bg-black/80 focus:outline-none focus:ring-2 focus:ring-pink-400 {{ $inWishlist ? 'text-pink-500' : 'text-slate-400' }}"
                data-book-id="{{ $book->id }}"
                aria-label="{{ $inWishlist ? 'Хүслийн жагсаалтаас хасах' : 'Хүслийн жагсаалтад нэмэх' }}"
                title="{{ $inWishlist ? 'Хүслийн жагсаалтаас хасах' : 'Хүслийн жагсаалтад нэмэх' }}">
                <svg class="w-5 h-5" fill="{{ $inWishlist ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>
              </button>
            @endauth
            
            <div class="space-y-1">
              <h3 class="font-bold text-slate-900 dark:text-white text-base leading-tight mb-1 line-clamp-2 group-hover:text-cyan-600 dark:group-hover:text-cyan-400 transition-colors" title="{{ $book->title }}">
                {{ $book->title }}
              </h3>
              <p class="text-slate-500 dark:text-slate-400 text-xs font-medium mb-2 line-clamp-1" title="{{ $book->author ?? $book->authorModel?->name ?? 'Unknown' }}">
                {{ $book->author ?? $book->authorModel?->name ?? 'Тодорхойгүй зохиолч' }}
              </p>
              
              <div class="flex items-center justify-between text-xs">
                <div class="flex items-center gap-2">
                  <span class="font-bold text-slate-900 dark:text-white">
                    #{{ str_pad($loop->iteration, 3, '0', STR_PAD_LEFT) }}
                  </span>
                  <span class="text-slate-500 dark:text-slate-400">
                    {{ number_format($book->price ?? 0) }}₮
                  </span>
                </div>
                
                {{-- Reading Time Estimate --}}
                @if(isset($book->page_count) && $book->page_count > 0)
                  <span class="text-slate-500 dark:text-slate-400" title="Ойролцоогоор унших хугацаа">
                    ~{{ ceil($book->page_count / 2) }}мин
                  </span>
                @endif
              </div>
            </div>
          </div>
        </div>
      @empty
        <div class="col-span-full text-center py-12 text-slate-500" role="status">
          <p>Одоогоор ном байхгүй байна.</p>
        </div>
      @endforelse
    </div>
  </section>

  {{-- ================= AUTHORS ================= --}}
  <section class="max-w-6xl mx-auto px-6 mt-24 mb-24" role="region" aria-labelledby="authors-heading">
    <h2 id="authors-heading" class="text-2xl font-bold text-slate-900 dark:text-white mb-8 text-center">Онцлох зохиолчид</h2>
    <div class="flex flex-wrap justify-center gap-8" role="list">
      @forelse($featuredAuthors as $author)
        <a href="{{ route('authors.show', $author->slug ?? $author->id) }}" 
           class="group flex flex-col items-center focus:outline-none focus:ring-2 focus:ring-cyan-400 rounded-full px-4 py-4"
           role="listitem">
          <div class="w-24 h-24 rounded-full overflow-hidden border-2 border-slate-200 dark:border-white/20 group-hover:border-cyan-400 group-focus:border-cyan-400 transition shadow-lg mb-3">
            <img 
              src="{{ $author->image ? asset('storage/'.$author->image) : 'https://ui-avatars.com/api/?name='.urlencode($author->name).'&background=random' }}" 
              alt="{{ $author->name }}"
              class="w-full h-full object-cover group-hover:scale-110 transition duration-500"
              loading="lazy">
          </div>
          <span class="text-slate-600 dark:text-slate-300 font-medium group-hover:text-slate-900 dark:group-hover:text-white transition">{{ $author->name }}</span>
          <span class="text-xs text-slate-500">{{ $author->books_count }} ном</span>
        </a>
      @empty
        <div class="text-center py-8 text-slate-500" role="status">Зохиолч байхгүй байна.</div>
      @endforelse
    </div>
  </section>
  </section>

</main>

{{-- ================= ENHANCED WISHLIST JS ================= --}}
<script type="module">
  (function () {
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
    const wishlistUrl = '{{ route("wishlist.toggle") }}';
    const isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};

    // Analytics tracking function
    const trackEvent = (action, bookId, bookTitle) => {
      if (typeof gtag !== 'undefined') {
        gtag('event', action, {
          event_category: 'Wishlist',
          event_label: bookTitle,
          book_id: bookId
        });
      }
      // Custom analytics can be added here
      console.log(`📊 Analytics: ${action}`, { bookId, bookTitle });
    };

    // Enhanced wishlist toggle with better error handling
    const handleWishlistToggle = async (btn) => {
      if (!isAuthenticated) {
        // Redirect to login with return URL
        const returnUrl = encodeURIComponent(window.location.href);
        window.location.href = '{{ route("login") }}?redirect=' + returnUrl;
        return;
      }

      const bookId = btn.dataset.bookId;
      const bookTitle = btn.closest('[data-book-id]').querySelector('h3')?.textContent?.trim() || 'Unknown';
      
      if (!bookId) {
        console.error('❌ Book ID not found');
        return;
      }

      // Prevent double-clicks
      if (btn.disabled || btn.classList.contains('loading')) return;
      
      const wasActive = btn.classList.contains('text-pink-500');
      const icon = btn.querySelector('svg');
      
      // Optimistic UI update
      btn.disabled = true;
      btn.classList.add('loading');
      btn.style.transform = 'scale(0.95)';
      
      try {
        const response = await fetch(wishlistUrl, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': token,
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
          },
          body: JSON.stringify({ book_id: bookId }),
          credentials: 'same-origin'
        });

        if (response.status === 401) {
          // Session expired, redirect to login
          const returnUrl = encodeURIComponent(window.location.href);
          window.location.href = '{{ route("login") }}?redirect=' + returnUrl;
          return;
        }

        if (!response.ok) {
          throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }

        const data = await response.json();
        const isNowActive = data.in_wishlist === true;
        
        // Update UI
        btn.classList.toggle('text-pink-500', isNowActive);
        btn.classList.toggle('text-slate-400', !isNowActive);
        icon.setAttribute('fill', isNowActive ? 'currentColor' : 'none');
        btn.setAttribute('aria-label', isNowActive ? 'Хүслийн жагсаалтаас хасах' : 'Хүслийн жагсаалтад нэмэх');
        btn.setAttribute('title', isNowActive ? 'Хүслийн жагсаалтаас хасах' : 'Хүслийн жагсаалтад нэмэх');
        
        // Success animation
        btn.style.transform = 'scale(1.1)';
        setTimeout(() => {
          btn.style.transform = 'scale(1)';
        }, 150);
        
        // Track analytics
        trackEvent(isNowActive ? 'add_to_wishlist' : 'remove_from_wishlist', bookId, bookTitle);
        
        // Show toast notification
        showToast(isNowActive ? 'Хүслийн жагсаалтад нэмэгдлээ' : 'Хүслийн жагсаалтаас хасагдлаа', 'success');
        
      } catch (error) {
        console.error('❌ Wishlist toggle error:', error);
        
        // Revert optimistic update
        btn.classList.toggle('text-pink-500', wasActive);
        btn.classList.toggle('text-slate-400', !wasActive);
        icon.setAttribute('fill', wasActive ? 'currentColor' : 'none');
        
        // Error feedback
        btn.style.transform = 'scale(1)';
        btn.classList.add('animate-pulse');
        setTimeout(() => btn.classList.remove('animate-pulse'), 1000);
        
        // Show error message
        let errorMessage = 'Алдаа гарлаа. Дахин оролдоно уу.';
        if (error.message.includes('401')) {
          errorMessage = 'Нэвтрэх шаардлагатай. Дахин нэвтэрнэ үү.';
        } else if (error.message.includes('Network')) {
          errorMessage = 'Интернэт холболтыг шалгаад дахин оролдоно уу.';
        }
        
        showToast(errorMessage, 'error');
        
      } finally {
        btn.disabled = false;
        btn.classList.remove('loading');
      }
    };

    // Simple toast notification system
    const showToast = (message, type = 'info') => {
      const toast = document.createElement('div');
      toast.className = `fixed top-4 right-4 px-4 py-2 rounded-lg text-white z-50 transition-all transform translate-x-full opacity-0 ${
        type === 'success' ? 'bg-green-500' : 
        type === 'error' ? 'bg-red-500' : 'bg-blue-500'
      }`;
      toast.textContent = message;
      toast.setAttribute('role', 'alert');
      toast.setAttribute('aria-live', 'polite');
      
      document.body.appendChild(toast);
      
      // Animate in
      setTimeout(() => {
        toast.classList.remove('translate-x-full', 'opacity-0');
      }, 100);
      
      // Auto remove
      setTimeout(() => {
        toast.classList.add('translate-x-full', 'opacity-0');
        setTimeout(() => {
          document.body.removeChild(toast);
        }, 300);
      }, 3000);
    };

    // Event delegation with keyboard support
    document.addEventListener('click', (e) => {
      const wishlistBtn = e.target.closest('.wishlist-btn');
      if (wishlistBtn) {
        e.preventDefault();
        e.stopPropagation();
        handleWishlistToggle(wishlistBtn);
      }
    });

    // Keyboard navigation support
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Enter' || e.key === ' ') {
        const wishlistBtn = e.target.closest('.wishlist-btn');
        if (wishlistBtn) {
          e.preventDefault();
          handleWishlistToggle(wishlistBtn);
        }
      }
    });

    // Prefetch wishlist route for faster subsequent requests
    if (isAuthenticated) {
      const prefetchLink = document.createElement('link');
      prefetchLink.rel = 'prefetch';
      prefetchLink.href = wishlistUrl;
      document.head.appendChild(prefetchLink);
    }

    console.log('✅ Wishlist functionality initialized');
  })();
</script>

@include('include.footer')