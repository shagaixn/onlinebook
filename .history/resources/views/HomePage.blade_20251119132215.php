@include('include.header')

<style>
  /* Night gradient (dark top -> violet bottom) */
  .page-bg-simple {
    position: relative;
    min-height: 75vh;
    overflow: hidden;
    background:
      linear-gradient(to bottom,
        #17306d 0%,
        #1f3f92 35%,
        #4855b5 55%,
        #7d6fc5 70%,
        #a691df 85%);
    color: #f8fafc;
  }

  /* Stars layer (static) */
  .page-bg-simple::before {
    content: "";
    position: absolute;
    inset: 0;
    pointer-events: none;
    background:
      /* олон жижиг radial "од" */
      radial-gradient(1px 1px at 10% 15%, #ffffff 80%, transparent),
      radial-gradient(1px 1px at 22% 40%, #ffffff 80%, transparent),
      radial-gradient(1px 1px at 30% 18%, #ffffff 80%, transparent),
      radial-gradient(1px 1px at 42% 28%, #ffffff 80%, transparent),
      radial-gradient(1px 1px at 55% 12%, #ffffff 80%, transparent),
      radial-gradient(1px 1px at 65% 24%, #ffffff 80%, transparent),
      radial-gradient(1px 1px at 72% 10%, #ffffff 80%, transparent),
      radial-gradient(1px 1px at 80% 32%, #ffffff 80%, transparent),
      radial-gradient(1px 1px at 88% 18%, #ffffff 80%, transparent),
      radial-gradient(1px 1px at 15% 30%, #ffffff 80%, transparent),
      radial-gradient(1px 1px at 25% 55%, #ffffff 80%, transparent),
      radial-gradient(1px 1px at 35% 48%, #ffffff 80%, transparent),
      radial-gradient(1px 1px at 48% 60%, #ffffff 80%, transparent),
      radial-gradient(1px 1px at 60% 50%, #ffffff 80%, transparent),
      radial-gradient(1px 1px at 70% 62%, #ffffff 80%, transparent),
      radial-gradient(1px 1px at 82% 48%, #ffffff 80%, transparent),
      radial-gradient(1px 1px at 90% 58%, #ffffff 80%, transparent),
      radial-gradient(1px 1px at 12% 65%, #ffffff 80%, transparent),
      radial-gradient(1px 1px at 28% 72%, #ffffff 80%, transparent),
      radial-gradient(1px 1px at 44% 75%, #ffffff 80%, transparent),
      radial-gradient(1px 1px at 58% 70%, #ffffff 80%, transparent),
      radial-gradient(1px 1px at 66% 78%, #ffffff 80%, transparent),
      radial-gradient(1px 1px at 79% 72%, #ffffff 80%, transparent),
      radial-gradient(1px 1px at 88% 68%, #ffffff 80%, transparent);
    background-blend-mode: screen;
    opacity: 0.8;
    z-index: 0;
  }

  /* Clouds at bottom using multiple radial-gradients */
  .page-bg-simple::after {
    content: "";
    position: absolute;
    bottom: 0;
    left: 0; right: 0;
    height: 38vh;
    pointer-events: none;
    z-index: 1;
    background:
      radial-gradient(circle at 10% 80%, #1c2e55 0 28%, transparent 29%),
      radial-gradient(circle at 25% 90%, #1d335f 0 30%, transparent 31%),
      radial-gradient(circle at 40% 85%, #203965 0 26%, transparent 27%),
      radial-gradient(circle at 55% 92%, #223d6c 0 32%, transparent 33%),
      radial-gradient(circle at 70% 86%, #1f3661 0 27%, transparent 28%),
      radial-gradient(circle at 85% 90%, #1c2e55 0 30%, transparent 31%),
      radial-gradient(circle at 15% 100%, #142342 0 36%, transparent 37%),
      radial-gradient(circle at 35% 104%, #162746 0 34%, transparent 35%),
      radial-gradient(circle at 55% 101%, #13213f 0 38%, transparent 39%),
      radial-gradient(circle at 75% 103%, #152443 0 35%, transparent 36%),
      radial-gradient(circle at 92% 100%, #13203b 0 37%, transparent 38%);
    background-repeat: no-repeat;
    background-size: 100% 100%;
    mix-blend-mode: normal;
  }

  /* OPTIONAL: very soft star twinkle animation (opacity pulse) */
  @keyframes subtle-twinkle {
    0%, 100% { opacity: 0.8; }
    50% { opacity: 0.55; }
  }
  .page-bg-simple.twinkle::before {
    animation: subtle-twinkle 8s ease-in-out infinite;
  }
</style>

@push('page-scripts')
  @vite('resources/js/home.js')
@endpush

<main class="page-bg-simple twinkle relative">
  <!-- Таны контент энд (hero, books гэх мэт). -->
  @yield('home-content')
</main>

@include('include.footer')