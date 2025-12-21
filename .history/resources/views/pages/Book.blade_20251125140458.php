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
    <div class="flex flex-wrap justify-center gap-8 group">
        @forelse ($books as $book)
            @php $inWishlist = in_array($book->id, $wishlistIds); @endphp
            <div class="relative w-48 h-72 rounded-2xl overflow-hidden shadow-xl
                        bg-white/5 backdrop-blur border border-white/10 cursor-pointer group/book transition-all duration-300">
                <!-- Wishlist icon -->
                <button
                    type="button"
                    class="wishlist-btn absolute top-2 right-2 z-10 inline-flex items-center justify-center p-2 rounded-full transition
                    {{ $inWishlist ? 'bg-pink-600 text-white shadow' : 'bg-white/10 text-pink-300 hover:bg-white/20 backdrop-blur-sm' }}"
                    data-book-id="{{ $book->id }}"
                    aria-label="Wishlist-д нэмэх"
                    aria-pressed="{{ $inWishlist ? 'true' : 'false' }}">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="{{ $inWishlist ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="1.6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 21s-6.5-4.35-9.2-8.34A5.5 5.5 0 0112 5.52a5.5 5.5 0 019.2 7.14C18.5 16.65 12 21 12 21Z" />
                    </svg>
                </button>

                @if($book->cover_image)
                    <img src="{{ asset('storage/' . $book->cover_image) }}"
                         alt="{{ $book->title }}"
                         class="w-full h-full object-cover transition-all duration-500 group-hover:blur-sm group-hover:brightness-75 group-hover/book:!blur-none group-hover/book:!brightness-100" />
                @else
                    <div class="w-full h-full bg-blue-100 flex items-center justify-center rounded-2xl text-gray-400 text-xs">
                        No Image
                    </div>
                @endif
                <div class="absolute inset-0 flex flex-col justify-end opacity-0 group-hover/book:opacity-100 transition duration-300
                            bg-gradient-to-t from-[#0f0c24]/90 via-transparent to-transparent p-4">
                    <h2 class="text-lg font-semibold mb-1 text-white">{{ $book->title }}</h2>
                    <p class="text-white/80 mb-2 line-clamp-2 text-xs">Зохиолч: {{ $book->author }}</p>
                    <div class="flex gap-2">
                        <a href="{{ route('books.show', $book->id) }}"
                           class="bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white px-3 py-1.5 rounded-full font-medium text-[11px] shadow">
                          Дэлгэрэнгүй
                        </a>
                    </div>
                </div>
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