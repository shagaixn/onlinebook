/**
 * Enhanced JS for home page interactions and accessibility.
 * Place this file at: public/js/home.js and include with:
 *   <script src="{{ asset('js/home.js') }}" defer></script>
 *
 * Improvements:
 * - Background image preload and "bg-loaded" class toggle for nicer paint.
 * - Robust marquee controls (pause on hover/focus/keyboard + play/pause button).
 * - IntersectionObserver to add `.in-view` to cards/covers for subtle entrance effects.
 * - Global image error fallback to placeholder.
 * - Prevent double form submissions and show aria-busy.
 * - Better link transition handling for elements with data-transition.
 * - Respects prefers-reduced-motion.
 */
(function () {
  'use strict';

  const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  // Small utility helpers
  const $ = (sel, ctx = document) => Array.from(ctx.querySelectorAll(sel));
  const on = (el, ev, fn, opts) => el && el.addEventListener(ev, fn, opts);
  const once = (el, ev, fn, opts) => el && el.addEventListener(ev, fn, Object.assign({ once: true }, opts));
  const debounce = (fn, wait = 150) => {
    let t;
    return function (...args) {
      clearTimeout(t);
      t = setTimeout(() => fn.apply(this, args), wait);
    };
  };

  // Preload background image used by .page-bg (if any) and add class 'bg-loaded' on load
  function preloadPageBg() {
    const el = document.querySelector('.page-bg');
    if (!el) return;

    // Extract URL from computed style background-image
    const bg = getComputedStyle(el).backgroundImage || '';
    const match = bg.match(/url\((?:'|")?(.*?)(?:'|")?\)/);
    const url = match ? match[1] : null;
    if (!url) {
      el.classList.add('bg-loaded'); // nothing to wait for
      return;
    }

    const img = new Image();
    img.onload = () => el.classList.add('bg-loaded');
    img.onerror = () => {
      // graceful fallback if image fails
      el.classList.add('bg-loaded');
      console.warn('Background image failed to load:', url);
    };
    // If url is relative (asset), try to resolve absolute path for current host
    img.src = url;
  }

  // Marquee controls: pause/resume + accessible button + keyboard control
  function enhanceMarquees() {
    const marquees = $('.animate-marquee');
    if (!marquees.length) return;

    marquees.forEach((marquee) => {
      // Respect reduced motion: don't animate if user prefers reduced motion
      if (prefersReducedMotion) {
        marquee.style.animation = 'none';
        return;
      }

      // Ensure paused when hovered or focused
      marquee.addEventListener('mouseenter', () => marquee.style.animationPlayState = 'paused');
      marquee.addEventListener('mouseleave', () => marquee.style.animationPlayState = 'running');
      marquee.addEventListener('focusin', () => marquee.style.animationPlayState = 'paused');
      marquee.addEventListener('focusout', () => marquee.style.animationPlayState = 'running');

      // Add keyboard controls: left/right arrows to nudge scroll (for non-animated cases)
      marquee.addEventListener('keydown', (e) => {
        if (e.key === ' ' || e.key === 'Spacebar') {
          // toggle pause
          e.preventDefault();
          const paused = marquee.style.animationPlayState === 'paused';
          marquee.style.animationPlayState = paused ? 'running' : 'paused';
        } else if (e.key === 'ArrowLeft' || e.key === 'ArrowRight') {
          // move the marquee slightly (useful for accessibility)
          e.preventDefault();
          const step = marquee.offsetWidth * 0.05;
          const current = marquee._scrollOffset || 0;
          marquee._scrollOffset = e.key === 'ArrowLeft' ? current - step : current + step;
          marquee.style.transform = `translateX(${marquee._scrollOffset}px)`;
        }
      });

      // Insert a simple play/pause button for sighted users (append to container)
      const container = marquee.closest('.marquee-container') || marquee.parentElement;
      if (container && !container.querySelector('.marquee-toggle')) {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'marquee-toggle';
        btn.setAttribute('aria-pressed', 'false');
        btn.setAttribute('aria-label', 'Pause marquee');
        btn.textContent = 'Pause';
        btn.style.cssText = 'position:absolute; right:12px; top:8px; z-index:20; background:rgba(0,0,0,0.45); color:#fff; border:none; padding:.25rem .5rem; border-radius:.375rem; font-size:.875rem;';
        btn.addEventListener('click', () => {
          const paused = btn.getAttribute('aria-pressed') === 'true';
          btn.setAttribute('aria-pressed', String(!paused));
          btn.textContent = paused ? 'Pause' : 'Play';
          marquee.style.animationPlayState = paused ? 'running' : 'paused';
        });
        container.style.position = container.style.position || 'relative';
        container.appendChild(btn);
      }
    });
  }

  // IntersectionObserver to add .in-view for subtle reveal animations.
  function observeInView() {
    const targets = $('.group, .cover-size, section, article');
    if (!targets.length) return;
    if (prefersReducedMotion) {
      // If reduced motion, immediately add in-view
      targets.forEach(t => t.classList.add('in-view'));
      return;
    }

    const io = new IntersectionObserver((entries) => {
      entries.forEach(e => {
        if (e.isIntersecting) {
          e.target.classList.add('in-view');
          // optional: unobserve after in view for performance
          io.unobserve(e.target);
        }
      });
    }, { threshold: 0.12 });

    targets.forEach((t) => io.observe(t));
  }

  // Global image onerror fallback to placeholder (delegated)
  function attachImageFallback(placeholder = '/images/placeholder-book.png') {
    // Use capturing listener for image errors
    window.addEventListener('error', (ev) => {
      const el = ev.target;
      if (!(el instanceof HTMLImageElement)) return;
      // if already fallback, don't loop
      if (el.dataset.fallbackApplied) return;
      el.dataset.fallbackApplied = '1';
      // use data-src if present or fallback placeholder
      el.src = el.getAttribute('data-fallback-src') || placeholder;
    }, true);
  }

  // Prevent duplicate form submissions, show aria-busy
  function preventDoubleSubmit() {
    document.addEventListener('submit', (e) => {
      const form = e.target;
      if (!(form instanceof HTMLFormElement)) return;
      // allow forms marked data-no-disable to opt out
      if (form.dataset.noDisable === 'true') return;

      // Find primary submit button(s)
      const submitButtons = Array.from(form.querySelectorAll('button[type="submit"], input[type="submit"]'));
      if (!submitButtons.length) return;

      // If already submitted, prevent
      if (form.dataset.submitted === 'true') {
        e.preventDefault();
        return;
      }

      // Mark submitted and disable
      form.dataset.submitted = 'true';
      submitButtons.forEach(btn => {
        try {
          btn.disabled = true;
          btn.setAttribute('aria-disabled', 'true');
        } catch (err) { /* ignore */ }
      });

      // mark form as busy for assistive tech
      form.setAttribute('aria-busy', 'true');

      // If the form doesn't actually navigate (AJAX), callers must re-enable themselves.
      // As a safety, re-enable after 10s to avoid permanent disabled state in edge cases.
      setTimeout(() => {
        if (form.dataset.submitted === 'true') {
          delete form.dataset.submitted;
          form.removeAttribute('aria-busy');
          submitButtons.forEach(btn => {
            btn.disabled = false;
            btn.removeAttribute('aria-disabled');
          });
        }
      }, 10000);
    }, true);
  }

  // Link transition handling for a[data-transition] more robust (supports same-page anchors)
  function handleTransitionLinks() {
    const card = document.querySelector('#auth-card');

    document.addEventListener('click', (e) => {
      const a = e.target.closest && e.target.closest('a[data-transition]');
      if (!a) return;
      const href = a.getAttribute('href');
      // allow hash/anchor or external links
      if (!href || href.startsWith('#') || href.startsWith('mailto:') || href.startsWith('tel:')) return;

      // external origin? let default happen
      try {
        const targetUrl = new URL(href, document.baseURI);
        if (targetUrl.origin !== location.origin) return;
      } catch (err) {
        // not a well-formed URL - allow default
      }

      e.preventDefault();
      if (!card) {
        window.location.href = href;
        return;
      }

      // add exit class and wait for animationend
      card.classList.remove('auth-enter');
      card.classList.add('auth-exit');

      once(card, 'animationend', () => {
        window.location.href = href;
      });
    });
  }

  // Optional: small improvement to adjust CSS var for cover-size (fallback for browsers without clamp support)
  function adjustCoverSizeFallback() {
    // Only run if browser doesn't support clamp (very rare)
    if ('CSS' in window && typeof CSS.supports === 'function' && CSS.supports('width', 'clamp(1rem, 10vw, 10rem)')) return;
    const root = document.documentElement;
    const apply = () => {
      const vw = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);
      // compute size similar to clamp(4.5rem, 9vw, 6.5rem)
      const min = 4.5 * 16; // px
      const max = 6.5 * 16;
      const ideal = Math.round(vw * 0.09); // 9vw
      const val = Math.max(min, Math.min(max, ideal));
      root.style.setProperty('--home-cover-size', `${val}px`);
    };
    on(window, 'resize', debounce(apply, 120));
    apply();
  }

  // initialize everything on DOMContentLoaded
  function init() {
    preloadPageBg();
    enhanceMarquees();
    observeInView();
    attachImageFallback();
    preventDoubleSubmit();
    handleTransitionLinks();
    adjustCoverSizeFallback();

    // Add small progressive enhancement: lazy-load background embedded inline images (if any)
    // Also add data-animated attribute for cards we want to animate
    $('.group, .cover-size').forEach(el => {
      if (!el.hasAttribute('tabindex')) {
        el.setAttribute('tabindex', '0'); // make focusable for keyboard users (non-interactive)
        el.style.outline = 'none';
      }
    });

    // Expose a small API for debugging/automation if needed
    window.__MBOOK = Object.assign(window.__MBOOK || {}, {
      pauseMarquees: () => $('.animate-marquee').forEach(m => m.style.animationPlayState = 'paused'),
      playMarquees: () => $('.animate-marquee').forEach(m => m.style.animationPlayState = 'running'),
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();