@include('include.header')
@php
  // Controller-оос ирээгүй бол session-оос эсвэл хоосон массив
  $wishlistIds = $wishlistIds ?? session('wishlist.ids', []);
@endphp
<section class=" night-sky relative min-h-[80vh] flex items-center justify-center px-4 py-12">
   


<main class=" max-w-9xl mx-auto py-15 px-4">
    <h1 class="text-4xl font-bold mb-2 text-blue-700 text-center">Номуудын жагсаалт</h1>
    @if(request('q'))
        <p class="text-center text-sm text-gray-500 dark:text-gray-400 mb-6">Хайлтын үг: <span class="font-semibold">"{{ request('q') }}"</span></p>
    @endif
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
        @forelse ($books as $book)
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
            <div class="col-span-3 text-center text-gray-400">
                @if(request('q'))
                    "{{ request('q') }}" -тэй тохирох ном олдсонгүй.
                @else
                    Номын мэдээлэл олдсонгүй.
                @endif
            </div>
        @endforelse
    </div>
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
          svg.setAttribute('fill', active ? 'currentColor' : 'none');
        } catch (e) {
          console.error('Wishlist toggle failed', e);
        } finally {
          btn.disabled = false;
        }
      });
    });
  })();
</script>