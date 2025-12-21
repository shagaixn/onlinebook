@include('include.header')

<style>
 
/* 3D Card Hover Effect */
.card-3d { perspective: 1000px; }
.card-3d__inner {
  position: relative;
  transform-style: preserve-3d;
  transform: rotateX(var(--rx, 0deg)) rotateY(var(--ry, 0deg));
  border-radius: 1rem;
  overflow: hidden;
  background: var(--card-bg, #0b0b0c);
  transition: transform 120ms ease-out, box-shadow 200ms ease, filter 200ms ease;
  will-change: transform;
}
.card-3d:hover .card-3d__inner {
  box-shadow: 0 18px 55px rgba(0,0,0,.45);
  filter: saturate(1.05) contrast(1.02);
}

/* Media area (cover) */
.card-3d__media { position: relative; height: 14rem; }
.card-3d__cover {
  display: block;
  width: 100%;
  height: 100%;
  object-fit: cover;
  transform: translateZ(20px);
  transition: transform .25s ease;
}
.card-3d:hover .card-3d__cover { transform: translateZ(40px); }

/* Bottom shade for readability */
.card-3d__shade {
  position: absolute;
  inset: 0;
  background: linear-gradient(to top, rgba(0,0,0,.45), rgba(0,0,0,0) 50%);
  transform: translateZ(50px);
  pointer-events: none;
}

/* Shine following cursor */
.card-3d__shine {
  position: absolute;
  inset: 0;
  background: radial-gradient(600px circle at var(--mx,50%) var(--my,50%), rgba(255,255,255,.20), transparent 45%);
  mix-blend-mode: screen;
  opacity: 0;
  transition: opacity .2s ease;
  transform: translateZ(60px);
  pointer-events: none;
}
.card-3d:hover .card-3d__shine { opacity: .85; }

/* Title on image */
.card-3d__title {
  position: absolute;
  bottom: .75rem;
  left: .75rem;
  right: .75rem;
  color: #fff;
  font-weight: 700;
  letter-spacing: .03em;
  text-shadow: 0 2px 12px rgba(0,0,0,.55);
  transform: translateZ(70px);
}

/* Below-image content slightly raised */
.card-3d__content { position: relative; transform: translateZ(40px); }
/* Country badge */
.card-3d__badge {
  position: absolute;
  top: .75rem;
  right: .75rem;
  background: rgba(255,255,255,0.9);
  color: #374151;
  font-size: 0.7rem;
  font-weight: 600;
  padding: 0.25rem 0.5rem;
  border-radius: 9999px;
  transform: translateZ(80px);
}
.dark .card-3d__badge {
  background: rgba(30,41,59,0.9);
  color: #e2e8f0;
}
/* Accessibility */
@media (prefers-reduced-motion: reduce) {
  .card-3d__inner, .card-3d__cover { transition: none !important; }
}
</style>
<main class="night-sky min-h-[100svh]  max-w-9xl mx-auto px-4 py-15">
<div class="max-w-7xl mx-auto px-6 py-10">
    <div class="text-center mb-10">
        <h1 class="text-4xl font-bold mb-3 text-slate-900 dark:text-slate-100">📚 Зохиолчид</h1>
        <p class="text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">Алдарт зохиолчид болон тэдний бүтээлүүдтэй танилцана уу</p>
    </div>

   <form method="GET" action="{{ route('authors.index') }}" class="mb-8 flex justify-center gap-3">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Нэр, үндэс эсвэл намтраар хайх..."
              class="w-full max-w-md px-5 py-3 rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm" />
        <button type="submit" class="px-6 py-3 rounded-xl bg-blue-600 text-white hover:bg-blue-700 transition shadow-sm font-medium">Хайх</button>
    </form>

    @if($authors->count() === 0)
        <div class="p-12 text-center bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800">
            <svg class="mx-auto h-16 w-16 text-gray-400 dark:text-slate-500 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <p class="text-gray-500 dark:text-gray-400 text-lg">Зохиолч олдсонгүй.</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($authors as $author)
                <a href="{{ route('authors.show', $author->slug) }}"
                   class="card-3d block rounded-2xl border border-gray-100 dark:border-slate-800 hover:-translate-y-0.5 transition">
                    <div class="card-3d__inner bg-white dark:bg-slate-900">
                        <div class="card-3d__media">
                            @if($author->profile_image)
                              
                              <div class="card-3d__cover bg-gradient-to-br from-gray-200 to-gray-300 dark:from-slate-700 dark:to-slate-800 flex items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-400 dark:text-slate-500" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                    </svg>
                                </div>
                            @else
                                <img
                                    src="/images/authors/default.jpg"
                                    alt="No image"
                                    class="card-3d__cover" />
                            @endif

                            <div class="card-3d__shade"></div>
                            <div class="card-3d__shine"></div>

                                @if($author->country)
                                <span class="card-3d__badge">🌍 {{ $author->country }}</span>
                            @endif
                            <div class="card-3d__title">
                                <h2 class="text-lg">{{ $author->name }}</h2>
                                @if($author->position)
                                    <p class="text-xs font-normal text-blue-200 mt-0.5">{{ $author->position }}</p>
                                @endif
                            </div>
                        </div>

                        <div class="card-3d__content p-4">
                                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                                <span>{{ $author->nationality ?? 'Үндэс тодорхойгүй' }}</span>
                                @if($author->birth_date)
                                    <span class="text-gray-400 dark:text-gray-500">·</span>
                                    <span>{{ $author->birth_date->format('Y') }}@if($author->death_date)-{{ $author->death_date->format('Y') }}@endif</span>
                                @endif
                            </div>
                            <p class="text-sm text-gray-700 dark:text-gray-300 line-clamp-2 mb-3">
                              {{ \Illuminate\Support\Str::limit($author->biography, 80) }}
                            </p>
                                 {{-- Бүтээл тоо --}}
                              @if($author->notable_works_count > 0)
                                <div class="flex items-center gap-1 text-xs text-gray-500 dark:text-gray-400">
                                    <span>📖</span>
                                       <span>{{ $author->notable_works_count }} бүтээл</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-10">
            {{ $authors->links() }}
        </div>
    @endif
</div>

<script>
/* Cursor-based 3D tilt + glow */
(() => {
  const supportsHover = window.matchMedia("(hover: hover) and (pointer: fine)").matches;

  document.querySelectorAll(".card-3d").forEach((card) => {
    const inner = card.querySelector(".card-3d__inner");
    if (!inner) return;

    const maxTilt = 12; // degrees

    function onMove(e) {
      const rect = card.getBoundingClientRect();
      const x = (e.clientX - rect.left) / rect.width;  // 0..1
      const y = (e.clientY - rect.top) / rect.height;  // 0..1

      const ry = (x - 0.5) * (maxTilt * 2);  // left/right
      const rx = -(y - 0.5) * (maxTilt * 2); // up/down

      inner.style.setProperty("--ry", ry.toFixed(2) + "deg");
      inner.style.setProperty("--rx", rx.toFixed(2) + "deg");

      // shine position
      inner.style.setProperty("--mx", (x * 100).toFixed(1) + "%");
      inner.style.setProperty("--my", (y * 100).toFixed(1) + "%");
    }

    function reset() {
      inner.style.setProperty("--ry", "0deg");
      inner.style.setProperty("--rx", "0deg");
    }

    if (supportsHover) {
      card.addEventListener("mousemove", onMove);
      card.addEventListener("mouseleave", reset);
    }
  });
})();
</script>
</main>
@include('include.footer')