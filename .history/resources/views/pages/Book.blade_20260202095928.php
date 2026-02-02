@include('include.header')
<style>
  /* Ensure native form controls render dark internally when in dark mode */
  .dark select,
  .dark input,
  .dark textarea,
  .dark button {
    color-scheme: dark;
  }
</style>
@php
  // Controller-оос ирээгүй бол session-оос эсвэл хоосон массив
  $wishlistIds = $wishlistIds ?? session('wishlist.ids', []);
@endphp

<section class="night-sky relative min-h-[80vh] flex items-center justify-center px-4 py-12">
  <main class="max-w-9xl mx-auto py-15 px-4">
    <h1 class="text-4xl font-bold mb-2 text-blue-700 text-center">Номуудын жагсаалт</h1>

   

    @if(request('q') || request('category_id') || request('author_id'))
      <p class="text-center text-sm text-gray-500 dark:text-gray-400 mb-6 flex items-center justify-center gap-3">
        @if(request('q')) <span>Хайлтын үг: <span class="font-semibold">"{{ request('q') }}"</span></span> @endif
        @if(request('q') && (request('category_id') || request('author_id'))) <span aria-hidden="true">·</span> @endif
        @if(request('category_id') && isset($categories))
          <span>Ангилал: <span class="font-semibold">{{ optional($categories->firstWhere('id', (int)request('category_id')))->name ?? 'Бүх ангилал' }}</span></span>
        @endif
        @if(request('author_id') && isset($authors))
          <span>Зохиолч: <span class="font-semibold">{{ optional($authors->firstWhere('id', (int)request('author_id')))->name ?? 'Бүх зохиолч' }}</span></span>
        @endif
        @isset($books)
          <span class="px-3 py-1 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-200 font-semibold">
            {{ $books->count() }} үр дүн
          </span>
        @endisset
      </p>
    @endif

    @if(!request('q') && !request('category_id') && !request('author_id'))
      {{-- Шүүлтгүй үед: ангилал тус бүрээр бүлэглэж харуулах --}}
      @forelse(($categoryRows ?? collect()) as $row)
        @php $cat = $row['category']; $items = $row['books']; @endphp
        <section class="mb-10">
          <div class="flex items-center justify-between mb-3">
            <h2 class="text-xl font-bold text-slate-900 dark:text-white">{{ $cat->name }}</h2>
            @if($items->count() >= 10)
              <a href="{{ route('book', ['category_id' => $cat->id]) }}" class="text-blue-600 hover:text-blue-700 dark:text-cyan-300 font-medium">Бүгдийг үзэх →</a>
            @endif
          </div>
          <!-- Smaller cards: more columns, tighter gap -->
          <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-3">
            @foreach($items as $book)
              <div class="group relative text-[12px]" data-book-id="{{ $book->id }}">
                {{-- Wishlist Button --}}
                @auth
                  @php
                    $inWishlist = in_array($book->id, $wishlistIds ?? []);
                  @endphp
                  <button onclick="event.stopPropagation(); toggleWishlist({{ $book->id }}, this)" 
                          class="wishlist-btn absolute top-1 right-1 z-20 p-1.5 rounded-full bg-white/90 dark:bg-slate-800/90 backdrop-blur shadow-md hover:scale-110 transition-all {{ $inWishlist ? 'text-pink-500' : 'text-slate-400' }}"
                          data-book-id="{{ $book->id }}"
                          title="{{ $inWishlist ? 'Wishlist-аас хасах' : 'Wishlist-д нэмэх' }}">
                    <svg class="w-4 h-4" fill="{{ $inWishlist ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                  </button>
                @endauth
                <a href="{{ route('books.show', $book->id) }}" class="block focus:outline-none">
                  <div class="relative aspect-[2/3] rounded-md overflow-hidden mb-2 shadow-sm transition-all duration-300 group-hover:shadow-md group-hover:-translate-y-0.5">
                    <img
                      src="{{ $book->cover_image ? asset('storage/'.$book->cover_image) : asset('images/placeholder-book.png') }}"
                      alt="{{ $book->title }}"
                      class="w-full h-full object-cover transition duration-500 group-hover:scale-105"
                      loading="lazy">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                  </div>
                  <div>
                    <h3 class="font-bold text-slate-900 dark:text-white text-[12px] leading-tight mb-0.5 line-clamp-1 group-hover:text-cyan-600 dark:group-hover:text-cyan-400 transition-colors">
                      {{ $book->title }}
                    </h3>
                    <p class="text-slate-500 dark:text-slate-400 text-[10px] mb-0.5 line-clamp-1">
                      {{ $book->author_display ?? 'Unknown' }}
                    </p>
                    @if($book->reviews->count() > 0)
                    <div class="flex items-center gap-1 text-[10px]">
                      <svg class="w-3 h-3 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                      </svg>
                      <span class="text-yellow-600 dark:text-yellow-400 font-semibold">{{ number_format($book->reviews->avg('rating'), 1) }}</span>
                      <span class="text-slate-400">({{ $book->reviews->count() }})</span>
                    </div>
                    @endif
                  </div>
                </a>
              </div>
            @endforeach
          </div>
        </section>
      @empty
        <p class="text-center text-gray-400 py-12">Ангилал бүхий ном олдсонгүй.</p>
      @endforelse
    @else
      {{-- Шүүлттэй үед: нийтлэг grid --}}
      <!-- All books in a grid -->
      <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-3">
        @forelse ($books as $book)
          @php
            $inWishlist = in_array($book->id, $wishlistIds);
            $isNew = $book->created_at && $book->created_at->diffInHours(now()) < 24;
          @endphp
          <div class="group relative text-[12px]" data-book-id="{{ $book->id }}">
            {{-- Wishlist Button --}}
            @auth
              <button onclick="event.stopPropagation(); toggleWishlist({{ $book->id }}, this)" 
                      class="wishlist-btn absolute top-1 right-1 z-20 p-1.5 rounded-full bg-white/90 dark:bg-slate-800/90 backdrop-blur shadow-md hover:scale-110 transition-all {{ $inWishlist ? 'text-pink-500' : 'text-slate-400' }}"
                      data-book-id="{{ $book->id }}"
                      title="{{ $inWishlist ? 'Wishlist-аас хасах' : 'Wishlist-д нэмэх' }}">
                <svg class="w-4 h-4" fill="{{ $inWishlist ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>
              </button>
            @endauth
            <a href="{{ route('books.show', $book->id) }}" class="block focus:outline-none">
              <div class="relative aspect-[2/3] rounded-md overflow-hidden mb-2 shadow-sm transition-all duration-300 group-hover:shadow-md group-hover:-translate-y-0.5">
                @if($isNew)
                  <div class="absolute top-0 left-0 bg-red-500 text-white text-[8px] font-bold px-1.5 py-0.5 rounded-br-md z-10 uppercase tracking-wider">
                    Latest 24hours
                  </div>
                @else
                  <div class="absolute top-0 left-0 bg-slate-800/80 backdrop-blur text-white text-[8px] font-bold px-1.5 py-0.5 rounded-br-md z-10 uppercase tracking-wider">
                    {{ $book->created_at ? $book->created_at->format('m.d D') : 'N/A' }}
                  </div>
                @endif
                <img
                  src="{{ $book->cover_image ? asset('storage/'.$book->cover_image) : asset('images/placeholder-book.png') }}"
                  alt="{{ $book->title }}"
                  class="w-full h-full object-cover transition duration-500 group-hover:scale-105"
                  loading="lazy">
                <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
              </div>

              <div>
                <h3 class="font-bold text-slate-900 dark:text-white text-[12px] leading-tight mb-0.5 line-clamp-1 group-hover:text-cyan-600 dark:group-hover:text-cyan-400 transition-colors">
                  {{ $book->title }}
                </h3>
                <p class="text-slate-500 dark:text-slate-400 text-[10px] font-medium mb-1 line-clamp-1">
                  {{ $book->author_display ?? 'Unknown' }}
                </p>

                <div class="flex items-center justify-between gap-2 text-[10px]">
                  <div class="flex items-center gap-2">
                    <span class="font-bold text-slate-900 dark:text-white">
                      #{{ str_pad($loop->iteration, 3, '0', STR_PAD_LEFT) }}
                    </span>
                    <span class="text-slate-500 dark:text-slate-400">
                      {{ number_format($book->price) }}₮
                    </span>
                  </div>
                  @if($book->reviews->count() > 0)
                  <div class="flex items-center gap-1">
                    <svg class="w-3 h-3 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                      <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    <span class="text-yellow-600 dark:text-yellow-400 font-semibold">{{ number_format($book->reviews->avg('rating'), 1) }}</span>
                  </div>
                  @endif
                </div>
              </div>
            </a>
          </div>
        @empty
          <div class="col-span-full text-center text-gray-400 py-12">
            @if(request('q'))
              "{{ request('q') }}" -тэй тохирох ном олдсонгүй.
            @elseif(request('category_id'))
              Сонгосон ангилалд ном олдсонгүй.
            @elseif(request('author_id'))
              Сонгосон зохиолчийн ном олдсонгүй.
            @else
              Номын мэдээлэл олдсонгүй.
            @endif
          </div>
        @endforelse
      </div>
    @endif
  </main>
