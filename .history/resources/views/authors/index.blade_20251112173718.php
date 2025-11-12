@include('include.header')

<!-- Global overlay (whole-page blur on hover) + cursor preview container -->
<style>
  /* Whole-page blur overlay (hidden by default) */
  #global-blur-overlay {
    position: fixed;
    inset: 0;
    z-index: 40; /* below preview, above content */
    backdrop-filter: blur(8px) saturate(110%);
    -webkit-backdrop-filter: blur(8px) saturate(110%);
    background: rgba(0, 0, 0, 0.25); /* fallback tint if backdrop-filter unsupported */
    opacity: 0;
    pointer-events: none;
    transition: opacity .2s ease;
  }
  #global-blur-overlay.active {
    opacity: 1;
    pointer-events: auto; /* allows closing on click if desired */
  }

  /* Cursor-following mini preview */
  #cursor-preview {
    position: fixed;
    z-index: 50;
    width: 180px;
    height: 120px;
    border-radius: 12px;
    overflow: hidden;
    pointer-events: none;
    opacity: 0;
    transform: translate(-50%, -50%) scale(.96);
    box-shadow: 0 12px 40px rgba(0,0,0,.35);
    border: 1px solid rgba(255,255,255,.18);
    background: #0b0b10;
    transition: opacity .18s ease, transform .18s ease;
  }
  #cursor-preview.show {
    opacity: 1;
    transform: translate(-50%, -50%) scale(1);
  }
  #cursor-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    filter: contrast(1.02) saturate(1.05);
  }

  /* Respect reduced motion */
  @media (prefers-reduced-motion: reduce) {
    #global-blur-overlay { transition: none; }
    #cursor-preview { transition: none; }
  }
</style>

<div id="global-blur-overlay" aria-hidden="true"></div>
<div id="cursor-preview" aria-hidden="true" role="img">
  <img alt="" />
</div>

<div class="max-w-7xl mx-auto px-6 py-10">
  <h1 class="text-3xl font-bold mb-6 text-slate-90 dark:text-slate-100">Зохиолчид</h1>

  <form method="GET" action="{{ route('authors.index') }}" class="mb-6 flex gap-3">
      <input type="text" name="q" value="{{ request('q') }}" placeholder="Нэр эсвэл танилцуулга..."
               class="flex-2 px-4 py-2 rounded-xl border border-gray-200 dark:border-slate-70 bg-black dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500" />
      <button type="submit" class="px-5 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700">Хайх</button>
  </form>

  @if($authors->count() === 0)
      <div class="p-8 text-center text-gray-500 dark:text-gray-400 bg-white dark:bg-slate-900 rounded-xl border border-gray-100 dark:border-slate-800">
          Зохиолч олдсонгүй.
      </div>
  @else
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
          @foreach($authors as $author)
              <a href="{{ route('authors.show', $author->slug) }}"
                 class="author-card block bg-dark-900 dark:bg-slate-900 rounded-2xl shadow hover:shadow-lg border border-gray-100 dark:border-slate-800 overflow-hidden hover:-translate-y-0.5 transition">
                  @if($author->profile_image)
                      {{-- Use asset('storage/...') to avoid APP_URL mismatch issues and simplify path resolution --}}
                      <img
                          src="{{ asset('storage/'.$author->profile_image) }}"
                          alt="{{ $author->name }}"
                          loading="lazy"
                          class="author-image w-full h-48 object-cover"
                          onerror="this.onerror=null;this.src='/images/authors/default.jpg';" />
                  @else
                      <div class="w-full h-48 bg-gray-200 dark:bg-slate-800 flex items-center justify-center text-gray-500">
                          No Image
                      </div>
                  @endif
                  <div class="p-4">
                      <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100">{{ $author->name }}</h2>
                      <p class="text-sm text-gray-600 dark:text-gray-400">{{ $author->nationality ?? 'Үндэс тодорхойгүй' }}</p>
                      <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">{{ Str::limit($author->biography, 100) }}</p>
                  </div>
              </a>
          @endforeach
      </div>

      <div class="mt-8">
          {{ $authors->links() }}
      </div>
  @endif
</div>

<script>
  (function () {
    const overlay = document.getElementById('global-blur-overlay');
    const preview = document.getElementById('cursor-preview');
    const previewImg = preview.querySelector('img');

    function positionPreview(x, y) {
      const offset = 22;
      let px = x + offset;
      let py = y + offset;

      const pw = preview.offsetWidth || 180;
      const ph = preview.offsetHeight || 120;
      const vw = window.innerWidth;
      const vh = window.innerHeight;

      // Clamp to viewport edges
      if (px + pw > vw - 12) px = vw - pw - 12;
      if (py + ph > vh - 12) py = vh - ph - 12;

      preview.style.left = px + 'px';
      preview.style.top = py + 'px';
    }

    function showForCard(card) {
      const imgEl = card.querySelector('img.author-image');
      if (!imgEl) return;

      // Use currentSrc when responsive images are present
      const src = imgEl.currentSrc || imgEl.src;
      if (!src) return;

      previewImg.src = src;
      overlay.classList.add('active');
      preview.classList.add('show');
    }

    function hideAll() {
      overlay.classList.remove('active');
      preview.classList.remove('show');
    }

    // Mouse interactions
    document.querySelectorAll('.author-card').forEach(card => {
      card.addEventListener('mouseenter', () => showForCard(card));
      card.addEventListener('mousemove', (e) => {
        if (!preview.classList.contains('show')) showForCard(card);
        positionPreview(e.clientX, e.clientY);
      });
      card.addEventListener('mouseleave', hideAll);
      // Hide on click navigation (instant feedback)
      card.addEventListener('click', hideAll);

      // Touch support: tap to show while finger is down
      card.addEventListener('touchstart', (e) => {
        const t = e.touches[0];
        showForCard(card);
        positionPreview(t.clientX, t.clientY);
      }, { passive: true });

      card.addEventListener('touchmove', (e) => {
        const t = e.touches[0];
        positionPreview(t.clientX, t.clientY);
      }, { passive: true });

      card.addEventListener('touchend', hideAll);
      card.addEventListener('touchcancel', hideAll);
    });

    // Clicking overlay also hides (optional)
    overlay.addEventListener('click', hideAll);
  })();
</script>

@include('include.footer')