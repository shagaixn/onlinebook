(() => {
  const TAU = Math.PI * 2;
  const prefersReduced = window.matchMedia?.('(prefers-reduced-motion: reduce)')?.matches ?? false;

  // Nebula
  function makeNebula(canvas) {
    const ctx = canvas.getContext('2d');
    let w = (canvas.width = canvas.clientWidth);
    let h = (canvas.height = canvas.clientHeight);

    const area = w * h;
    const targetBlobs = Math.max(6, Math.min(18, Math.floor(area / 120000)));
    const colors = [
      { h: 285, s: 70, l: 62 },
      { h: 205, s: 80, l: 60 },
      { h: 320, s: 70, l: 65 },
      { h: 190, s: 75, l: 58 }
    ];

    const blobs = Array.from({ length: targetBlobs }).map(() => {
      const hue = colors[Math.floor(Math.random() * colors.length)];
      const baseR = Math.sqrt(area) * (Math.random() * 0.25 + 0.18);
      const speed = (Math.random() * 0.15 + 0.05) / 60;
      const dir = Math.random() * TAU;
      return {
        x: Math.random() * w,
        y: Math.random() * h,
        vx: Math.cos(dir) * speed,
        vy: Math.sin(dir) * speed,
        baseR,
        r: baseR * (Math.random() * 0.2 + 0.9),
        pulse: Math.random() * TAU,
        pulseAmp: Math.random() * 0.12 + 0.08,
        hue
      };
    });

    function frame() {
      ctx.clearRect(0, 0, w, h);

      // faint base fog
      const fog = ctx.createRadialGradient(w * 0.5, h * 0.2, 50, w * 0.5, h * 0.2, Math.max(w, h));
      fog.addColorStop(0, 'rgba(60,100,200,0.08)');
      fog.addColorStop(1, 'rgba(0,0,0,0)');
      ctx.globalCompositeOperation = 'source-over';
      ctx.fillStyle = fog;
      ctx.fillRect(0, 0, w, h);

      ctx.globalCompositeOperation = 'screen';

      for (const b of blobs) {
        if (!prefersReduced) {
          b.pulse += 0.003 + Math.random() * 0.001;
          b.x += b.vx;
          b.y += b.vy;

          if (b.x < -b.baseR) b.x = w + b.baseR * 0.5;
          if (b.x > w + b.baseR) b.x = -b.baseR * 0.5;
          if (b.y < -b.baseR) b.y = h + b.baseR * 0.5;
          if (b.y > h + b.baseR) b.y = -b.baseR * 0.5;
        }

        const pulsate = 1 + Math.sin(b.pulse) * b.pulseAmp;
        const R = b.r * pulsate;

        for (let layer = 0; layer < 3; layer++) {
          const scale = 1 + layer * 0.45;
          const alpha = [0.16, 0.10, 0.06][layer];
          const gx = b.x + Math.cos(b.pulse + layer) * (R * 0.08);
          const gy = b.y + Math.sin(b.pulse * 0.9 + layer) * (R * 0.08);
          const grad = ctx.createRadialGradient(gx, gy, 0, gx, gy, R * scale);
          grad.addColorStop(0.0, `hsla(${b.hue.h}, ${b.hue.s}%, ${Math.min(95, b.hue.l + 10)}%, ${alpha})`);
          grad.addColorStop(0.35, `hsla(${b.hue.h}, ${b.hue.s}%, ${b.hue.l}%, ${alpha * 0.85})`);
          grad.addColorStop(0.7, `hsla(${b.hue.h}, ${b.hue.s}%, ${Math.max(30, b.hue.l - 20)}%, ${alpha * 0.25})`);
          grad.addColorStop(1.0, `hsla(${b.hue.h}, ${b.hue.s}%, ${Math.max(20, b.hue.l - 35)}%, 0)`);
          ctx.fillStyle = grad;
          ctx.beginPath();
          ctx.arc(gx, gy, R * scale, 0, TAU);
          ctx.fill();
        }
      }

      if (!prefersReduced) requestAnimationFrame(frame);
    }

    // Resize observer
    const ro = new ResizeObserver(() => {
      w = canvas.width = canvas.clientWidth;
      h = canvas.height = canvas.clientHeight;
      // redraw on resize
      frame();
    });
    ro.observe(canvas);

    // initial paint
    frame();
  }

  // Stars
  function makeStars(canvas, density = 0.0009) {
    const ctx = canvas.getContext('2d');
    let w = (canvas.width = canvas.clientWidth);
    let h = (canvas.height = canvas.clientHeight);

    const count = Math.max(60, Math.min(500, Math.floor(w * h * density)));
    const stars = Array.from({ length: count }).map(() => ({
      x: Math.random() * w,
      y: Math.random() * h,
      r: Math.random() * 1.1 + 0.2,
      a: Math.random() * 0.8 + 0.2,
      tw: (Math.random() * 0.8 + 0.2) * (Math.random() < 0.5 ? 1 : -1),
      sp: Math.random() * 0.015 + 0.005,
      hue: 200 + Math.random() * 40
    }));

    function frame() {
      ctx.clearRect(0, 0, w, h);

      const grad = ctx.createRadialGradient(w * 0.5, h * -0.1, 50, w * 0.5, h * -0.1, Math.max(w, h));
      grad.addColorStop(0, 'rgba(80,120,220,0.10)');
      grad.addColorStop(1, 'rgba(0,0,0,0)');
      ctx.fillStyle = grad;
      ctx.fillRect(0, 0, w, h);

      for (const s of stars) {
        if (!prefersReduced) {
          s.a += s.tw * s.sp;
          if (s.a > 1 || s.a < 0.15) s.tw *= -1;
        }

        const glow = ctx.createRadialGradient(s.x, s.y, 0, s.x, s.y, s.r * 6);
        glow.addColorStop(0, `hsla(${s.hue}, 70%, 85%, ${s.a * 0.9})`);
        glow.addColorStop(1, `hsla(${s.hue}, 70%, 85%, 0)`);
        ctx.fillStyle = glow;
        ctx.beginPath();
        ctx.arc(s.x, s.y, s.r * 6, 0, TAU);
        ctx.fill();

        ctx.fillStyle = `hsla(${s.hue}, 80%, 95%, ${Math.min(1, s.a + 0.2)})`;
        ctx.beginPath();
        ctx.arc(s.x, s.y, s.r, 0, TAU);
        ctx.fill();
      }

      if (!prefersReduced) requestAnimationFrame(frame);
    }

    const ro = new ResizeObserver(() => {
      w = canvas.width = canvas.clientWidth;
      h = canvas.height = canvas.clientHeight;
      frame();
    });
    ro.observe(canvas);

    frame();
  }

  function init() {
    document.querySelectorAll('.page-bg .sky-nebula').forEach((c) => makeNebula(c));
    document.querySelectorAll('.page-bg .sky-stars').forEach((c) => makeStars(c));
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init, { once: true });
  } else {
    init();
  }
})();   