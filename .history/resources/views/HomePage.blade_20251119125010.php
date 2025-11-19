@include('include.header')

{{-- Removed section wrapper; rendering content directly between header and footer --}}
<style>
  /* Hero gradient text helper */
  .text-gradient {
    background: linear-gradient(90deg, #60a5fa 0%, #6366f1 50%, #22d3ee 100%);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
  }

    /* Page background with moving star fields */
  .page-bg {
    background: #0b1120;
    overflow: hidden;
    position: relative;
    z-index: 0; /* stacking context so star layers (z:-1) sit behind */
  }

  /* layer 1 */
  .page-bg::before {
    content: "";
    position: absolute;
    inset: 0;
    background-image: repeating-radial-gradient(circle, white 0 1px, transparent 1px 18px);
    animation: moveStars1 80s linear infinite;
    opacity: .30;
    pointer-events: none;
    z-index: -1;
  }

  @keyframes moveStars1 {
    from { transform: translateY(0); }
    to { transform: translateY(600px); }
  }

  /* layer 2 */
  .page-bg::after {
    content: "";
    position: absolute;
    inset: 0;
    background-image: repeating-radial-gradient(circle, white 0 1px, transparent 1px 22px);
    animation: moveStars2 140s linear infinite;
    opacity: .18;
    pointer-events: none;
    z-index: -1;
  }

  @keyframes moveStars2 {
    from { transform: translateY(0); }
    to { transform: translateY(800px); }
  }



  /* Marquee */
  @keyframes marquee {
    0% { transform: translateX(0%); }
    100% { transform: translateX(-50%); }
  }
  .marquee-container {
    mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent);
    -webkit-mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent);
  }
  .animate-marquee {
    display: flex;
    width: max-content;
    animation: marquee 22s linear infinite;
    will-change: transform;
  }
  .animate-marquee:hover { animation-play-state: paused; }
  @media (prefers-reduced-motion: reduce) {
    .animate-marquee { animation: none; }
  }

  /* Subtle float for decorative covers */
  @keyframes float {
    0%, 100% { transform: translateY(0) }
    50% { transform: translateY(-10px) }
  }
  .float-slow { animation: float 6s ease-in-out infinite; }

  /* Responsive size for floating cover images */
  :root { --home-cover-size: clamp(4.5rem, 9vw, 6.5rem); }
  .cover-size { width: var(--home-cover-size); height: var(--home-cover-size); }

  /* Slow spin for rings (uses Tailwind rotate utilities too) */
  @keyframes spin-slow {
    to { transform: rotate(360deg); }
  }
  .animate-spin-slow { animation: spin-slow 40s linear infinite; }
</style>
@push('page-scripts')
  @vite('resources/js/home.js')
@endpush

