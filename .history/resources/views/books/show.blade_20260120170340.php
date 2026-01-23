@include('include.header')

<main class="bg-dark-50 dark:bg-slate-900 min-h-screen py-12 transition-colors duration-300">
    <a href="{{ route('book') }}" class="inline-flex items-center gap-2 text-blue-600 dark:text-blue-300 hover:underline mb-6 px-6 pt-6">
        ← Буцах
    </a>
    <div class="max-w-4xl mx-auto rounded-3xl overflow-hidden flex flex-col md:flex-row
                bg-dark dark:bg-white/5 backdrop-blur border border-gray-200 dark:border-white/10 shadow-xl transition-colors duration-300">
    
    <!-- Book Image -->
    <div class="md:w-1/2 flex items-center justify-center bg-gradient-to-br from-blue-50 to-blue-100 dark:from-slate-800 dark:to-slate-700 p-8 transition-colors duration-300">
   <img src="{{ $book->cover_image ? asset('storage/' . $book->cover_image) : 'https://via.placeholder.com/250x360?text=No+Image' }}"
     alt="{{ $book->title }}"
     class="rounded-xl shadow-lg w-64 h-96 object-cover border-4 border-white dark:border-slate-700 transition-all duration-300">
    </div>
  <!-- Book Info -->
  <div class="md:w-1/2 p-8 flex flex-col justify-center transition-colors duration-300">
  <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white mb-2 drop-shadow-sm">{{ $book->title }}</h2>
      <p class="text-gray-700 dark:text-gray-300 font-medium mb-2">
        Зохиолч: 
        @if($book->authorModel && $book->authorModel->slug)
          <a href="{{ route('authors.show', $book->authorModel->slug) }}" class="font-semibold text-blue-600 dark:text-blue-300 hover:underline">
            {{ $book->author_display }}
          </a>
        @else
          <span class="font-semibold text-gray-600 dark:text-gray-400">
            {{ $book->author_display ?? 'Зохиолчийн мэдээлэл байхгүй байна' }}
          </span>
        @endif
      </p>
      <p class="text-gray-700 dark:text-gray-300 font-medium mb-2">
        Хэвлэгдсэн огноо: 
        <span class="font-semibold text-blue-600 dark:text-blue-300">
          {{ $book->published_date ? \Carbon\Carbon::parse($book->published_date)->format('Y-m-d') : '-' }}
        </span>
      </p>
      <p class="text-gray-700 dark:text-gray-300 font-medium mb-4">
        Үнэ: <span class="font-semibold text-green-600 dark:text-green-300">{{ $book->price }}₮</span>
      </p>
      <div class="flex items-center gap-6 mb-4">
        <span class="inline-block bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-3 py-1 rounded-full font-semibold text-lg shadow-sm">{{ $book->duration ?? '-' }}</span>
      </div>
      <div class="mb-4 text-gray-700 dark:text-gray-300">
        <span class="font-semibold">Ангилал:</span>
        {{ $category ? $category->name : '-' }}
      </div>
      <div class="mt-2 flex flex-wrap gap-3">
        <a href="{{ route('books.read', $book->id) }}" aria-label="Унших"
           class="mb-4 text-gray-700 dark:text-gray-300 px-8 py-3 rounded-full shadow-md hover:from-blue-600 hover:to-indigo-700 transition font-semibold text-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 ">
          Унших
        </a>
        <button aria-label="Худалдан авах"
                class="mb-4 text-gray-700 dark:text-gray-300 px-8 py-3 rounded-full shadow-md hover:from-blue-600 hover:to-indigo-700 transition font-semibold text-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
          Худалдан авах
        </button>

      
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