@include('include.header')

<style>
  .text-gradient {
    background: linear-gradient(90deg,#5fa8ff 0%,#6679ff 50%,#2bd9ff 100%);
    -webkit-background-clip:text;
    background-clip:text;
    color:transparent;
    text-shadow:0 2px 6px rgba(0,0,0,0.45);
  }

  /* Илүү гүн бараан суурь */
  .page-bg {
    background:
      radial-gradient(1200px 800px at 50% -250px, rgba(60,100,180,0.15), transparent 70%),
      linear-gradient(180deg,#030509 0%, #07101c 40%, #081a2c 65%, #061a29 100%);
    position:relative;
    overflow:hidden;
    z-index:0;
    min-height:75vh;
  }

  .sky-canvas {
    position:absolute;
    inset:0;
    width:100%;
    height:100%;
    display:block;
    pointer-events:none;
  }
  /* Nebula-г арай илүү тодруулж болно (opacity 0.9–1 орчим) */
  .sky-nebula { z-index:-30; opacity:0.95; }
  .sky-stars  { z-index:-20; }

  .bg-decor { z-index:-10; }

  @keyframes marquee { 0%{transform:translateX(0)} 100%{transform:translateX(-50%)} }
  .marquee-container {
    mask-image:linear-gradient(to right,transparent,black 10%,black 90%,transparent);
    -webkit-mask-image:linear-gradient(to right,transparent,black 10%,black 90%,transparent);
  }
  .animate-marquee {
    display:flex;width:max-content;
    animation:marquee 22s linear infinite;
    will-change:transform;
  }
  .animate-marquee:hover { animation-play-state:paused; }
  @media (prefers-reduced-motion:reduce){ .animate-marquee{animation:none;} }

  @keyframes float { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-10px)} }
  .float-slow { animation:float 6s ease-in-out infinite; }

  :root { --home-cover-size: clamp(4.5rem, 9vw, 6.5rem); }
  .cover-size { width:var(--home-cover-size); height:var(--home-cover-size); }

  @keyframes spin-slow { to { transform:rotate(360deg); } }
  .animate-spin-slow { animation:spin-slow 40s linear infinite; }
</style>

@push('page-scripts')
  @vite(['resources/js/home.js','resources/js/sky.js'])
@endpush

<main class="page-bg relative overflow-hidden">
  <canvas class="sky-canvas sky-nebula" aria-hidden="true"></canvas>
  <canvas class="sky-canvas sky-stars" aria-hidden="true"></canvas>

  <div aria-hidden="true" class="bg-decor pointer-events-none absolute inset-0">
    <!-- Хэрэв манан хэт их давхцвал эдгээрийг багасгах эсвэл устгах -->
    <div class="absolute -top-24 -left-24 h-72 w-72 rounded-full bg-blue-500/10 blur-3xl"></div>
    <div class="absolute -bottom-24 -right-24 h-72 w-72 rounded-full bg-cyan-500/10 blur-3xl"></div>
  </div>

  <!-- Hero ... (таны үлдсэн контент өмнөхтэй адил) -->
</main>

@include('include.footer')