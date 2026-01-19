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

                <div class="flex items-center gap-2 text-[10px]">
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
  (function () {
    const token = '{{ csrf_token() }}';
    document.querySelectorAll('.wishlist-btn').forEach(btn => {
      btn.addEventListener('click', async (e) => {
        e.stopPropagation();
        const id = btn.getAttribute('data-book-id');
        btn.disabled = true;
        try {
          const res = await fetch("{{ route('wishlist.toggle') }}", {
            method: 'POST',
            headers: {
              'X-CSRF-TOKEN': token,
              'Accept': 'application/json',
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({ book_id: id })
          });
          const data = await res.json();
          const active = data.in_wishlist === true;
          btn.setAttribute('aria-pressed', active ? 'true' : 'false');
          btn.classList.toggle('bg-pink-600', active);
          btn.classList.toggle('text-white', active);
          btn.classList.toggle('shadow', active);
          btn.classList.toggle('bg-white/10', !active);
          btn.classList.toggle('text-pink-300', !active);
          const svg = btn.querySelector('svg');
          svg?.setAttribute('fill', active ? 'currentColor' : 'none');
        } catch (e) {
          console.error('Wishlist toggle failed', e);
        } finally {
          btn.disabled = false;
        }
      });
    });
  })();
</script>