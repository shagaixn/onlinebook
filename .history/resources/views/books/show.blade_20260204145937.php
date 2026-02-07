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
  <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white mb-2 drop-shadow-sm">
    <a href="{{ route('books.show', $book->id) }}" class="hover:text-blue-600 dark:hover:text-blue-300 underline-offset-4 hover:underline">
      {{ $book->title }}
    </a>
  </h2>
      <p class="text-gray-700 dark:text-gray-300 font-medium mb-2">
        Зохиолч: 
        @if($book->authorModel && $book->authorModel->slug)
          <a href="{{ route('authors.show', $book->authorModel->slug) }}" class="font-semibold text-blue-600 dark:text-blue-300 hover:underline">
            {{ $book->author_display }}
          </a>
        @elseif($book->author_display)
          <span class="font-semibold text-gray-600 dark:text-gray-400">
            {{ $book->author_display }}
          </span>
        @else
          <span class="font-semibold text-gray-600 dark:text-gray-400">Байхгүй</span>
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
        @if($book->categories && $book->categories->count() > 0)
          <div class="flex flex-wrap gap-2 mt-1">
            @foreach($book->categories as $cat)
              <span class="inline-block px-3 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded-full text-sm">
                {{ $cat->name }}
              </span>
            @endforeach
          </div>
        @else
          <span>{{ $category ? $category->name : '-' }}</span>
        @endif
      </div>
      <div class="mt-2 flex flex-wrap gap-3">
        <a href="{{ route('books.read', $book->id) }}" aria-label="Унших"
           class="text-gray-700 dark:text-gray-300 px-8 py-3 rounded-full shadow-md bg-blue-600 text-white hover:bg-blue-700 transition font-semibold text-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
          Унших
        </a>

        @auth
          <button onclick="toggleWishlist({{ $book->id }}, this)" 
                  class="wishlist-btn px-6 py-3 rounded-full shadow-md transition-all font-semibold text-lg focus:outline-none focus:ring-2 focus:ring-pink-500 flex items-center gap-2 {{ $inWishlist ? 'bg-pink-600 text-white' : 'bg-dark dark:bg-dark text-slate-700 dark:text-slate-300 border border-gray-300 dark:border-slate-600' }}"
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