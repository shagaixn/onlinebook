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
           class="text-gray-700 dark:text-gray-300 px-8 py-3 rounded-full shadow-md bg-blue-600 text-white hover:bg-blue-700 transition font-semibold text-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
          Унших
        </a>

        @auth
          <button onclick="toggleWishlist({{ $book->id }}, this)" 
                  class="wishlist-btn px-6 py-3 rounded-full shadow-md transition-all font-semibold text-lg focus:outline-none focus:ring-2 focus:ring-pink-500 flex items-center gap-2 {{ $inWishlist ? 'bg-pink-600 text-white' : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-gray-300 dark:border-slate-600' }}"
                  data-book-id="{{ $book->id }}"
                  title="{{ $inWishlist ? 'Wishlist-аас хасах' : 'Wishlist-д нэмэх' }}">
            <svg class="w-5 h-5" fill="{{ $inWishlist ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
            </svg>
            <span>{{ $inWishlist ? 'Хадгалсан' : 'Хадгалах' }}</span>
          </button>
        @else
          <a href="{{ route('login') }}" 
             class="px-6 py-3 rounded-full shadow-md transition-all font-semibold text-lg bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-gray-300 dark:border-slate-600 hover:border-pink-500 dark:hover:border-pink-500 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
            </svg>
            <span>Хадгалах</span>
          </a>
        @endauth
      
    </div>
  </div>
</div>

  {{-- Reviews Section --}}
  <div class="max-w-4xl mx-auto mt-8 px-6">
    {{-- Average Rating Display --}}
    <div class="bg-dark dark:bg-white/5 backdrop-blur border border-gray-200 dark:border-white/10 rounded-2xl p-6 mb-6 transition-colors duration-300">
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
        @else
        <div class="text-center">
          <div class="flex items-center gap-2">
            <span class="text-3xl font-bold text-gray-400">0.0</span>
            <div class="text-gray-400 text-2xl">★</div>
          </div>
          <p class="text-sm text-gray-600 dark:text-gray-400">Үнэлгээ байхгүй</p>
        </div>
        @endif
      </div>

      {{-- Rating Distribution --}}
      @if($book->reviews->count() > 0)
      <div class="mb-6 p-4 bg-dark dark:bg-white/5 rounded-xl">
        <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Үнэлгээний задаргаа</h4>
        @php
          $totalReviews = $book->reviews->count();
          $ratingCounts = $book->reviews->groupBy('rating')->map->count();
        @endphp
        @for($star = 5; $star >= 1; $star--)
          @php
            $count = $ratingCounts->get($star, 0);
            $percentage = $totalReviews > 0 ? ($count / $totalReviews) * 100 : 0;
          @endphp
          <div class="flex items-center gap-3 mb-2">
            <div class="flex items-center gap-1 w-12">
              <span class="text-sm text-gray-700 dark:text-gray-300">{{ $star }}</span>
              <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
              </svg>
            </div>
            <div class="flex-1 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
              <div class="bg-yellow-400 h-2 rounded-full transition-all duration-300" style="width: {{ $percentage }}%"></div>
            </div>
            <span class="text-sm text-gray-600 dark:text-gray-400 w-12 text-right">{{ $count }}</span>
          </div>
        @endfor
      </div>
      @endif

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

  // Wishlist toggle function
  const token = '{{ csrf_token() }}';
  
  async function toggleWishlist(bookId, button) {
    if (button.disabled) return;
    
    button.disabled = true;
    const svg = button.querySelector('svg');
    const textSpan = button.querySelector('span');
    
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
      button.classList.toggle('bg-pink-600', active);
      button.classList.toggle('text-white', active);
      button.classList.toggle('bg-white', !active);
      button.classList.toggle('dark:bg-slate-800', !active);
      button.classList.toggle('text-slate-700', !active);
      button.classList.toggle('dark:text-slate-300', !active);
      button.classList.toggle('border', !active);
      button.classList.toggle('border-gray-300', !active);
      button.classList.toggle('dark:border-slate-600', !active);
      
      // Update SVG fill
      if (svg) {
        svg.setAttribute('fill', active ? 'currentColor' : 'none');
      }
      
      // Update text
      if (textSpan) {
        textSpan.textContent = active ? 'Хадгалсан' : 'Хадгалах';
      }
      
      button.title = active ? 'Wishlist-аас хасах' : 'Wishlist-д нэмэх';
      
      // Update header wishlist count badge
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
      console.error('Wishlist toggle error:', e);
      alert('Алдаа гарлаа. Дахин оролдоно уу.');
    } finally {
      button.disabled = false;
    }
  }
</script>