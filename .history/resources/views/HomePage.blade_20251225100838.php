@include('include.header')

{{-- Using shared night sky styles from night-sky.css --}}
<style>
  /* Prefers-reduced-motion: animations off for accessibility */
  @media (prefers-reduced-motion: reduce) {
    .animate-spin-slow, .float-slow, .animate-marquee { animation: none !important; }
  }
</style>

@php
  // Controller-оос ирээгүй бол хоосон массив болгоно
  $wishlistIds = $wishlistIds ?? [];
@endphp

<main class="night-sky min-h-[100svh] max-w-9xl mx-auto px-4 py-15">
  <!-- Hero -->
  <section class="max-w-5xl mx-auto px-6 pt-16 pb-10 text-center" aria-labelledby="hero-heading">
    <h1 id="hero-heading" class="text-4xl sm:text-5xl font-extrabold leading-tight tracking-tight text-white">
      <span class="text-gradient drop-shadow-sm">#Мэдрэмж</span>, <span class="sr-only">Мэдрэмж,</span>Мэдлэгийг өнгөлнө.
    </h1>
    <p class="mt-4 text-slate-100/80 max-w-2xl mx-auto">
      Хил хязгаар, цаг хугацааг үл харгалзан бүгдэд нээлттэйгээр мэдрэмж, мэдлэгийг түгээх
      <strong class="font-semibold text-white">Mbook</strong>-т тавтай морилно уу!
    </p>
    <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3" role="group" aria-label="Үйлдлүүд">
      <a href="{{ route('subscription') }}"
         class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-blue-500 to-indigo-600 px-8 py-3 text-white font-semibold shadow-lg shadow-indigo-700/30 hover:from-blue-600 hover:to-indigo-700 transition focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-indigo-500"
         aria-label="Subscription төлөвлөгөөг үзэх">
        Subscription
        <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
          <path d="M5 12h14M13 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </a>
      <a href="{{ route('book') }}"
         class="inline-flex items-center gap-2 rounded-full px-6 py-3 text-slate-100 ring-1 ring-white/20 bg-white/10 backdrop-blur-md hover:bg-white/20 transition focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-cyan-500"
         aria-label="Номуудын жагсаалтыг үзэх">
        Номуудыг үзэх
      </a>
    </div>
  </section>

  @php
    $circleBooks = collect($newBooks ?? [])->take(3);
  @endphp
  <div class="relative mx-auto mt-6 w-full max-w-xl h-72 sm:h-80">
    <div class="absolute inset-0 flex items-center justify-center">
      <div class="relative">
        <div class="absolute inset-0 rounded-full border border-blue-300/25 w-48 h-48 animate-spin-slow"></div>
        <div class="absolute inset-0 rounded-full border border-blue-300/15 w-72 h-72 animate-spin-slow" style="animation-duration:55s;"></div>
      </div>
    </div>

    @if($circleBooks->count())
      @foreach($circleBooks as $i => $book)
        @php
          $pos = [
            ['top' => 'top-4', 'left' => 'left-6'],
            ['top' => 'top-8', 'right' => 'right-6'],
            ['bottom' => 'bottom-6', 'left' => 'left-1/2 -translate-x-1/2'],
          ][$i];
        @endphp
        <img
          src="{{ $book->cover_image ? asset('storage/' . $book->cover_image) : asset('images/placeholder-book.png') }}"
          alt="{{ $book->title }} номын хавтас"
          loading="lazy" decoding="async"
          class="absolute {{ $pos['top'] ?? '' }} {{ $pos['bottom'] ?? '' }} {{ $pos['left'] ?? '' }} {{ $pos['right'] ?? '' }}
                 cover-size rounded-xl object-cover shadow-lg shadow-black/50 ring-1 ring-white/10 float-slow"
          style="animation-delay: {{ $i * 0.8 }}s"
          onerror="this.onerror=null;this.src='{{ asset('images/placeholder-book.png') }}';"
        />
      @endforeach
    @else
      <img src="{{ asset('images/placeholder-book.png') }}" alt="Placeholder ном 1" loading="lazy" decoding="async" class="absolute top-4 left-6 cover-size rounded-xl object-cover shadow-lg ring-1 ring-white/10 float-slow" />
      <img src="{{ asset('images/placeholder-book.png') }}" alt="Placeholder ном 2" loading="lazy" decoding="async" class="absolute top-8 right-6 cover-size rounded-xl object-cover shadow-lg ring-1 ring-white/10 float-slow" style="animation-delay:.6s" />
      <img src="{{ asset('images/placeholder-book.png') }}" alt="Placeholder ном 3" loading="lazy" decoding="async" class="absolute bottom-6 left-1/2 -translate-x-1/2 cover-size rounded-xl object-cover shadow-lg ring-1 ring-white/10 float-slow" style="animation-delay:1.2s" />
    @endif

    <p class="absolute -bottom-8 left-1/2 -translate-x-1/2 mt-6 text-slate-300 flex items-center gap-2 animate-bounce">
      <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8">
        <path d="M12 5v10M8 11l4 4 4-4" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
      Scroll
    </p>
  </div>

  <!-- Features -->
  <section class="max-w-7xl mx-auto px-6 mt-24 grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
    <div class="group p-6 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-sm hover:bg-white/10 transition duration-300">
      <div class="w-14 h-14 mx-auto mb-4 rounded-2xl bg-blue-500/20 flex items-center justify-center text-blue-400 group-hover:scale-110 transition duration-300">
        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
      </div>
      <h3 class="text-lg font-semibold text-white mb-2">Өргөн сонголт</h3>
      <p class="text-slate-400 text-sm leading-relaxed">Мянга мянган ном, зохиолуудаас өөрийн хүссэнээ сонгон унших боломж.</p>
    </div>
    <div class="group p-6 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-sm hover:bg-white/10 transition duration-300">
      <div class="w-14 h-14 mx-auto mb-4 rounded-2xl bg-purple-500/20 flex items-center justify-center text-purple-400 group-hover:scale-110 transition duration-300">
        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
      </div>
      <h3 class="text-lg font-semibold text-white mb-2">Хэзээ ч, хаана ч</h3>
      <p class="text-slate-400 text-sm leading-relaxed">Гар утас, таблет, компьютерээсээ хүссэн үедээ хандах боломжтой.</p>
    </div>
    <div class="group p-6 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-sm hover:bg-white/10 transition duration-300">
      <div class="w-14 h-14 mx-auto mb-4 rounded-2xl bg-cyan-500/20 flex items-center justify-center text-cyan-400 group-hover:scale-110 transition duration-300">
        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
      </div>
      <h3 class="text-lg font-semibold text-white mb-2">Шуурхай шинэчлэл</h3>
      <p class="text-slate-400 text-sm leading-relaxed">Шинэ ном, зохиолууд тогтмол нэмэгдэж, таны мэдлэгийн санг баяжуулна.</p>
    </div>
  </section>

  <!-- New Books -->
  <section class="w-full max-w-6xl mx-auto px-6 mt-24">
    <div class="flex items-end justify-between mb-6">
      <h2 class="text-2xl sm:text-3xl font-bold text-white">Шинээр нэмэгдсэн номнууд</h2>
      @if(!empty($newBooks) && count($newBooks) > 0)
        <a href="{{ route('book') }}" class="text-cyan-300 hover:underline text-sm">Бүгдийг харах</a>
      @endif
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
      @forelse($newBooks ?? [] as $book)
        <article class="group rounded-2xl bg-white/5 backdrop-blur border border-white/10 overflow-hidden shadow-sm hover:shadow-lg hover:border-cyan-400/40 transition">
          <a href="{{ route('books.show', $book->id) }}" class="block focus:outline-none focus-visible:ring-2 focus-visible:ring-cyan-500/70 rounded-2xl" aria-label="{{ $book->title }} номын дэлгэрэнгүй">
            @if($book->cover_image)
              <div class="aspect-[3/4] overflow-hidden">
                <img
                  src="{{ asset('storage/' . $book->cover_image) }}"
                  alt="{{ $book->title }} номын хавтас"
                  loading="lazy" decoding="async"
                  width="600" height="800"
                  sizes="(min-width:1024px) 33vw, (min-width:640px) 50vw, 100vw"
                  onerror="this.onerror=null;this.src='{{ asset('images/placeholder-book.png') }}';"
                  class="h-full w-full object-cover transition duration-300 group-hover:scale-105" />
              </div>
            @else
              <div class="aspect-[3/4] overflow-hidden">
                <img
                  src="{{ asset('images/placeholder-book.png') }}"
                  alt="{{ $book->title }} номын хавтас (placeholder)"
                  loading="lazy" decoding="async"
                  width="600" height="800"
                  sizes="(min-width:1024px) 33vw, (min-width:640px) 50vw, 100vw"
                  class="h-full w-full object-cover transition duration-300 group-hover:scale-105" />
              </div>
            @endif
            <div class="p-4">
              <h3 class="text-base font-semibold text-white">
                {{ $book->title }}
              </h3>
              <p class="mt-1 text-sm text-slate-300">
                Зохиолч: {{ $book->author->name ?? '-' }}
              </p>
              <div class="mt-3 flex items-center justify-between">
                <div class="inline-flex items-center gap-1 text-cyan-300 text-sm">
                  Дэлгэрэнгүй
                  <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M5 12h14M13 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg>
                </div>
                <button
                  type="button"
                  class="wishlist-btn px-3 py-2 rounded-full text-sm font-medium transition
                    {{ in_array($book->id, $wishlistIds) ? 'bg-pink-600/80 text-white' : 'bg-white/10 text-pink-300 hover:bg-white/20' }}"
                  data-book-id="{{ $book->id }}"
                  aria-label="Wishlist-д нэмэх"
                  aria-pressed="{{ in_array($book->id, $wishlistIds) ? 'true' : 'false' }}">
                  <svg class="w-5 h-5" viewBox="0 0 24 24" fill="{{ in_array($book->id, $wishlistIds) ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="1.6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M12 21s-6.5-4.35-9.2-8.34A5.5 5.5 0 0112 5.52a5.5 5.5 0 019.2 7.14C18.5 16.65 12 21 12 21Z" />
                  </svg>
                </button>
              </div>
            </div>
          </a>
        </article>
      @empty
        <div class="col-span-1 sm:col-span-2 md:col-span-3 text-center text-slate-300">
          Шинэ ном алга байна.
        </div>
      @endforelse
    </div>
  </section>

  <!-- New Authors -->
  <section class="w-full max-w-6xl mx-auto px-6 mt-20">
    <div class="flex items-end justify-between mb-6">
      <h2 class="text-2xl sm:text-3xl font-bold text-white">Шинээр нэмэгдсэн зохиолчид</h2>
      @if(!empty($newAuthors) && count($newAuthors) > 0)
        <a href="{{ route('authors.index') }}" class="text-indigo-300 hover:underline text-sm">Бүгдийг харах</a>
      @endif
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
      @forelse($newAuthors ?? [] as $author)
        <article class="group rounded-2xl bg-white/5 backdrop-blur border border-white/10 overflow-hidden shadow-sm hover:shadow-lg hover:border-indigo-400/40 transition">
          <a href="{{ route('authors.show', $author->slug) }}" class="block focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500/60 rounded-2xl" aria-label="{{ $author->name }} зохиолчийн дэлгэрэнгүй">
            @if($author->profile_image)
              <div class="aspect-[3/2] overflow-hidden">
                <img
                  src="{{ asset('storage/' . $author->profile_image) }}"
                  alt="{{ $author->name }} зохиолчийн зураг"
                  loading="lazy" decoding="async"
                  width="600" height="400"
                  sizes="(min-width:1024px) 33vw, (min-width:640px) 50vw, 100vw"
                  onerror="this.onerror=null;this.src='{{ asset('images/placeholder-author.png') }}';"
                  class="h-full w-full object-cover transition duration-300 group-hover:scale-105" />
              </div>
            @else
              <div class="aspect-[3/2] overflow-hidden">
                <img
                  src="{{ asset('images/placeholder-author.png') }}"
                  alt="{{ $author->name }} зохиолчийн зураг (placeholder)"
                  loading="lazy" decoding="async"
                  width="600" height="400"
                  sizes="(min-width:1024px) 33vw, (min-width:640px) 50vw, 100vw"
                  class="h-full w-full object-cover transition duration-300 group-hover:scale-105" />
              </div>
            @endif
            <div class="p-4">
              <h3 class="text-base font-semibold text-white">
                {{ $author->name }}
              </h3>
              <p class="mt-1 text-sm text-slate-300">
                {{ $author->nationality ?? '' }}
              </p>
              <div class="mt-3 inline-flex items-center gap-1 text-indigo-300 text-sm">
                Дэлгэрэнгүй
                <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8">
                  <path d="M5 12h14M13 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
              </div>
            </div>
          </a>
        </article>
      @empty
        <div class="col-span-1 sm:col-span-2 md:col-span-3 text-center text-slate-300">
          Шинэ зохиолч алга байна.
        </div>
      @endforelse
    </div>
  </section>

  @php
    // Build marquee items from wishlist first; then fallback to new books; else static
    $wishlistBooks = $wishlistBooks ?? null;

    if (!$wishlistBooks && !empty($wishlistIds)) {
      try {
        // Зөвхөн title ачааллана
        $wishlistBooks = \App\Models\Book::whereIn('id', $wishlistIds)->select('title')->get();
      } catch (\Throwable $e) {
        $wishlistBooks = collect();
      }
    }

    $marqueeItems = collect($wishlistBooks ?? [])
      ->pluck('title');

    if ($marqueeItems->isEmpty()) {
      $marqueeItems = collect($newBooks ?? [])->pluck('title');
    }

    $marqueeItems = $marqueeItems
      ->map(fn ($t) => '📚 ' . $t)
      ->take(8)
      ->values()
      ->toArray();

    if (empty($marqueeItems)) {
      $marqueeItems = ['📚 Book 1','📕 Book 2','📖 Book 3','📙 Book 4','📘 Book 5'];
    }
  @endphp

  <!-- Marquee -->
  <section id="testimonials" class="mt-24">
    <div class="relative w-full overflow-hidden bg-[#132540]">
      <div class="marquee-container">
        <div class="animate-marquee whitespace-nowrap py-6 px-2" aria-label="Wishlist дахь номуудыг гүйлгэн үзүүлж байна">
          @foreach($marqueeItems as $label)
            <span class="mx-8 text-xl sm:text-2xl text-white/90 font-semibold">{{ $label }}</span>
          @endforeach
          @foreach($marqueeItems as $label)
            <span class="mx-8 text-xl sm:text-2xl text-white/90 font-semibold" aria-hidden="true">{{ $label }}</span>
          @endforeach
        </div>
      </div>
      <div aria-hidden="true" class="pointer-events-none absolute inset-y-0 left-0 w-16 bg-gradient-to-r from-[#132540] to-transparent"></div>
      <div aria-hidden="true" class="pointer-events-none absolute inset-y-0 right-0 w-16 bg-gradient-to-l from-[#132540] to-transparent"></div>
    </div>
  </section>
</main>
<script>
  (function () {
    const token = '{{ csrf_token() }}';
    document.querySelectorAll('.wishlist-btn').forEach(btn => {
      btn.addEventListener('click', async () => {
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
          btn.classList.toggle('bg-pink-600/80', active);
          btn.classList.toggle('text-white', active);
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
@include('include.footer')

<!-- Optional: Icon kit (remove if unused) -->
<!-- <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script> -->