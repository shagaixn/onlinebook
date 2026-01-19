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
        @if ($book->authorModel)
          <a href="{{ route('authors.show', $book->authorModel->slug) }}" class="font-semibold text-blue-600 dark:text-blue-300 hover:underline">
            {{ $book->author_display }}
          </a>
        @else
          <span class="font-semibold text-blue-600 dark:text-blue-300">{{ $book->author_display ?? '-' }}</span>
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
        @if ($book->categories && $book->categories->count() > 0)
          <div class="flex flex-wrap gap-2 mt-2">
            @foreach ($book->categories as $cat)
              <span class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-3 py-1 rounded-full text-sm">
                {{ $cat->name }}
              </span>
            @endforeach
          </div>
        @elseif ($category)
          <span class="inline-block bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-3 py-1 rounded-full text-sm mt-2">
            {{ $category->name }}
          </span>
        @else
          <span class="text-gray-500">-</span>
        @endif
      </div>
      @if ($book->pages)
      <div class="mb-4 text-gray-700 dark:text-gray-300">
        <span class="font-semibold">Хуудасны тоо:</span>
        <span class="text-gray-600 dark:text-gray-400">{{ $book->pages }}</span>
      </div>
      @endif
      <div class="mt-2 flex flex-wrap gap-3">
        <a href="{{ route('books.read', $book->id) }}" aria-label="Унших"
           class="bg-slate-800 hover:bg-slate-700 dark:bg-white/10 dark:hover:bg-white/20 text-white dark:text-slate-100 px-6 py-3 rounded-full shadow border border-transparent dark:border-white/10 transition font-semibold text-lg">
          Унших
        </a>
        <button aria-label="Худалдан авах"
                class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white px-8 py-3 rounded-full shadow-md hover:from-blue-600 hover:to-indigo-700 transition font-semibold text-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
          Худалдан авах
        </button>

      
    </div>
  </div>

<!-- Book Description Section -->
@if ($book->description)
<div class="max-w-4xl mx-auto mt-8 bg-white dark:bg-white/5 backdrop-blur border border-gray-200 dark:border-white/10 shadow-xl rounded-3xl p-8 transition-colors duration-300">
  <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-4">📖 Тайлбар</h3>
  <div class="text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-wrap">
    {{ $book->description }}
  </div>
</div>
@endif

<!-- Reviews Section -->
<div class="max-w-4xl mx-auto mt-8 bg-white dark:bg-white/5 backdrop-blur border border-gray-200 dark:border-white/10 shadow-xl rounded-3xl p-8 transition-colors duration-300">
  <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-6">⭐ Үнэлгээ болон сэтгэгдэл</h3>
  
  @auth
  <!-- Review Form -->
  <div class="mb-8 bg-gray-50 dark:bg-slate-800 rounded-xl p-6">
    <h4 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Сэтгэгдэл үлдээх</h4>
    <form action="{{ route('reviews.store') }}" method="POST">
      @csrf
      <input type="hidden" name="book_id" value="{{ $book->id }}">
      
      <div class="mb-4">
        <label class="block text-gray-700 dark:text-gray-300 font-medium mb-2">Үнэлгээ</label>
        <div class="flex gap-2">
          @for ($i = 1; $i <= 5; $i++)
            <input type="radio" name="rating" value="{{ $i }}" id="rating-{{ $i }}" class="hidden peer/rating-{{ $i }}" required>
            <label for="rating-{{ $i }}" class="cursor-pointer text-3xl text-gray-300 peer-checked/rating-{{ $i }}:text-yellow-400 hover:text-yellow-300">
              ⭐
            </label>
          @endfor
        </div>
      </div>
      
      <div class="mb-4">
        <label for="comment" class="block text-gray-700 dark:text-gray-300 font-medium mb-2">Сэтгэгдэл</label>
        <textarea name="comment" id="comment" rows="4" 
                  class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-slate-700 dark:text-white"
                  placeholder="Энэ номын талаар таны бодол..."></textarea>
      </div>
      
      <button type="submit" 
              class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold transition">
        Сэтгэгдэл үлдээх
      </button>
    </form>
  </div>
  @else
  <div class="mb-8 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-xl p-4">
    <p class="text-yellow-800 dark:text-yellow-200">
      Сэтгэгдэл үлдээхийн тулд <a href="{{ route('login') }}" class="underline font-semibold">нэвтэрнэ үү</a>
    </p>
  </div>
  @endauth
  
  <!-- Reviews List -->
  @if ($book->reviews && $book->reviews->count() > 0)
    <div class="space-y-4">
      @foreach ($book->reviews as $review)
      <div class="bg-gray-50 dark:bg-slate-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
        <div class="flex items-start justify-between mb-2">
          <div>
            <h5 class="font-semibold text-gray-800 dark:text-white">{{ $review->user->name ?? 'Хэрэглэгч' }}</h5>
            <div class="flex items-center gap-1 mt-1">
              @for ($i = 1; $i <= 5; $i++)
                <span class="text-{{ $i <= $review->rating ? 'yellow' : 'gray' }}-400 text-lg">⭐</span>
              @endfor
            </div>
          </div>
          <span class="text-sm text-gray-500 dark:text-gray-400">
            {{ $review->created_at->diffForHumans() }}
          </span>
        </div>
        @if ($review->comment)
          <p class="text-gray-700 dark:text-gray-300 mt-3">{{ $review->comment }}</p>
        @endif
      </div>
      @endforeach
    </div>
  @else
    <p class="text-gray-500 dark:text-gray-400 text-center py-8">
      Одоогоор сэтгэгдэл байхгүй байна
    </p>
  @endif
</div>

<!-- Related Books Section -->
@if ($relatedBooks && $relatedBooks->count() > 0)
<div class="max-w-4xl mx-auto mt-8 mb-12 bg-white dark:bg-white/5 backdrop-blur border border-gray-200 dark:border-white/10 shadow-xl rounded-3xl p-8 transition-colors duration-300">
  <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-6">📚 Холбоотой номууд</h3>
  <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
    @foreach ($relatedBooks as $relatedBook)
    <a href="{{ route('books.show', $relatedBook->id) }}" 
       class="bg-gray-50 dark:bg-slate-800 rounded-xl overflow-hidden hover:shadow-lg transition">
      <div class="aspect-[2/3] bg-gray-200 dark:bg-slate-700">
        @if ($relatedBook->cover_image)
          <img src="{{ asset('storage/' . $relatedBook->cover_image) }}" 
               alt="{{ $relatedBook->title }}"
               class="w-full h-full object-cover">
        @else
          <div class="w-full h-full flex items-center justify-center text-4xl">📚</div>
        @endif
      </div>
      <div class="p-3">
        <h4 class="font-semibold text-gray-800 dark:text-white text-sm line-clamp-2">
          {{ $relatedBook->title }}
        </h4>
        <p class="text-gray-600 dark:text-gray-400 text-xs mt-1">
          {{ $relatedBook->author_display ?? '-' }}
        </p>
      </div>
    </a>
    @endforeach
  </div>
</div>
@endif

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