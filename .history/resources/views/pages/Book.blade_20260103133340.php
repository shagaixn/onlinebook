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

    <form method="GET" action="{{ route('book') }}" class="max-w-5xl mx-auto mb-6 flex flex-col sm:flex-row items-center gap-3 justify-center bg-white/70 dark:bg-slate-900/60 backdrop-blur rounded-2xl px-4 py-4 shadow-lg border border-slate-200/70 dark:border-slate-800/70">
      <label for="category_id" class="sr-only">Category</label>
      <div class="relative w-full sm:w-48">
        <select id="category_id" name="category_id"
          class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm appearance-none dark:[color-scheme:dark]"
          onchange="this.form.submit()">
          <option value="">Бүх ангилал</option>
          @isset($categories)
            @foreach($categories as $cat)
              <option value="{{ $cat->id }}" @selected(($categoryId ?? null) == $cat->id)>{{ $cat->name }}</option>
            @endforeach
          @endisset
        </select>
        <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-slate-400"><i class="fa-solid fa-chevron-down text-xs"></i></span>
      </div>

      <div class="relative w-full sm:flex-1">
        <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Гарчиг эсвэл зохиолчоор хайх..."
          class="w-full pl-9 pr-4 py-3 rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm" />
      </div>

      <button type="submit" class="w-full sm:w-auto px-6 py-3 rounded-xl bg-blue-600 text-white hover:bg-blue-700 transition shadow-sm font-medium">
        Хайх
      </button>
    </form>

    @if(request('q') || request('category_id'))
      <p class="text-center text-sm text-gray-500 dark:text-gray-400 mb-6 flex items-center justify-center gap-3">
        @if(request('q')) <span>Хайлтын үг: <span class="font-semibold">"{{ request('q') }}"</span></span> @endif
        @if(request('q') && request('category_id')) <span aria-hidden="true">·</span> @endif
        @if(request('category_id') && isset($categories))
          <span>Ангилал: <span class="font-semibold">{{ optional($categories->firstWhere('id', (int)request('category_id')))->name ?? 'Бүх ангилал' }}</span></span>
        @endif
        @isset($books)
          <span class="px-3 py-1 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-200 font-semibold">
            {{ $books->count() }} үр дүн
          </span>
        @endisset
      </p>
    @endif

    @if(!($categoryId ?? null) && !($categoryName ?? ''))
      {{-- Шүүлтгүй үед: ангилал тус бүрээр бүлэглэж харуулах --}}
      @forelse(($categoryRows ?? collect()) as $row)
        @php $cat = $row['category']; $items = $row['books']; @endphp
        <section class="mb-10">
          <div class="flex items-center justify-between mb-3">
            <h2 class="text-xl font-bold text-slate-900 dark:text-white">{{ $cat->name }}</h2>
            <a href="{{ route('book', ['category_id' => $cat->id]) }}" class="text-blue-600 hover:text-blue-700 dark:text-cyan-300 font-medium">See more →</a>
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
        <p class="text-center text-gray-400">Ангилал бүхий ном олдсонгүй.</p>
      @endforelse
    @else
      {{-- Шүүлттэй үед: нийтлэг grid --}}
      <!-- Smaller cards: more columns, tighter gap -->
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
          <div class="col-span-3 text-center text-gray-400">
            @if(request('q'))
              "{{ request('q') }}" -тэй тохирох ном олдсонгүй.
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