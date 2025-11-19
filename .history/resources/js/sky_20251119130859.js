(() => {
  const TAU = Math.PI * 2;
  const prefersReduced = window.matchMedia?.('(prefers-reduced-motion: reduce)')?.matches ?? false;

  // Deep blue nebula
  function makeNebula(canvas, customColors) {
    const ctx = canvas.getContext('2d');
    let w = (canvas.width = canvas.clientWidth);
    let h = (canvas.height = canvas.clientHeight);

    const area = w * h;
    // Талбайгаас blobs тоог тооцох
    const targetBlobs = Math.max(6, Math.min(18, Math.floor(area / 120000)));

    const colors = customColors || [
      { h: 215, s: 70, l: 50 }, // deep marine blue
      { h: 205, s: 75, l: 55 }, // cyan-ish
      { h: 225, s: 68, l: 48 }, // indigo-blue
      { h: 200, s: 60, l: 52 }, // teal blue
      { h: 230, s: 65, l: 46 }  // darker accent
    ];

    const blobs = Array.from({ length: targetBlobs }).map(() => {
      const hue = colors[Math.floor(Math.random() * colors.length)];
      const baseR = Math.sqrt(area) * (Math.random() * 0.23 + 0.17);
      const speed = (Math.random() * 0.12 + 0.04) / 60;
      const dir = Math.random() * TAU;
      return {
        x: Math.random() * w,
        y: Math.random() * h,
        vx: Math.cos(dir) * speed,
        vy: Math.sin(dir) * speed,
        baseR,
        r: baseR * (Math.random() * 0.25 + 0.85),
        pulse: Math.random() * TAU,
        pulseAmp: Math.random() * 0.14 + 0.09,
        hue
      };
    });

    function draw() {
      ctx.clearRect(0, 0, w, h);

      // Very faint base fog layer
      const fog = ctx.createRadialGradient(w * 0.5, h * 0.25, 60, w * 0.5, h * 0.25, Math.max(w, h));
      fog.addColorStop(0, 'rgba(70,110,200,0.10)');
      fog.addColorStop(1, 'rgba(0,0,0,0)');
      ctx.globalCompositeOperation = 'source-over';
      ctx.fillStyle = fog;
      ctx.fillRect(0, 0, w, h);

      ctx.globalCompositeOperation = 'screen';

      for (const b of blobs) {
        if (!prefersReduced) {
          b.pulse += 0.0035;
          b.x += b.vx;
          b.y += b.vy;
          // wrap
          if (b.x < -b.baseR) b.x = w + b.baseR * 0.4;
          if (b.x > w + b.baseR) b.x = -b.baseR * 0.4;
          if (b.y < -b.baseR) b.y = h + b.baseR * 0.4;
          if (b.y > h + b.baseR) b.y = -b.baseR * 0.4;
        }
        const pulsate = 1 + Math.sin(b.pulse) * b.pulseAmp;
        const R = b.r * pulsate;

        // 3–4 давхарга илүү гүн
        for (let layer = 0; layer < 4; layer++) {
          const scale = 1 + layer * 0.40;
          const alpha = [0.18, 0.11, 0.07, 0.045][layer];
          const gx = b.x + Math.cos(b.pulse + layer * 0.7) * (R * 0.07);
            const gy = b.y + Math.sin(b.pulse * 0.85 + layer) * (R * 0.07);
          const grad = ctx.createRadialGradient(gx, gy, 0, gx, gy, R * scale);
          grad.addColorStop(0.0, `hsla(${b.hue.h}, ${b.hue.s}%, ${Math.min(90, b.hue.l + 18)}%, ${alpha})`);
          grad.addColorStop(0.35, `hsla(${b.hue.h}, ${b.hue.s}%, ${b.hue.l + 5}%, ${alpha * 0.85})`);
          grad.addColorStop(0.65, `hsla(${b.hue.h}, ${b.hue.s}%, ${Math.max(30, b.hue.l - 10)}%, ${alpha * 0.35})`);
          grad.addColorStop(1.0, `hsla(${b.hue.h}, ${b.hue.s}%, ${Math.max(18, b.hue.l - 28)}%, 0)`);
          ctx.fillStyle = grad;
          ctx.beginPath();
          ctx.arc(gx, gy, R * scale, 0, TAU);
          ctx.fill();
        }
      }

      if (!prefersReduced) requestAnimationFrame(draw);
    }

    // Resize
    const ro = new ResizeObserver(() => {
      w = canvas.width = canvas.clientWidth;
      h = canvas.height = canvas.clientHeight;
      draw();
    });
    ro.observe(canvas);

    draw();
  }

  // Stars
  function makeStars(canvas, density = 0.0008) {
    const ctx = canvas.getContext('2d');
    let w = (canvas.width = canvas.clientWidth);
    let h = (canvas.height = canvas.clientHeight);

    const count = Math.max(80, Math.min(600, Math.floor(w * h * density)));
    const stars = Array.from({ length: count }).map(() => ({
      x: Math.random() * w,
      y: Math.random() * h,
      r: Math.random() * 1.1 + 0.25,
      a: Math.random() * 0.8 + 0.2,
      tw: (Math.random() * 0.8 + 0.2) * (Math.random() < 0.5 ? 1 : -1),
      sp: Math.random() * 0.012 + 0.004,
      hue: 200 + Math.random() * 35
    }));

    function draw() {
      ctx.clearRect(0, 0, w, h);
      const grad = ctx.createRadialGradient(w * 0.5, h * -0.1, 40, w * 0.5, h * -0.1, Math.max(w, h));
      grad.addColorStop(0, 'rgba(90,130,220,0.12)');
      grad.addColorStop(1, 'rgba(0,0,0,0)');
      ctx.fillStyle = grad;
      ctx.fillRect(0, 0, w, h);

      for (const s of stars) {
        if (!prefersReduced) {
          s.a += s.tw * s.sp;
          if (s.a > 1 || s.a < 0.15) s.tw *= -1;
        }
        const glow = ctx.createRadialGradient(s.x, s.y, 0, s.x, s.y, s.r * 6);
        glow.addColorStop(0, `hsla(${s.hue},70%,85%,${s.a * 0.9})`);
        glow.addColorStop(1, `hsla(${s.hue},70%,85%,0)`);
        ctx.fillStyle = glow;
        ctx.beginPath();
        ctx.arc(s.x, s.y, s.r * 6, 0, TAU);
        ctx.fill();

        ctx.fillStyle = `hsla(${s.hue},80%,95%,${Math.min(1, s.a + 0.25)})`;
        ctx.beginPath();
        ctx.arc(s.x, s.y, s.r, 0, TAU);
        ctx.fill();
      }

      if (!prefersReduced) requestAnimationFrame(draw);
    }

    const ro = new ResizeObserver(() => {
      w = canvas.width = canvas.clientWidth;
      h = canvas.height = canvas.clientHeight;
      draw();
    });
    ro.observe(canvas);

    draw();
  }

  function init() {
    document.querySelectorAll('.page-bg .sky-nebula').forEach(c => makeNebula(c));
    document.querySelectorAll('.page-bg .sky-stars').forEach(c => makeStars(c));
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init, { once:true });
  } else {
    init();
  }
})();