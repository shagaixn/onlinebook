@include('include.header')

<style>
@media (prefers-reduced-motion: reduce) {
  .animate-spin-slow,
  .float-slow,
  .animate-marquee {
    animation: none !important;
  }
}

/* Hover pause marquee */
.marquee-container:hover .animate-marquee {
  animation-play-state: paused;
}
</style>

@php
  $wishlistIds = $wishlistIds ?? [];
@endphp

<main class="night-sky min-h-[100svh] max-w-9xl mx-auto px-4 py-16 animate-fade-in">

  {{-- ================= HERO ================= --}}
  <section class="relative max-w-5xl mx-auto px-6 pt-24 pb-16 text-center z-10">

    {{-- Gradient spotlight --}}
    <div class="absolute inset-0 -z-10 bg-[radial-gradient(circle_at_top,rgba(56,189,248,0.25),transparent_55%)]"></div>

    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full
                bg-white/10 border border-white/20 backdrop-blur
                text-xs font-medium text-cyan-300 mb-6">
      <span class="relative flex h-2 w-2">
        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-cyan-400 opacity-75"></span>
        <span class="relative inline-flex rounded-full h-2 w-2 bg-cyan-500"></span>
      </span>
      Шинэ номууд нэмэгдлээ
    </div>

    <h1 class="text-5xl sm:text-6xl md:text-7xl font-black tracking-tight
               bg-gradient-to-br from-white via-cyan-200 to-blue-400
               bg-clip-text text-transparent
               drop-shadow-[0_10px_40px_rgba(0,0,0,0.6)]">
      #Мэдрэмж,<br class="hidden sm:block"> Мэдлэгийг өнгөлнө.
    </h1>

    <p class="mt-6 text-lg text-slate-300 max-w-2xl mx-auto">
      Хил хязгаар, цаг хугацааг үл харгалзан мэдлэгийг түгээх
      <span class="font-semibold text-white">Book Plus</span>
    </p>

    <div class="mt-10 flex flex-col sm:flex-row gap-4 justify-center">
      <a href="{{ route('subscription') }}"
         class="px-8 py-4 rounded-full bg-white text-slate-900 font-bold
                hover:scale-105 transition shadow-lg">
        Subscription →
      </a>

      <a href="{{ route('book') }}"
         class="px-8 py-4 rounded-full bg-white/10 text-white
                border border-white/30 backdrop-blur
                hover:bg-white/20 transition">
        Номуудыг үзэх
      </a>
    </div>
  </section>

  {{-- ================= NEW BOOKS ================= --}}
  <section class="max-w-6xl mx-auto px-6 mt-24">

    <div class="flex justify-between items-end mb-6">
      <h2 class="text-3xl font-bold text-white">Шинээр нэмэгдсэн номууд</h2>
      <a href="{{ route('book') }}" class="text-cyan-300 hover:underline text-sm">
        Бүгдийг харах
      </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">

      @forelse($newBooks ?? [] as $book)
        @php $inWishlist = in_array($book->id, $wishlistIds); @endphp

        <article class="group relative rounded-2xl
                        bg-white/5 backdrop-blur
                        border border-white/10 overflow-hidden
                        transition-all duration-300
                        hover:-translate-y-1
                        hover:border-cyan-400/30
                        hover:shadow-[0_20px_60px_-20px_rgba(34,211,238,0.5)]">

          <a href="{{ route('books.show', $book->id) }}" class="block">

            <div class="relative aspect-[3/4] overflow-hidden">
              <img
                src="{{ $book->cover_image
                  ? asset('storage/'.$book->cover_image)
                  : asset('images/placeholder-book.png') }}"
                alt="{{ $book->title }}"
                class="h-full w-full object-cover transition duration-500 group-hover:scale-110"
              >
              <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-transparent to-transparent"></div>
            </div>

            <div class="p-5">
              <h3 class="text-lg font-bold text-white group-hover:text-cyan-300">
                {{ $book->title }}
              </h3>
              <p class="text-sm text-slate-400">
                {{ $book->author->name ?? 'Тодорхойгүй' }}
              </p>

              <div class="mt-4 flex justify-between items-center border-t border-white/10 pt-3">
                <span class="text-xs px-2 py-1 rounded bg-cyan-500/10 text-cyan-300">
                  Шинэ
                </span>
                <span class="text-sm text-slate-300 group-hover:translate-x-1 transition">
                  Үзэх →
                </span>
              </div>
            </div>
          </a>

          {{-- Wishlist --}}
          <button
            type="button"
            data-book-id="{{ $book->id }}"
            class="wishlist-btn absolute top-3 right-3 p-2.5 rounded-full
                   border backdrop-blur transition
                   {{ $inWishlist
                      ? 'bg-pink-600 text-white border-pink-500 shadow-lg shadow-pink-600/30'
                      : 'bg-black/40 text-white border-white/20 hover:bg-pink-600'
                   }}">
            ❤️
          </button>

        </article>
      @empty
        <p class="col-span-3 text-center text-slate-400">
          Шинэ ном алга байна
        </p>
      @endforelse
    </div>
  </section>

  {{-- ================= MARQUEE ================= --}}
  <section class="mt-24">
    <div class="relative overflow-hidden bg-white/5 border-y border-white/10">
      <div class="marquee-container">
        <div class="animate-marquee whitespace-nowrap py-6">
          @foreach($newBooks ?? [] as $book)
            <span class="mx-8 text-2xl text-white hover:text-cyan-300 transition">
              📚 {{ $book->title }}
            </span>
          @endforeach
        </div>
      </div>
    </div>
  </section>

</main>

{{-- ================= WISHLIST JS ================= --}}
<script>
(function () {
  const token = '{{ csrf_token() }}';

  document.querySelectorAll('.wishlist-btn').forEach(btn => {
    btn.addEventListener('click', async e => {
      e.preventDefault();
      e.stopPropagation();

      const id = btn.dataset.bookId;
      btn.disabled = true;

      try {
        const res = await fetch("{{ route('wishlist.toggle') }}", {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': token,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
          },
          body: JSON.stringify({ book_id: id })
        });

        const data = await res.json();
        btn.classList.toggle('bg-pink-600', data.in_wishlist);
      } catch (e) {
        console.error(e);
      } finally {
        btn.disabled = false;
      }
    });
  });
})();
</script>

@include('include.footer')
