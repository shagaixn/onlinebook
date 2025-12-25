@include('include.header')

<main class=" bg-dark from-blue-50 to-dark dark:from-slate-950 dark:to-slate-900 min-h-screen py-12 transition-colors duration-300">
    <a href="{{ route('book') }}" class="inline-flex items-center gap-2 text-blue-600 dark:text-blue-300 hover:underline mb-6 px-6 pt-6">
        ← Буцах
    </a>
    <div class="max-w-4xl mx-auto rounded-3xl overflow-hidden flex flex-col md:flex-row
                bg-white/5 backdrop-blur border border-white/10 shadow-xl transition-colors duration-300">
    
    <!-- Book Image -->
    <div class="md:w-1/2 flex items-center justify-center bg-gradient-to-br from-blue-100 to-blue-200 dark:from-slate-800 dark:to-slate-700 p-8 transition-colors duration-300">
   <img src="{{ $book->cover_image ? asset('storage/' . $book->cover_image) : 'https://via.placeholder.com/250x360?text=No+Image' }}"
     alt="{{ $book->title }}"
     class="rounded-xl shadow-lg w-64 h-96 object-cover border-4 border-gray-200 dark:border-slate-700 transition-all duration-300">
    </div>
  <!-- Book Info -->
  <div class="md:w-1/2 p-8 flex flex-col justify-center transition-colors duration-300">
  <h2 class="text-3xl font-extrabold text-white mb-2 drop-shadow">{{ $book->title }}</h2>
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
           class="bg-white/10 hover:bg-white/20 text-slate-100 px-6 py-3 rounded-full shadow border border-white/10 transition font-semibold text-lg">
          Унших
        </a>
        <button aria-label="Худалдан авах"
                class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white px-8 py-3 rounded-full shadow-md hover:from-blue-600 hover:to-indigo-700 transition font-semibold text-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
          Худалдан авах
        </button>

        <button type="button" 
                data-book-id="{{ $book->id }}"
                aria-pressed="{{ $inWishlist ? 'true' : 'false' }}"
                class="wishlist-btn flex items-center gap-2 px-6 py-3 rounded-full shadow border transition font-semibold text-lg focus:outline-none
                       {{ $inWishlist ? 'bg-pink-600 text-white shadow-md' : 'bg-pink-100 text-pink-700 hover:bg-pink-200' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="{{ $inWishlist ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
            </svg>
            <span>{{ $inWishlist ? 'Wishlist-д байна' : 'Wishlist' }}</span>
        </button>

      
    </div>
  </div>
  
  <!-- Reviews Section -->
  <div class="max-w-4xl mx-auto mt-12 p-6 bg-white/5 backdrop-blur border border-white/10 rounded-3xl shadow-xl">
      <h3 class="text-2xl font-bold text-white mb-6">Сэтгэгдэл & Үнэлгээ</h3>

      <!-- Average Rating -->
      <div class="flex items-center gap-4 mb-8">
          <div class="text-4xl font-bold text-yellow-400">
              {{ number_format($book->reviews->avg('rating'), 1) }}
          </div>
          <div class="flex flex-col">
              <div class="flex text-yellow-400">
                  @for($i=1; $i<=5; $i++)
                      @if($i <= round($book->reviews->avg('rating')))
                          ★
                      @else
                          ☆
                      @endif
                  @endfor
              </div>
              <span class="text-sm text-gray-400">{{ $book->reviews->count() }} сэтгэгдэл</span>
          </div>
      </div>

      <!-- Review Form -->
      @auth
          <form action="{{ route('reviews.store') }}" method="POST" class="mb-10 bg-white/5 p-6 rounded-xl border border-white/10">
              @csrf
              <input type="hidden" name="book_id" value="{{ $book->id }}">
              
              <div class="mb-4">
                  <label class="block text-gray-300 mb-2">Үнэлгээ</label>
                  <div class="flex gap-4">
                      @for($i=1; $i<=5; $i++)
                          <label class="cursor-pointer">
                              <input type="radio" name="rating" value="{{ $i }}" class="hidden peer" required>
                              <span class="text-2xl text-gray-600 peer-checked:text-yellow-400 hover:text-yellow-300 transition">★</span>
                          </label>
                      @endfor
                  </div>
              </div>

              <div class="mb-4">
                  <label class="block text-gray-300 mb-2">Сэтгэгдэл</label>
                  <textarea name="comment" rows="3" class="w-full bg-slate-800 border border-slate-700 rounded-lg p-3 text-white focus:ring-2 focus:ring-blue-500 outline-none" placeholder="Энэ номны талаар сэтгэгдлээ бичнэ үү..."></textarea>
              </div>

              <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition">
                  Илгээх
              </button>
          </form>
      @else
          <div class="mb-10 p-6 bg-blue-900/20 border border-blue-500/30 rounded-xl text-center">
              <p class="text-blue-200">Сэтгэгдэл бичихийн тулд <a href="{{ route('login') }}" class="underline font-bold">нэвтэрнэ үү</a>.</p>
          </div>
      @endauth

      <!-- Reviews List -->
      <div class="space-y-6">
          @forelse($book->reviews()->latest()->get() as $review)
              <div class="border-b border-white/10 pb-6 last:border-0">
                  <div class="flex justify-between items-start mb-2">
                      <div class="flex items-center gap-3">
                          <div class="w-10 h-10 rounded-full bg-slate-700 flex items-center justify-center text-white font-bold">
                              {{ substr($review->user->name, 0, 1) }}
                          </div>
                          <div>
                              <h4 class="text-white font-semibold">{{ $review->user->name }}</h4>
                              <span class="text-xs text-gray-400">{{ $review->created_at->diffForHumans() }}</span>
                          </div>
                      </div>
                      <div class="flex text-yellow-400 text-sm">
                          @for($i=1; $i<=5; $i++)
                              {{ $i <= $review->rating ? '★' : '☆' }}
                          @endfor
                      </div>
                  </div>
                  <p class="text-gray-300 pl-13">{{ $review->comment }}</p>
              </div>
          @empty
              <p class="text-gray-500 text-center py-4">Одоогоор сэтгэгдэл алга байна.</p>
          @endforelse
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