{{-- Improved login page with accessibility, UX, and small JS enhancements --}}
@include('include.header')

<style>
  /* Auth card animations & utilities */
  @keyframes auth-in { from { opacity: 0; transform: translateY(12px) scale(.995) } to { opacity: 1; transform: none } }
  @keyframes auth-out { from { opacity: 1; transform: none } to { opacity: 0; transform: translateY(-12px) scale(.995) } }
  .auth-enter { animation: auth-in .36s cubic-bezier(.16,.84,.44,1) both; }
  .auth-exit { animation: auth-out .26s cubic-bezier(.7,0,.84,.98) both; }

  /* Card visuals */
  .auth-card {
    background: linear-gradient(180deg, rgba(255,255,255,0.82), rgba(255,255,255,0.76));
    backdrop-filter: blur(6px);
  }
  .dark .auth-card {
    background: linear-gradient(180deg, rgba(15,23,42,0.75), rgba(15,23,42,0.85));
  }

  /* Reduced motion respect */
  @media (prefers-reduced-motion: reduce) {
    .auth-enter, .auth-exit { animation: none !important; }
  }

  /* Small helper for icon button */
  .icon-btn { display:inline-flex; align-items:center; justify-content:center; gap:.5rem; }
</style>

<main class="min-h-screen bg-gray-50 dark:bg-slate-900 flex items-center justify-center px-4 py-12">
  <div class="w-full max-w-md">
    <div id="auth-card" class="auth-card border border-gray-200 dark:border-slate-700 rounded-2xl p-8 shadow-xl auth-enter">
      <!-- Optional: app logo -->
      <div class="flex items-center justify-center mb-6">
        <a href="{{ url('/') }}" class="flex items-center gap-3">
          <img src="{{ asset('images/logo.svg') }}" alt="Mbook" class="h-10 w-10 object-contain" onerror="this.style.display='none'">
          <span class="font-extrabold text-lg text-slate-900 dark:text-white">Mbook</span>
        </a>
      </div>

      {{-- Status / errors --}}
      @if (session('status'))
        <div role="status" aria-live="polite" class="mb-4 text-sm text-green-700 dark:text-green-300 bg-green-50 dark:bg-green-900/20 p-3 rounded">
          {{ session('status') }}
        </div>
      @endif

      @if ($errors->any())
        <div role="alert" aria-live="assertive" class="mb-4 text-sm text-red-700 dark:text-red-300 bg-red-50 dark:bg-red-900/20 p-3 rounded">
          <strong class="font-medium">Алдаа:</strong>
          <ul class="mt-1 list-disc list-inside">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <h2 class="text-2xl font-semibold text-slate-900 dark:text-white text-center mb-4">Нэвтрэх</h2>

      <form method="POST" action="{{ route('login') }}" novalidate class="space-y-4" aria-describedby="login-desc">
        @csrf

        <p id="login-desc" class="sr-only">Имэйлээр нэвтрэх эсвэл доор заасан сошиал товчлууруудаар үргэлжлүүлнэ үү.</p>

        <div>
          <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-200">Имэйл</label>
          <input
            id="email"
            name="email"
            type="email"
            inputmode="email"
            autocomplete="username"
            required
            value="{{ old('email') }}"
            aria-required="true"
            aria-describedby="@error('email') email-error @enderror"
            class="mt-1 block w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-800 transition"
            placeholder="name@example.com"
          />
          @error('email')
            <p id="email-error" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <div class="flex items-center justify-between">
            <label for="password" class="block text-sm font-medium text-slate-700 dark:text-slate-200">Нууц үг</label>
            <a href= class="text-sm text-blue-600 dark:text-blue-400 hover:underline">Нууц үг мартсан?</a>
          </div>

          <div class="relative mt-1">
            <input
              id="password"
              name="password"
              type="password"
              autocomplete="current-password"
              required
              aria-required="true"
              aria-describedby="@error('password') password-error @enderror"
              class="block w-full px-4 py-2 pr-12 rounded-lg border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-800 transition"
              placeholder="••••••••"
            />
            <button type="button" id="toggle-password" aria-pressed="false" aria-label="Нууц үг харах/хаах" class="absolute inset-y-0 right-2 px-2 rounded text-slate-500 dark:text-slate-300 hover:text-slate-700 dark:hover:text-white focus:outline-none focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-800">
              <!-- eye icon -->
              <svg id="eye-open" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M2 12s4-8 10-8 10 8 10 8-4 8-10 8S2 12 2 12z"/><circle cx="12" cy="12" r="3"/></svg>
              <svg id="eye-closed" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M3 3l18 18"/><path d="M10.58 10.58A3 3 0 0 0 13.42 13.42"/></svg>
            </button>
          </div>
          @error('password')
            <p id="password-error" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
          @enderror
        </div>

        <div class="flex items-center justify-between">
          <label class="inline-flex items-center gap-2 text-sm text-slate-700 dark:text-slate-200">
            <input type="checkbox" name="remember" class="h-4 w-4 rounded border-gray-300 dark:border-slate-600 text-blue-600 focus:ring-blue-300">
            <span>Намайг сана</span>
          </label>

          <div class="text-sm">
            <a href="{{ route('register') }}" class="text-blue-600 dark:text-blue-400 hover:underline">Шинээр бүртгүүлэх</a>
          </div>
        </div>

        <div>
          <button type="submit" class="w-full inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 dark:bg-blue-600 dark:hover:bg-blue-500 text-white font-semibold py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-800 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M12 5l7 7-7 7"/></svg>
            Нэвтрэх
          </button>
        </div>

        <div class="pt-2">
          <div class="flex items-center gap-3 text-sm text-slate-400">
            <span class="flex-1 h-px bg-slate-200 dark:bg-slate-800/60"></span>
            <span class="whitespace-nowrap">эсвэл үргэлжлүүлэх</span>
            <span class="flex-1 h-px bg-slate-200 dark:bg-slate-800/60"></span>
          </div>

          <div class="mt-3 grid grid-cols-3 gap-3">
            <!-- Social buttons: replace href with oauth routes -->
            <a href="{{ route('social.redirect', 'facebook') }}" class="icon-btn w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-100 text-sm hover:shadow-sm transition" aria-label="Facebook-ээр нэвтрэх">
              <!-- Facebook SVG -->
              <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M22 12a10 10 0 10-11.5 9.9v-7H8v-3h2.5V9.5c0-2.5 1.5-3.9 3.8-3.9 1.1 0 2.3.2 2.3.2v2.5h-1.3c-1.3 0-1.7.8-1.7 1.6V12H22v0z"/></svg>
            </a>

            <a href="{{ route('social.redirect', 'twitter') }}" class="icon-btn w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-100 text-sm hover:shadow-sm transition" aria-label="Twitter-ээр нэвтрэх">
              <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M22 5.8c-.6.3-1.2.5-1.9.6.7-.4 1.3-1 1.6-1.8-.7.4-1.5.7-2.3.9C18.9 4.6 17.9 4 16.7 4c-1.8 0-3.2 1.6-2.8 3.3C11 7 8.7 5.6 7 3.6c-.8 1.4-.3 3.2 1.1 4.1-.5 0-1-.2-1.4-.4v.1c0 1.7 1.2 3.2 2.9 3.5-.4.1-.9.2-1.4.1.4 1.2 1.7 2 3.1 2-1.1.9-2.4 1.3-3.8 1.3-.2 0-.5 0-.7-.1 1.3.8 2.8 1.2 4.3 1.2 5.1 0 7.9-4.2 7.9-7.9v-.4c.6-.4 1.1-1 1.5-1.6z"/></svg>
            </a>

            <a href="{{ route('social.redirect', 'google') }}" class="icon-btn w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-100 text-sm hover:shadow-sm transition" aria-label="Google-ээр нэвтрэх">
              <svg class="h-5 w-5" viewBox="0 0 24 24" aria-hidden="true"><path d="M21.6 12.23c0-.7-.06-1.38-.18-2.03H12v3.84h5.42c-.23 1.25-.93 2.3-1.99 3.02v2.5h3.22c1.88-1.73 2.95-4.28 2.95-7.33z" fill="#4285F4"/><path d="M12 22c2.7 0 4.97-.9 6.63-2.43l-3.22-2.5c-.9.6-2.03.96-3.41.96-2.62 0-4.84-1.76-5.63-4.12H2.99v2.6C4.64 19.98 8.07 22 12 22z" fill="#34A853"/><path d="M6.37 13.91A6.01 6.01 0 016 12c0-.64.11-1.27.37-1.91V7.49H2.99A9.99 9.99 0 002 12c0 1.6.37 3.12 1.05 4.51l2.32-2.6z" fill="#FBBC05"/><path d="M12 6.48c1.47 0 2.8.51 3.84 1.51l2.87-2.87C16.96 3.55 14.68 2.6 12 2.6 8.07 2.6 4.64 4.62 2.99 7.49l3.38 2.6C7.16 8.24 9.38 6.48 12 6.48z" fill="#EA4335"/></svg>
            </a>
          </div>
        </div>
      </form>
    </div>

    <p class="mt-6 text-center text-xs text-slate-400">© {{ date('Y') }} Mbook. Бүх эрх хуулиар хамгаалагдсан.</p>
  </div>
</main>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    // animation entrance
    const card = document.getElementById('auth-card');

    // Password show/hide
    const pw = document.getElementById('password');
    const toggle = document.getElementById('toggle-password');
    const eyeOpen = document.getElementById('eye-open');
    const eyeClosed = document.getElementById('eye-closed');

    if (toggle && pw) {
      toggle.addEventListener('click', () => {
        const isPassword = pw.getAttribute('type') === 'password';
        pw.setAttribute('type', isPassword ? 'text' : 'password');
        toggle.setAttribute('aria-pressed', String(isPassword));
        eyeOpen.classList.toggle('hidden');
        eyeClosed.classList.toggle('hidden');
      });
    }

    // Handle transition link navigation (same behavior as original)
    document.querySelectorAll('a[data-transition]').forEach((a) => {
      a.addEventListener('click', (e) => {
        const url = a.getAttribute('href');
        if (!url || url.startsWith('#')) return;
        e.preventDefault();
        if (!card) { window.location.href = url; return; }
        card.classList.remove('auth-enter');
        card.classList.add('auth-exit');
        card.addEventListener('animationend', () => window.location.href = url, { once: true });
      });
    });
  });
</script>

@include('include.footer')