<main class="page-bg relative min-h-[60vh] overflow-hidden">
  <!-- Background decoration -->
  <div aria-hidden="true" class="pointer-events-none absolute inset-0 -z-10">
    <div class="absolute -top-24 -left-24 h-72 w-72 rounded-full bg-blue-500/10 blur-3xl"></div>
    <div class="absolute -bottom-24 -right-24 h-72 w-72 rounded-full bg-indigo-500/10 blur-3xl"></div>
  </div>

  <!-- Hero -->
    <section class="max-w-5xl mx-auto px-6 pt-16 pb-10 text-center" aria-labelledby="hero-heading">
      <h1 id="hero-heading" class="text-4xl sm:text-5xl font-extrabold leading-tight tracking-tight text-slate-900 dark:text-white">
        <span class="text-gradient drop-shadow-sm">#Мэдрэмж</span>, <span class="sr-only">Мэдрэмж,</span>Мэдлэгийг өнгөлнө.
      </h1>
      <p class="mt-4 text-slate-600 dark:text-slate-300 max-w-2xl mx-auto">
        Хил хязгаар, цаг хугацааг үл харгалзан бүгдэд нээлттэйгээр мэдрэмж, мэдлэгийг түгээх <strong class="font-semibold">Mbook</strong>-т тавтай морилно уу!
      </p>
      <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3" role="group" aria-label="Үйлдлүүд">
        <a href="{{ route('subscription') }}"
           class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-blue-500 to-indigo-600 px-8 py-3 text-white font-semibold shadow-lg shadow-blue-500/20 hover:from-blue-600 hover:to-indigo-700 transition focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-indigo-500"
           aria-label="Subscription төлөвлөгөөг үзэх">
          Subscription
          <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M5 12h14M13 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </a>
        <a href="{{ route('book') }}"
           class="inline-flex items-center gap-2 rounded-full px-6 py-3 text-slate-700 dark:text-slate-100 ring-1 ring-slate-200/70 dark:ring-slate-700/70 bg-white/80 dark:bg-slate-900/60 backdrop-blur hover:bg-slate-50 dark:hover:bg-slate-800 transition focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-blue-500"
           aria-label="Номуудын жагсаалтыг үзэх">
          Номуудыг үзэх
        </a>
      </div>
    </section>

  <!-- Decorative rings + floating covers -->
  @php
    $circleBooks = collect($newBooks ?? [])->take(3);
  @endphp
  <div class="relative mx-auto mt-6 w-full max-w-xl h-72 sm:h-80">
    <div class="absolute inset-0 flex items-center justify-center">
      <div class="relative">
        <div class="absolute inset-0 rounded-full border border-blue-200 dark:border-slate-800 w-48 h-48 animate-spin-slow"></div>
        <div class="absolute inset-0 rounded-full border border-blue-200/60 dark:border-slate-700/80 w-72 h-72 animate-spin-slow" style="animation-duration: 55s;"></div>
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
                 cover-size rounded-xl object-cover shadow-lg ring-1 ring-black/5 float-slow"
          style="animation-delay: {{ $i * 0.8 }}s"
          onerror="this.onerror=null;this.src='{{ asset('images/placeholder-book.png') }}';"
        />
      @endforeach
    @else
  <img src="{{ asset('images/placeholder-book.png') }}" alt="Placeholder ном 1" loading="lazy" decoding="async" class="absolute top-4 left-6 cover-size rounded-xl object-cover shadow-lg ring-1 ring-black/5 float-slow" />
  <img src="{{ asset('images/placeholder-book.png') }}" alt="Placeholder ном 2" loading="lazy" decoding="async" class="absolute top-8 right-6 cover-size rounded-xl object-cover shadow-lg ring-1 ring-black/5 float-slow" style="animation-delay:.6s" />
  <img src="{{ asset('images/placeholder-book.png') }}" alt="Placeholder ном 3" loading="lazy" decoding="async" class="absolute bottom-6 left-1/2 -translate-x-1/2 cover-size rounded-xl object-cover shadow-lg ring-1 ring-black/5 float-slow" style="animation-delay:1.2s" />
    @endif

    <p class="absolute -bottom-8 left-1/2 -translate-x-1/2 mt-6 text-slate-500 dark:text-slate-400 flex items-center gap-2">
      <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 5v10M8 11l4 4 4-4" stroke-linecap="round" stroke-linejoin="round"/></svg>
      Scroll
    </p>
  </div>

  <!-- New Books -->
  <section class="w-full max-w-6xl mx-auto px-6 mt-24">
    <div class="flex items-end justify-between mb-6">
  <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white">Шинээр нэмэгдсэн номнууд</h2>
      @if(!empty($newBooks) && count($newBooks) > 0)
        <a href="{{ route('book') }}" class="text-blue-600 dark:text-blue-400 hover:underline text-sm">Бүгдийг харах</a>
      @endif
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
      @forelse($newBooks ?? [] as $book)
  <article class="group rounded-2xl bg-white/80 dark:bg-slate-900/60 backdrop-blur border border-slate-200/70 dark:border-slate-800 overflow-hidden shadow-sm hover:shadow-lg transition will-change-transform">
          <a href="{{ route('books.show', $book->id) }}" class="block focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500/60 rounded-2xl">
            @if($book->cover_image)
              <div class="aspect-[3/4] overflow-hidden">
                <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }} номын хавтас" loading="lazy" decoding="async" class="h-full w-full object-cover transition duration-300 group-hover:scale-105" />
              </div>
            @endif
            <div class="p-4">
              <h3 class="text-base font-semibold text-slate-900 dark:text-white">
                {{ $book->title }}
              </h3>
              <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">
                Зохиолч: {{ $book->author->name ?? '-' }}
              </p>
              <div class="mt-3 inline-flex items-center gap-1 text-blue-600 dark:text-blue-400 text-sm">
                Дэлгэрэнгүй
                <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M5 12h14M13 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
              </div>
            </div>
          </a>
        </article>
      @empty
        <div class="col-span-1 sm:col-span-2 md:col-span-3 text-center text-slate-500 dark:text-slate-400">
          Шинэ ном алга байна.
        </div>
      @endforelse
    </div>
  </section>

  <!-- New Authors -->
  <section class="w-full max-w-6xl mx-auto px-6 mt-20">
    <div class="flex items-end justify-between mb-6">
  <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white">Шинээр нэмэгдсэн зохиолчид</h2>
      @if(!empty($newAuthors) && count($newAuthors) > 0)
        <a href="{{ route('authors.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline text-sm">Бүгдийг харах</a>
      @endif
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
      @forelse($newAuthors ?? [] as $author)
  <article class="group rounded-2xl bg-white/80 dark:bg-slate-900/60 backdrop-blur border border-slate-200/70 dark:border-slate-800 overflow-hidden shadow-sm hover:shadow-lg transition will-change-transform">
          <a href="{{ route('authors.show', $author->slug) }}" class="block focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500/60 rounded-2xl">
            @if($author->profile_image)
              <div class="aspect-[3/2] overflow-hidden">
                <img src="{{ asset('storage/' . $author->profile_image) }}" alt="{{ $author->name }} зохиолчийн зураг" loading="lazy" decoding="async" class="h-full w-full object-cover transition duration-300 group-hover:scale-105" />
              </div>
            @endif
            <div class="p-4">
              <h3 class="text-base font-semibold text-slate-900 dark:text-white">
                {{ $author->name }}
              </h3>
              <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">
                {{ $author->nationality ?? '' }}
              </p>
              <div class="mt-3 inline-flex items-center gap-1 text-blue-600 dark:text-blue-400 text-sm">
                Дэлгэрэнгүй
                <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M5 12h14M13 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
              </div>
            </div>
          </a>
        </article>
      @empty
        <div class="col-span-1 sm:col-span-2 md:col-span-3 text-center text-slate-500 dark:text-slate-400">
          Шинэ зохиолч алга байна.
        </div>
      @endforelse
    </div>
  </section>

  <!-- Infinite marquee strip -->
  <section id="testimonials" class="mt-24">
    <div class="relative w-full overflow-hidden bg-slate-900">
      <div class="marquee-container">
        <div class="animate-marquee whitespace-nowrap py-6 px-2">
          <span class="mx-8 text-xl sm:text-2xl text-white/90 font-semibold">📚 Book 1</span>
          <span class="mx-8 text-xl sm:text-2xl text-white/90 font-semibold">📕 Book 2</span>
          <span class="mx-8 text-xl sm:text-2xl text-white/90 font-semibold">📖 Book 3</span>
          <span class="mx-8 text-xl sm:text-2xl text-white/90 font-semibold">📙 Book 4</span>
          <span class="mx-8 text-xl sm:text-2xl text-white/90 font-semibold">📘 Book 5</span>
          <!-- duplicate for seamless loop -->
          <span class="mx-8 text-xl sm:text-2xl text-white/90 font-semibold">📚 Book 1</span>
          <span class="mx-8 text-xl sm:text-2xl text-white/90 font-semibold">📕 Book 2</span>
          <span class="mx-8 text-xl sm:text-2xl text-white/90 font-semibold">📖 Book 3</span>
          <span class="mx-8 text-xl sm:text-2xl text-white/90 font-semibold">📙 Book 4</span>
          <span class="mx-8 text-xl sm:text-2xl text-white/90 font-semibold">📘 Book 5</span>
        </div>
      </div>

      <!-- soft edges if mask not supported -->
      <div aria-hidden="true" class="pointer-events-none absolute inset-y-0 left-0 w-16 bg-gradient-to-r from-slate-900 to-transparent"></div>
      <div aria-hidden="true" class="pointer-events-none absolute inset-y-0 right-0 w-16 bg-gradient-to-l from-slate-900 to-transparent"></div>
    </div>
  </section>

</main>

@include('include.footer')

<!-- Optional: Icon kit (remove if unused) -->
<!-- <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script> -->