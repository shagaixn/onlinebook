<style>
  .page-bg {
    background: radial-gradient(at 55% 20%, rgba(56,189,248,0.15) 0,transparent 80%),
                radial-gradient(ellipse 80% 65% at 50% 100%, rgba(99,102,241,0.09) 10%,rgba(34,211,238,0.10) 40%,transparent 100%),
                linear-gradient(120deg, #0ea5e9 0%, #0b1120 40%, #6366f1 80%, #0b1120 100%);
    position: relative;
    overflow: hidden;
    z-index: 0;
  }
  /* Subtle animated gradient overlay */
  .page-bg::before {
    content: "";
    position: absolute;
    inset: 0;
    background:
      radial-gradient(circle, rgba(255,255,255,0.12) 0%, transparent 70%) 63% 30% / 120% 100% no-repeat,
      radial-gradient(circle, rgba(56,189,248,0.20) 0, transparent 80%) 30% 60% / 80% 55% no-repeat;
    opacity: 0.5;
    pointer-events: none;
    z-index: -2;
    animation: bg-float 24s ease-in-out infinite alternate;
  }
  @keyframes bg-float {
    0%,100% { transform: scale(1) rotate(-1deg); opacity: .5; }
    50%    { transform: scale(1.04) rotate(3deg); opacity: .7; }
  }

  /* Twinkling star field */
  .bg-stars {
    position: absolute;
    inset: 0;
    pointer-events: none;
    z-index: -1;
  }
  .bg-star {
    position: absolute;
    width: 2px;
    height: 2px;
    border-radius: 999px;
    background: white;
    filter: blur(0.5px) brightness(1.2);
    opacity: 0.7;
    animation: twinkle 7s infinite alternate both;
  }
  .bg-star:nth-child(1) { top: 18%; left: 10%; animation-delay: 1s; }
  .bg-star:nth-child(2) { top: 25%; left: 81%; width:1.4px;height:1.4px; opacity:.6; animation-delay: 2.3s;}
  .bg-star:nth-child(3) { top: 62%; left: 42%; width:2px; }
  .bg-star:nth-child(4) { top: 77%; left: 65%; width:1.2px; height:1.2px; opacity:.8; animation-delay: 4.7s;}
  .bg-star:nth-child(5) { top: 41%; left: 33%; width:1px; height:1px; opacity:.7; animation-delay: 3.6s;}
  .bg-star:nth-child(6) { top: 82%; left: 18%; animation-delay: 6.1s;}
  .bg-star:nth-child(7) { top: 13%; left: 93%; width:2.2px; animation-delay: 2.6s;}
  .bg-star:nth-child(8) { top: 44%; left: 57%; width:1.2px; animation-delay: 5.1s;}
  .bg-star:nth-child(9) { top: 56%; left: 85%; width:1px; animation-delay: 1.5s;}
  .bg-star:nth-child(10){top: 68%; left: 12%; width:1.7px; animation-delay: 4.2s;}
  @keyframes twinkle {
    0%,100% { opacity: 0.7; filter: brightness(1); }
    50%     { opacity: 1; filter: brightness(1.6); }
  }
</style>

<main class="page-bg relative min-h-[60vh] overflow-hidden">
  <!-- Extra: Animated Stars Layer -->
  <div class="bg-stars">
    <div class="bg-star"></div>
    <div class="bg-star"></div>
    <div class="bg-star"></div>
    <div class="bg-star"></div>
    <div class="bg-star"></div>
    <div class="bg-star"></div>
    <div class="bg-star"></div>
    <div class="bg-star"></div>
    <div class="bg-star"></div>
    <div class="bg-star"></div>
  </div>
  <!-- Background decoration -->
  <div aria-hidden="true" class="pointer-events-none absolute inset-0 -z-10">
    <div class="absolute -top-24 -left-24 h-72 w-72 rounded-full bg-blue-500/10 blur-3xl"></div>
    <div class="absolute -bottom-24 -right-24 h-72 w-72 rounded-full bg-indigo-500/10 blur-3xl"></div>
  </div>
  <!-- The rest of your content below ... -->