</section>

@include('include.footer')

<script>
  const token = '{{ csrf_token() }}';
  
  // Global wishlist toggle function
  async function toggleWishlist(bookId, button) {
    if (button.disabled) return;
    
    button.disabled = true;
    const svg = button.querySelector('svg');
    
    try {
      const res = await fetch("{{ route('wishlist.toggle') }}", {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': token,
          'Accept': 'application/json',
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ book_id: bookId })
      });
      
      if (!res.ok) throw new Error('Network error');
      
      const data = await res.json();
      const active = data.in_wishlist === true;
      
      // Update button styles
      button.classList.toggle('text-pink-500', active);
      button.classList.toggle('text-slate-400', !active);
      button.title = active ? 'Wishlist-аас хасах' : 'Wishlist-д нэмэх';
      
      // Update SVG fill
      if (svg) {
        svg.setAttribute('fill', active ? 'currentColor' : 'none');
      }
      
      // Update text if exists (for detail page button)
      const textSpan = button.querySelector('span');
      if (textSpan) {
        textSpan.textContent = active ? 'Хадгалсан' : 'Хадгалах';
        button.classList.toggle('bg-pink-600', active);
        button.classList.toggle('text-white', active);
        button.classList.toggle('bg-white', !active);
        button.classList.toggle('dark:bg-slate-800', !active);
        button.classList.toggle('text-slate-700', !active);
        button.classList.toggle('dark:text-slate-300', !active);
        button.classList.toggle('border', !active);
        button.classList.toggle('border-gray-300', !active);
        button.classList.toggle('dark:border-slate-600', !active);
      }
      
      // Update all buttons with same book ID on the page
      document.querySelectorAll(`button[data-book-id="${bookId}"]`).forEach(btn => {
        if (btn !== button) {
          btn.classList.toggle('text-pink-500', active);
          btn.classList.toggle('text-slate-400', !active);
          const btnSvg = btn.querySelector('svg');
          if (btnSvg) {
            btnSvg.setAttribute('fill', active ? 'currentColor' : 'none');
          }
        }
      });
      
      // Update header wishlist count if exists
      const headerBadge = document.querySelector('a[href*="wishlist"] .bg-pink-500');
      if (headerBadge) {
        const currentCount = parseInt(headerBadge.textContent) || 0;
        const newCount = active ? currentCount + 1 : Math.max(0, currentCount - 1);
        headerBadge.textContent = newCount;
        if (newCount === 0) {
          headerBadge.classList.add('hidden');
        } else {
          headerBadge.classList.remove('hidden');
        }
      }
      
    } catch (e) {
      console.error('Wishlist toggle failed:', e);
      alert('Алдаа гарлаа. Дахин оролдоно уу.');
    } finally {
      button.disabled = false;
    }
  }
  
  // Old code for compatibility
  (function () {
    document.querySelectorAll('.wishlist-btn').forEach(btn => {
      if (!btn.hasAttribute('onclick')) {
        btn.addEventListener('click', async (e) => {
          e.stopPropagation();
          e.preventDefault();
          const id = btn.getAttribute('data-book-id');
          await toggleWishlist(id, btn);
        });
      }
    });
  })();
</script>