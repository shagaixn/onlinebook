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
      <div class="mb-4 text-gray-700 dark:text-gray-300">
        <span class="font-semibold">Ангилал:</span>
        {{ $category ? $category->name : '-' }}
      </div>
      <div class="mt-2 flex flex-wrap gap-3">
        <a href="{{ route('books.read', $book->id) }}" aria-label="Унших"
           class="mb-4 text-gray-700 dark:text-gray-300 px-8 py-3 rounded-full shadow-md hover:from-blue-600 hover:to-indigo-700 transition font-semibold text-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 ">
          Унших
        </a>

      
    </div>
  </div>
</div>

  {{-- Reviews Section --}}
  <div class="max-w-4xl mx-auto mt-8 px-6">
    {{-- Average Rating Display --}}
    <div class="bg-white dark:bg-white/5 backdrop-blur border border-gray-200 dark:border-white/10 rounded-2xl p-6 mb-6 transition-colors duration-300">
      <div class="flex items-center justify-between mb-6">
        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Үнэлгээ & Сэтгэгдэл</h3>
        @if($book->reviews->count() > 0)
        <div class="text-center">
          <div class="flex items-center gap-2">
            <span class="text-3xl font-bold text-yellow-500">{{ number_format($book->reviews->avg('rating'), 1) }}</span>
            <div class="text-yellow-400 text-2xl">★</div>
          </div>
          <p class="text-sm text-gray-600 dark:text-gray-400">{{ $book->reviews->count() }} үнэлгээ</p>
        </div>
        @endif
      </div>

      {{-- Review Form (Only for logged-in users) --}}
      @auth
        <div class="mb-8 p-6 bg-gray-50 dark:bg-white/5 rounded-xl border border-gray-200 dark:border-white/10">
          <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
            @if($userReview)
              Таны үнэлгээг засах
            @else
              Таны үнэлгээ
            @endif
          </h4>
          
          @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 dark:bg-green-900/20 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-300 rounded-lg">
              {{ session('success') }}
            </div>
          @endif

          @if($errors->any())
            <div class="mb-4 p-4 bg-red-100 dark:bg-red-900/20 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-300 rounded-lg">
              <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form action="{{ route('reviews.store') }}" method="POST">
            @csrf
            <input type="hidden" name="book_id" value="{{ $book->id }}">
            
            {{-- Star Rating --}}
            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Үнэлгээ</label>
              <div class="flex gap-2 items-center">
                @for($i = 1; $i <= 5; $i++)
                  <label class="cursor-pointer">
                    <input type="radio" name="rating" value="{{ $i }}" class="hidden star-radio" 
                           {{ ($userReview && $userReview->rating == $i) || old('rating') == $i ? 'checked' : '' }} required>
                    <svg class="w-8 h-8 star-icon text-gray-300 hover:text-yellow-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                    </svg>
                  </label>
                @endfor
              </div>
            </div>

            {{-- Comment --}}
            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Сэтгэгдэл (заавал биш)</label>
              <textarea name="comment" rows="4" class="w-full px-4 py-2 border border-gray-300 dark:border-white/20 rounded-lg bg-white dark:bg-white/5 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors" placeholder="Энэ номын талаархи таны бодол...">{{ old('comment', $userReview->comment ?? '') }}</textarea>
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
              @if($userReview)
                Үнэлгээ шинэчлэх
              @else
                Үнэлгээ өгөх
              @endif
            </button>
          </form>
        </div>
      @else
        <div class="mb-8 p-6 bg-gray-50 dark:bg-white/5 rounded-xl border border-gray-200 dark:border-white/10 text-center">
          <p class="text-gray-600 dark:text-gray-400 mb-3">Үнэлгээ өгөхийн тулд нэвтэрнэ үү</p>
          <a href="{{ route('login') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition-colors">
            Нэвтрэх
          </a>
        </div>
      @endauth

      {{-- Reviews List --}}
      <div class="space-y-4">
        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Бүх үнэлгээ</h4>
        
        @forelse($book->reviews()->latest()->get() as $review)
          <div class="p-4 bg-gray-50 dark:bg-white/5 rounded-xl border border-gray-200 dark:border-white/10">
            <div class="flex items-start justify-between mb-2">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-semibold">
                  {{ substr($review->user->name ?? 'U', 0, 1) }}
                </div>
                <div>
                  <p class="font-medium text-gray-900 dark:text-white">{{ $review->user->name ?? 'Хэрэглэгч' }}</p>
                  <div class="flex items-center gap-1">
                    @for($i = 1; $i <= 5; $i++)
                      <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                      </svg>
                    @endfor
                  </div>
                </div>
              </div>
              <span class="text-xs text-gray-500 dark:text-gray-400">{{ $review->created_at->diffForHumans() }}</span>
            </div>
            
            @if($review->comment)
              <p class="text-gray-700 dark:text-gray-300 mt-3">{{ $review->comment }}</p>
            @endif
          </div>
        @empty
          <p class="text-center text-gray-500 dark:text-gray-400 py-8">Одоогоор үнэлгээ байхгүй байна.</p>
        @endforelse
      </div>
    </div>
  </div>
</main>

@include('include.footer')

<script>
  // Star Rating Interaction
  document.addEventListener('DOMContentLoaded', function() {
    const starRadios = document.querySelectorAll('.star-radio');
    const starIcons = document.querySelectorAll('.star-icon');
    
    // Function to update star display
    function updateStars(rating) {
      starIcons.forEach((icon, index) => {
        if (index < rating) {
          icon.classList.remove('text-gray-300');
          icon.classList.add('text-yellow-400');
          icon.setAttribute('fill', 'currentColor');
        } else {
          icon.classList.remove('text-yellow-400');
          icon.classList.add('text-gray-300');
          icon.setAttribute('fill', 'none');
        }
      });
    }
    
    // Add click event to each star
    starRadios.forEach((radio, index) => {
      radio.addEventListener('change', function() {
        updateStars(index + 1);
      });
    });
    
    // Hover effect
    starIcons.forEach((icon, index) => {
      icon.parentElement.addEventListener('mouseenter', function() {
        starIcons.forEach((ic, i) => {
          if (i <= index) {
            ic.classList.add('text-yellow-400');
            ic.classList.remove('text-gray-300');
          }
        });
      });
      
      icon.parentElement.addEventListener('mouseleave', function() {
        const checkedRadio = document.querySelector('.star-radio:checked');
        if (checkedRadio) {
          updateStars(parseInt(checkedRadio.value));
        } else {
          starIcons.forEach(ic => {
            ic.classList.remove('text-yellow-400');
            ic.classList.add('text-gray-300');
            ic.setAttribute('fill', 'none');
          });
        }
      });
    });
    
    // Check if there's a pre-selected rating
    const checkedRadio = document.querySelector('.star-radio:checked');
    if (checkedRadio) {
      updateStars(parseInt(checkedRadio.value));
    }
  });

  // Wishlist functionality (existing)
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