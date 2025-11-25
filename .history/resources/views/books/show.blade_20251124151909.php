@include('include.header')

<main class=" bg-dark from-blue-50 to-dark dark:from-slate-950 dark:to-slate-900 min-h-screen py-12 transition-colors duration-300">
    <a href="{{ route('book') }}" class="inline-flex items-center gap-2 text-blue-600 dark:text-blue-300 hover:underline mb-6 px-6 pt-6">
        ← Буцах
    </a>
    <div class="max-w-4xl mx-auto bg-dark dark:bg-slate-800 rounded-3xl shadow-2xl overflow-hidden flex flex-col md:flex-row transition-colors duration-300 border border-gray-200 dark:border-slate-700">
    
    <!-- Book Image -->
    <div class="md:w-1/2 flex items-center justify-center bg-gradient-to-br from-blue-100 to-blue-200 dark:from-slate-800 dark:to-slate-700 p-8 transition-colors duration-300">
   <img src="{{ $book->cover_image ? asset('storage/' . $book->cover_image) : 'https://via.placeholder.com/250x360?text=No+Image' }}"
     alt="{{ $book->title }}"
     class="rounded-xl shadow-lg w-64 h-96 object-cover border-4 border-gray-200 dark:border-slate-700 transition-all duration-300">
    </div>
  <!-- Book Info -->
  <div class="md:w-1/2 p-8 flex flex-col justify-center transition-colors duration-300 bg-dark dark:bg-slate-800">
  <h2 class="text-3xl font-extrabold text-blue-800 dark:text-blue-200 mb-2 drop-shadow">{{ $book->title }}</h2>
      <p class="text-gray-700 dark:text-gray-300 font-medium mb-2">
        Зохиолч: <span class="font-semibold text-blue-500 dark:text-blue-300">{{ $book->author ?? '-' }}</span>
      </p>
      <p class="text-gray-700 dark:text-gray-300 font-medium mb-2">
        Хэвлэгдсэн огноо: 
        <span class="font-semibold text-blue-500 dark:text-blue-300">
          {{ $book->published_date ? \Carbon\Carbon::parse($book->published_date)->format('Y-m-d') : '-' }}
        </span>
      </p>
      <p class="text-gray-700 dark:text-gray-300 font-medium mb-4">
        Үнэ: <span class="font-semibold text-green-700 dark:text-green-300">{{ $book->price }}₮</span>
      </p>
      <div class="flex items-center gap-6 mb-4">
        <span class="inline-block bg-blue-200 dark:bg-blue-900 text-blue-900 dark:text-blue-200 px-3 py-1 rounded-full font-semibold text-lg shadow">{{ $book->duration ?? '-' }}</span>
      </div>
      <div class="mb-4 text-gray-700 dark:text-gray-300">
        <span class="font-semibold">Ангилал:</span>
        {{ $category ? $category->name : '-' }}
      </div>
      <div class="mt-2 flex flex-wrap gap-3">
        <a href="{{ route('books.read', $book->id) }}" aria-label="Унших"
           class="bg-white/90 dark:bg-slate-900/60 border border-white/40 dark:border-slate-700 text-slate-700 dark:text-slate-200 px-6 py-3 rounded-full shadow hover:shadow-md transition font-semibold text-lg">
          Унших
        </a>
        <button aria-label="Худалдан авах" class="bg-gradient-to-r from-blue-600 to-blue-400 dark:from-blue-800 dark:to-blue-600 text-white px-8 py-3 rounded-full shadow-md hover:from-blue-700 hover:to-blue-500 dark:hover:from-blue-900 dark:hover:to-blue-700 hover:scale-105 transition transform duration-200 font-semibold text-lg focus:outline-none focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-800">
          Худалдан авах
        </button>
        @php
          $inWishlist = in_array($book->id, $wishlistIds ?? []);
        @endphp
        <button
          type="button"
          class="wishlist-btn flex items-center gap-2 px-6 py-3 rounded-full font-semibold text-lg transition
            {{ $inWishlist ? 'bg-pink-600 text-white shadow-md' : 'bg-pink-100 dark:bg-pink-900/40 text-pink-700 dark:text-pink-300 hover:bg-pink-200 dark:hover:bg-pink-800/60' }}"
          data-book-id="{{ $book->id }}"
          aria-label="Wishlist-д нэмэх"
          aria-pressed="{{ $inWishlist ? 'true' : 'false' }}">
          <svg class="w-6 h-6" viewBox="0 0 24 24" fill="{{ $inWishlist ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="1.6">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M12 21s-6.5-4.35-9.2-8.34A5.5 5.5 0 0112 5.52a5.5 5.5 0 019.2 7.14C18.5 16.65 12 21 12 21Z" />
          </svg>
          <span>{{ $inWishlist ? 'Wishlist-д байна' : 'Wishlist' }}</span>
        </button>
      </div>
      <hr class="my-6 border-blue-200 dark:border-slate-700">
      
    </div>
  </div>
</main>

@include('include.footer')

<script>
  (function () {
    const token = '{{ csrf_token() }}';
    const btn = document.querySelector('.wishlist-btn');
    if (!btn) return;
    btn.addEventListener('click', async () => {
      btn.disabled = true;
      const id = btn.getAttribute('data-book-id');
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
        btn.setAttribute('aria-pressed', active ? 'true':'false');
        btn.querySelector('span').textContent = active ? 'Wishlist-д байна' : 'Wishlist';
        btn.classList.toggle('bg-pink-600', active);
        btn.classList.toggle('text-white', active);
        btn.classList.toggle('shadow-md', active);
        btn.classList.toggle('bg-pink-100', !active);
        btn.classList.toggle('text-pink-700', !active);
        const svg = btn.querySelector('svg');
        svg.setAttribute('fill', active ? 'currentColor' : 'none');
      } catch (e) {
        console.error('Wishlist toggle error', e);
      } finally {
        btn.disabled = false;
      }
    });
  })();
</script>