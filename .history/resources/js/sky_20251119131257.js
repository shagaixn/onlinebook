/* Deep blue concentrated nebula + sparse stars
 * Replace previous sky.js with this.
 * Author: Copilot adaptation
 */
(() => {
  const TAU = Math.PI * 2;
  const prefersReduced = window.matchMedia?.('(prefers-reduced-motion: reduce)')?.matches ?? false;

  /* ---------------- Config ---------------- */
  const NEBULA_SETTINGS = {
    textureLowRes: 480,        // Offscreen квадрат хэмжээ (perf vs детал)
    noiseScale: 320,           // Томруулалт (ихсэх тусам манан нарийвчлал багасна)
    octaves: 5,
    persistence: 0.55,
    lacunarity: 2.1,
    contrastBoost: 2.2,        // 1.0–3.5: төвийг тодруулж ирмэгийг харанхуй
    radialFalloffPower: 1.85,  // Ирмэг рүү ямар хурдан унах (1.4–2.2)
    animate: true,
    driftSpeed: 0.002,         // Хэрэв animate=true бол noise seed shift
    baseColors: [
      { h: 210, s: 72, l: 54 }, // cyan-ish blue
      { h: 220, s: 68, l: 50 }, // deep indigo blue
      { h: 200, s: 65, l: 48 }  // teal-deep
    ],
    layers: 3,
    layerAlpha: [0.60, 0.35, 0.22], // давхарга тус бүрийн нягтрал
    layerBlur: [0, 4, 10],          // пост-блур (CSS биш – manual box blur)
    brightenCenter: 0.18,           // Төвийн нэмэлт гэрэлтүүлэг (0–0.3)
    screenGlowHue: 210,
    screenGlowSat: 80,
    screenGlowLight: 65
  };

  const STAR_SETTINGS = {
    density: 0.00035,
    minStars: 40,
    maxStars: 400,
    twinkleSpeedRange: [0.004, 0.011],
    radiusRange: [0.25, 1.3],
    centerBoostRadius: 0.35,    // canvas богино талбай * энэ үржвэр
    centerBoostExtra: 0.85      // төвд илүү тод glow
  };

  /* ---------------- Simplex Noise ---------------- */
  // Жижиг, dependencyгүй simplex (2D). Performance OK бага нягтрал дээр.
  class Simplex {
    constructor(seed = 0) {
      this.grad3 = [
        [1,1],[-1,1],[1,-1],[-1,-1],
        [1,0],[-1,0],[0,1],[0,-1]
      ];
      this.p = new Uint8Array(256);
      let x = seed * 16807 % 2147483647;
      for (let i=0;i<256;i++) {
        x = (x * 16807) % 2147483647;
        this.p[i] = x % 256;
      }
      this.perm = new Uint8Array(512);
      for (let i=0;i<512;i++) this.perm[i] = this.p[i & 255];
    }
    dot(g, x, y) { return g[0]*x + g[1]*y; }
    noise(xin, yin) {
      const F2 = 0.5*(Math.sqrt(3)-1);
      const G2 = (3-Math.sqrt(3))/6;
      let n0,n1,n2;
      const s = (xin+yin)*F2;
      const i = Math.floor(xin+s);
      const j = Math.floor(yin+s);
      const t = (i+j)*G2;
      const X0 = i - t;
      const Y0 = j - t;
      const x0 = xin - X0;
      const y0 = yin - Y0;
      let i1, j1;
      if (x0 > y0) { i1=1; j1=0; } else { i1=0; j1=1; }
      const x1 = x0 - i1 + G2;
      const y1 = y0 - j1 + G2;
      const x2 = x0 - 1 + 2*G2;
      const y2 = y0 - 1 + 2*G2;
      const ii = i & 255;
      const jj = j & 255;
      const gi0 = this.perm[ii+this.perm[jj]] % 8;
      const gi1 = this.perm[ii+i1+this.perm[jj+j1]] % 8;
      const gi2 = this.perm[ii+1+this.perm[jj+1]] % 8;
      let t0 = 0.5 - x0*x0 - y0*y0;
      if (t0<0) n0=0; else { t0*=t0; n0 = t0*t0*this.dot(this.grad3[gi0], x0,y0); }
      let t1 = 0.5 - x1*x1 - y1*y1;
      if (t1<0) n1=0; else { t1*=t1; n1 = t1*t1*this.dot(this.grad3[gi1], x1,y1); }
      let t2 = 0.5 - x2*x2 - y2*y2;
      if (t2<0) n2=0; else { t2*=t2; n2 = t2*t2*this.dot(this.grad3[gi2], x2,y2); }
      return 70*(n0+n1+n2); // range ~[-1,1]
    }
  }

  /* ---------------- Nebula Texture Generation ---------------- */
  function generateNebulaTexture(seedOffset=0) {
    const size = NEBULA_SETTINGS.textureLowRes;
    const off = document.createElement('canvas');
    off.width = off.height = size;
    const ctx = off.getContext('2d');
    const img = ctx.createImageData(size, size);
    const data = img.data;
    const simplex = new Simplex(seedOffset * 1337 + Date.now()%100000);

    // Generate multi-octave noise
    const { octaves, persistence, lacunarity, noiseScale, contrastBoost, radialFalloffPower } = NEBULA_SETTINGS;
    for (let y=0;y<size;y++) {
      for (let x=0;x<size;x++) {
        let nx = (x - size/2) / noiseScale;
        let ny = (y - size/2) / noiseScale;
        let amp = 1;
        let freq = 1;
        let value = 0;
        for (let o=0;o<octaves;o++) {
          value += amp * simplex.noise(nx*freq + seedOffset, ny*freq + seedOffset);
            amp *= persistence;
          freq *= lacunarity;
        }
        // normalize approx [-sumAmp,sumAmp]
        // sumAmp ≈ (1 - persistence^octaves)/(1 - persistence)
        const sumAmp = (1 - Math.pow(persistence, octaves))/(1 - persistence);
        value = (value / (sumAmp)) * 0.5 + 0.5; // [0,1]

        // Radial falloff
        const dx = (x - size/2)/(size/2);
        const dy = (y - size/2)/(size/2);
        const r = Math.sqrt(dx*dx + dy*dy);
        const falloff = Math.pow(Math.min(1, r), radialFalloffPower);
        value = value * (1 - falloff);

        // Contrast boost
        value = Math.pow(value, 1/contrastBoost);

        // Center brightening
        if (r < 0.25) {
          value = Math.min(1, value + NEBULA_SETTINGS.brightenCenter * (1 - r/0.25));
        }

        // Composite random base colors
        const cIdx = Math.floor(Math.random()*NEBULA_SETTINGS.baseColors.length);
        const { h, s, l } = NEBULA_SETTINGS.baseColors[cIdx];
        // Lightness modulation
        const light = l + (value * 25); // push brighter center
        const sat = s + value * 10;

        // Convert HSL to RGB (simple)
        const rgb = hslToRgb(h/360, sat/100, Math.min(1, light/100));
        const i = (y*size + x)*4;
        data[i] = rgb[0];
        data[i+1] = rgb[1];
        data[i+2] = rgb[2];
        data[i+3] = Math.round(value * 255); // alpha
      }
    }
    ctx.putImageData(img,0,0);

    // Multi-layer blur passes
    for (let layer=0; layer<NEBULA_SETTINGS.layers; layer++) {
      const blurRadius = NEBULA_SETTINGS.layerBlur[layer];
      if (blurRadius > 0) boxBlurRGBA(off, blurRadius);
      // apply global alpha scaling
      const alphaScale = NEBULA_SETTINGS.layerAlpha[layer];
      if (alphaScale < 1) {
        const id = ctx.getImageData(0,0,size,size);
        for (let i=3;i<id.data.length;i+=4) {
          id.data[i] = Math.min(255, id.data[i]*alphaScale);
        }
        ctx.putImageData(id,0,0);
      }
    }
    return off;
  }

  // Simple box blur (separable)
  function boxBlurRGBA(canvas, radius) {
    const w = canvas.width, h = canvas.height;
    const ctx = canvas.getContext('2d');
    const src = ctx.getImageData(0,0,w,h);
    const dst = ctx.createImageData(w,h);
    const tmp = ctx.createImageData(w,h);

    // horizontal
    const r = radius;
    for (let y=0;y<h;y++) {
      let sumR=0,sumG=0,sumB=0,sumA=0;
      const lineIndex = y*w*4;
      for (let x=-r; x<=r; x++) {
        const clx = Math.min(w-1, Math.max(0,x));
        const idx = lineIndex + clx*4;
        sumR += src.data[idx];
        sumG += src.data[idx+1];
        sumB += src.data[idx+2];
        sumA += src.data[idx+3];
      }
      for (let x=0;x<w;x++) {
        const outIdx = lineIndex + x*4;
        tmp.data[outIdx] = sumR/(r*2+1);
        tmp.data[outIdx+1] = sumG/(r*2+1);
        tmp.data[outIdx+2] = sumB/(r*2+1);
        tmp.data[outIdx+3] = sumA/(r*2+1);
        // slide
        const addX = x + r + 1;
        const remX = x - r;
        if (addX < w) {
          const addIdx = lineIndex + addX*4;
          sumR += src.data[addIdx]; sumG += src.data[addIdx+1]; sumB += src.data[addIdx+2]; sumA += src.data[addIdx+3];
        }
        if (remX >= 0) {
          const remIdx = lineIndex + remX*4;
          sumR -= src.data[remIdx]; sumG -= src.data[remIdx+1]; sumB -= src.data[remIdx+2]; sumA -= src.data[remIdx+3];
        }
      }
    }
    // vertical
    for (let x=0;x<w;x++) {
      let sumR=0,sumG=0,sumB=0,sumA=0;
      for (let y=-r;y<=r;y++) {
        const cly = Math.min(h-1, Math.max(0,y));
        const idx = (cly*w + x)*4;
        sumR += tmp.data[idx];
        sumG += tmp.data[idx+1];
        sumB += tmp.data[idx+2];
        sumA += tmp.data[idx+3];
      }
      for (let y=0;y<h;y++) {
        const outIdx = (y*w + x)*4;
        dst.data[outIdx] = sumR/(r*2+1);
        dst.data[outIdx+1] = sumG/(r*2+1);
        dst.data[outIdx+2] = sumB/(r*2+1);
        dst.data[outIdx+3] = sumA/(r*2+1);
        const addY = y + r + 1;
        const remY = y - r;
        if (addY < h) {
          const addIdx = (addY*w + x)*4;
          sumR += tmp.data[addIdx]; sumG += tmp.data[addIdx+1]; sumB += tmp.data[addIdx+2]; sumA += tmp.data[addIdx+3];
        }
        if (remY >= 0) {
          const remIdx = (remY*w + x)*4;
          sumR -= tmp.data[remIdx]; sumG -= tmp.data[remIdx+1]; sumB -= tmp.data[remIdx+2]; sumA -= tmp.data[remIdx+3];
        }
      }
    }
    ctx.putImageData(dst,0,0);
  }

  function hslToRgb(h, s, l){
    if (s === 0) {
      const v = l*255;
      return [v,v,v];
    }
    const hue2rgb = (p,q,t)=>{
      if (t<0) t+=1;
      if (t>1) t-=1;
      if (t<1/6) return p + (q-p)*6*t;
      if (t<1/2) return q;
      if (t<2/3) return p + (q-p)*(2/3 - t)*6;
      return p;
    };
    const q = l < 0.5 ? l*(1+s) : l + s - l*s;
    const p = 2*l - q;
    const r = hue2rgb(p,q,h+1/3);
    const g = hue2rgb(p,q,h);
    const b = hue2rgb(p,q,h-1/3);
    return [Math.round(r*255), Math.round(g*255), Math.round(b*255)];
  }

  /* ---------------- Nebula Draw ---------------- */
  function makeNebula(canvas) {
    const ctx = canvas.getContext('2d');
    let w = (canvas.width = canvas.clientWidth);
    let h = (canvas.height = canvas.clientHeight);

    let seedShift = 0;
    let texture = generateNebulaTexture(seedShift);

    function redrawTexture() {
      texture = generateNebulaTexture(seedShift);
    }

    function frame() {
      ctx.clearRect(0,0,w,h);
      // Draw scaled texture centered
      const scale = Math.max(w,h) / NEBULA_SETTINGS.textureLowRes * 1.2;
      const drawW = texture.width * scale;
      const drawH = texture.height * scale;
      ctx.globalCompositeOperation = 'screen';
      ctx.globalAlpha = 1;
      ctx.drawImage(texture, (w-drawW)/2, (h-drawH)/2, drawW, drawH);

      // Vignette darkening edges a bit
      const vignette = ctx.createRadialGradient(w/2, h/2, Math.min(w,h)*0.2, w/2, h/2, Math.max(w,h)*0.55);
      vignette.addColorStop(0, 'rgba(255,255,255,0)');
      vignette.addColorStop(1, 'rgba(0,0,20,0.55)');
      ctx.globalCompositeOperation = 'multiply';
      ctx.fillStyle = vignette;
      ctx.fillRect(0,0,w,h);

      // Subtle central glow
      ctx.globalCompositeOperation = 'screen';
      const glow = ctx.createRadialGradient(w/2, h/2, 0, w/2, h/2, Math.min(w,h)*0.35);
      glow.addColorStop(0, `hsla(${NEBULA_SETTINGS.screenGlowHue},${NEBULA_SETTINGS.screenGlowSat}%,${NEBULA_SETTINGS.screenGlowLight}%,0.4)`);
      glow.addColorStop(1, 'rgba(0,0,0,0)');
      ctx.fillStyle = glow;
      ctx.fillRect(0,0,w,h);

      if (NEBULA_SETTINGS.animate && !prefersReduced) {
        seedShift += NEBULA_SETTINGS.driftSpeed;
        // Ре-генерац хийх давтамжийг багасгах (жижиг хөдөлгөөн)
        if (seedShift >= 0.05) {
          seedShift = 0;
          redrawTexture();
        }
        requestAnimationFrame(frame);
      }
    }

    // Resize
    const ro = new ResizeObserver(() => {
      w = canvas.width = canvas.clientWidth;
      h = canvas.height = canvas.clientHeight;
      frame();
    });
    ro.observe(canvas);

    frame();
  }

  /* ---------------- Stars ---------------- */
  function makeStars(canvas) {
    const ctx = canvas.getContext('2d');
    let w = (canvas.width = canvas.clientWidth);
    let h = (canvas.height = canvas.clientHeight);

    function initStars() {
      const count = Math.max(STAR_SETTINGS.minStars,
        Math.min(STAR_SETTINGS.maxStars, Math.floor(w*h*STAR_SETTINGS.density)));
      return Array.from({length: count}).map(()=> {
        const rBaseMin = STAR_SETTINGS.radiusRange[0];
        const rBaseMax = STAR_SETTINGS.radiusRange[1];
        const x = Math.random()*w;
        const y = Math.random()*h;
        const centerDist = Math.hypot(x - w/2, y - h/2) / (Math.min(w,h)*0.5);
        const centerBoost = centerDist < STAR_SETTINGS.centerBoostRadius
          ? (1 - centerDist/STAR_SETTINGS.centerBoostRadius) * STAR_SETTINGS.centerBoostExtra
          : 0;
        return {
          x, y,
          r: (Math.random()*(rBaseMax - rBaseMin) + rBaseMin) + centerBoost*0.8,
          a: Math.random()*0.7 + 0.3,
          tw: (Math.random()<0.5?1:-1) * (Math.random()*0.6 + 0.4),
          sp: Math.random()*(STAR_SETTINGS.twinkleSpeedRange[1]-STAR_SETTINGS.twinkleSpeedRange[0]) + STAR_SETTINGS.twinkleSpeedRange[0],
          hue: 200 + Math.random()*30
        };
      });
    }

    let stars = initStars();

    function draw() {
      ctx.clearRect(0,0,w,h);
      // (Optional) faint overall haze
      const haze = ctx.createRadialGradient(w/2, h/2, Math.min(w,h)*0.05, w/2, h/2, Math.max(w,h)*0.6);
      haze.addColorStop(0,'rgba(80,120,200,0.10)');
      haze.addColorStop(1,'rgba(0,0,0,0)');
      ctx.fillStyle = haze;
      ctx.fillRect(0,0,w,h);

      for (const s of stars) {
        if (!prefersReduced) {
          s.a += s.tw*s.sp;
          if (s.a > 1 || s.a < 0.15) s.tw *= -1;
        }

        // Glow
        const glow = ctx.createRadialGradient(s.x,s.y,0,s.x,s.y,s.r*6);
        glow.addColorStop(0, `hsla(${s.hue},70%,85%,${s.a*0.9})`);
        glow.addColorStop(1, `hsla(${s.hue},70%,85%,0)`);
        ctx.globalCompositeOperation = 'screen';
        ctx.fillStyle = glow;
        ctx.beginPath();
        ctx.arc(s.x,s.y,s.r*6,0,TAU);
        ctx.fill();

        // Core
        ctx.globalCompositeOperation = 'lighter';
        ctx.fillStyle = `hsla(${s.hue},80%,95%,${Math.min(1,s.a+0.25)})`;
        ctx.beginPath();
        ctx.arc(s.x,s.y,s.r,0,TAU);
        ctx.fill();
      }

      if (!prefersReduced) requestAnimationFrame(draw);
    }

    // Resize
    const ro = new ResizeObserver(()=>{
      w = canvas.width = canvas.clientWidth;
      h = canvas.height = canvas.clientHeight;
      stars = initStars();
      draw();
    });
    ro.observe(canvas);

    draw();
  }

  /* ---------------- Init ---------------- */
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