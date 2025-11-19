
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
.card-3d__media { position: relative; height: 12rem; } /* ~ h-48 */
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
  background: linear-gradient(to top, rgba(0,0,0,.45), rgba(0,0,0,0) 40%);
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

/* Accessibility */
@media (prefers-reduced-motion: reduce) {
  .card-3d__inner, .card-3d__cover { transition: none !important; }
}
</style>

<div class=" night-sky max-w-7xl mx-auto px-6 py-10">
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
                   class="card-3d block rounded-2xl border border-gray-100 dark:border-slate-800 hover:-translate-y-0.5 transition">
                    <div class="card-3d__inner bg-white dark:bg-slate-900">
                        <div class="card-3d__media">
                            @if($author->profile_image)
                                {{-- storage/... ашиглаж APP_URL зөрчилгүй болгоно --}}
                                <img
                                    src="{{ asset('storage/'.$author->profile_image) }}"
                                    alt="{{ $author->name }}"
                                    loading="lazy"
                                    class="card-3d__cover"
                                    onerror="this.onerror=null;this.src='/images/authors/default.jpg';" />
                            @else
                                <img
                                    src="/images/authors/default.jpg"
                                    alt="No image"
                                    class="card-3d__cover" />
                            @endif

                            <div class="card-3d__shade"></div>
                            <div class="card-3d__shine"></div>

                            <h2 class="card-3d__title text-lg">
                                {{ $author->name }}
                            </h2>
                        </div>

                        <div class="card-3d__content p-4">
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                {{ $author->nationality ?? 'Үндэс тодорхойгүй' }}
                            </p>
                            <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                                {{ Str::limit($author->biography, 100) }}
                            </p>
                        </div>
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

@include('include.footer')