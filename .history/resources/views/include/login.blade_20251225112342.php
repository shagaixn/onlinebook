@include('include.header')

<style>
    /* Page-to-page (login/register) card transition */
    @keyframes auth-in { from { opacity: 0; transform: translateY(16px) scale(.98) } to { opacity: 1; transform: none } }
    @keyframes auth-out { from { opacity: 1; transform: none } to { opacity: 0; transform: translateY(-16px) scale(.98) } }
    .auth-enter { animation: auth-in .35s ease-out both; }
    .auth-exit { animation: auth-out .25s ease-in both; }
    @media (prefers-reduced-motion: reduce) {
        .auth-enter, .auth-exit { animation: none; }
    }
</style>

<section class=" night-sky relative min-h-[80vh] flex items-center justify-center px-4 py-12">
    <!-- soft background accents -->
    <div aria-hidden="true" class="pointer-events-none absolute inset-0 -z-10 overflow-hidden">
        <div class="absolute -top-28 -left-20 h-72 w-72 rounded-full bg-blue-400/15 blur-3xl"></div>
        <div class="absolute -bottom-24 -right-16 h-72 w-72 rounded-full bg-indigo-400/15 blur-3xl"></div>
    </div>

    <div id="auth-card" class="w-full max-w-md bg-dark dark:bg-slate-900/70 backdrop-blur-xl p-8 rounded-2xl shadow-xl transition-colors duration-200 border border-slate-200/70 dark:border-slate-700/70">
        <div class="text-center mb-6">
            <h2 class="text-2xl font-bold mb-6 text-center">Нэвтрэх</h2>
            <p class="mt-2 text-sm text-slate-900 dark:text-slate-400">Book Plus-д дахин тавтай морил!</p>
        </div>
        <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf
                <div>
                        <label for="email" class="block mb-1 font-medium bg-dark text-slate-800 dark:text-gray-200">Имэйл</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-slate-400 flex items-center justify-center">
                                <!-- Email Icon SVG (centered using flex) -->
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true" class="h-5 w-5">
                                    <path d="M3 7l9 6 9-6"/>
                                    <rect x="3" y="5" width="18" height="14" rx="2" ry="2"/>
                                </svg>
                            </span>
                            <input type="email" name="email" id="email" placeholder="name@example.com" autocomplete="username"
                                     class="w-full border rounded-lg pl-10 pr-3 py-2.5 bg-white/90 dark:bg-slate-800 border-gray-300 dark:border-slate-700 text-slate-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-800 transition-colors"
                                     required autofocus>
                        </div>
            @error('email')
                <div class="text-red-500 text-sm dark:text-red-400">{{ $message }}</div>
            @enderror
        </div>
                <div>
                        <label for="password" class="block mb-1 font-medium text-slate-800 dark:text-gray-200">Нууц үг</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-slate-400 flex items-center justify-center">
                                <!-- Password Icon SVG (centered using flex) -->
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true" class="h-5 w-5">
                                    <path d="M12 17a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/>
                                    <path d="M19 11V7a7 7 0 1 0-14 0v4"/>
                                    <rect x="5" y="11" width="14" height="10" rx="2"/>
                                </svg>
                            </span>
                            <input type="password" name="password" id="password" placeholder="••••••••" autocomplete="current-password"
                                     class="w-full border rounded-lg pl-10 pr-10 py-2.5 bg-white/90 dark:bg-slate-800 border-gray-300 dark:border-slate-700 text-slate-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-800 transition-colors"
                                     required>
                            <button type="button" id="togglePassword" class="absolute right-2.5 top-1/2 -translate-y-1/2 p-2 text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 flex items-center justify-center" aria-label="Нууц үг харах/нуух">
                                <!-- Eye open/close SVG; will be swapped dynamically -->
                                <svg id="eyeIcon" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                            </button>
                        </div>
            @error('password')
                <div class="text-red-500 text-sm dark:text-red-400">{{ $message }}</div>
            @enderror
        </div>
                <div class="flex items-center justify-between pt-2">
                    <label class="inline-flex items-center gap-2 text-sm text-slate-600 dark:text-slate-300 select-none">
                        <input type="checkbox" name="remember" class="h-4 w-4 rounded border-slate-300 text-blue-600 dark:bg-slate-800 dark:border-slate-700 focus:ring-blue-500">
                        Намайг сана
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">Нууц үг мартсан?</a>
                    @endif
                </div>

                <button type="submit" class="mt-2 inline-flex items-center justify-center bg-gradient-to-r from-blue-600 to-indigo-600 w-full py-2.5 text-white rounded-lg font-semibold shadow-lg shadow-blue-500/20 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">Нэвтрэх</button>

                <div class="text-center">
                    <span class="text-sm text-slate-500 dark:text-slate-400">Эсвэл</span>
                </div>
                <div class="text-center -mt-2">
                    <a href="{{ route('register') }}" data-transition class="text-blue-600 dark:text-blue-400 hover:underline font-medium">Бүртгүүлэх</a>
                </div>
    </form>
        <div class="mt-8 flex justify-center gap-4">
    <!-- Facebook -->
        <a href="#" class="group relative w-12 h-12 flex items-center justify-center rounded-xl bg-white/70 dark:bg-slate-800/70 border border-slate-200/70 dark:border-slate-700/70 backdrop-blur hover:scale-105 transition-transform">
                <span class="absolute inset-0 rounded-xl ring-1 ring-blue-500/40 group-hover:ring-blue-500/70 transition"></span>
                <span class="z-10 flex items-center justify-center h-full w-full">
                    <!-- Use raw SVG for best placement/compatibility -->
                    <svg viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5 text-blue-600 dark:text-blue-400">
                        <path d="M22 12c0-5.522-4.478-10-10-10S2 6.478 2 12c0 4.991 3.657 9.128 8.438 9.88v-6.99h-2.54v-2.89h2.54V9.797c0-2.507 1.493-3.89 3.793-3.89 1.1 0 2.246.195 2.246.195v2.48h-1.267c-1.249 0-1.638.773-1.638 1.563v1.881h2.787l-.446 2.89h-2.341v6.99C18.343 21.128 22 16.991 22 12"/>
                    </svg>
                </span>
        </a>
    <!-- Twitter -->
        <a href="#" class="group relative w-12 h-12 flex items-center justify-center rounded-xl bg-white/70 dark:bg-slate-800/70 border border-slate-200/70 dark:border-slate-700/70 backdrop-blur hover:scale-105 transition-transform">
                <span class="absolute inset-0 rounded-xl ring-1 ring-sky-400/40 group-hover:ring-sky-400/70 transition"></span>
                <span class="z-10 flex items-center justify-center h-full w-full">
                    <svg viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5 text-sky-500 dark:text-sky-300">
                        <path d="M22.46 6c-.77.35-1.6.59-2.47.69a4.34 4.34 0 001.93-2.41 8.53 8.53 0 01-2.72 1.04 4.28 4.28 0 00-7.3 3.9A12.13 12.13 0 013 4.89a4.22 4.22 0 001.32 5.71c-.72-.02-1.4-.22-1.99-.56v.06c0 2.28 1.61 4.19 3.96 4.64-.39.11-.81.16-1.24.16-.3 0-.6-.03-.88-.08a4.29 4.29 0 004 2.98A8.6 8.6 0 013 19.54c-.58 0-1.15-.03-1.71-.1A12.14 12.14 0 008.29 21c7.35 0 11.37-6.09 11.37-11.38 0-.17 0-.35-.01-.52A8.18 8.18 0 0024 4.59a8.15 8.15 0 01-2.54.7z"/>
                    </svg>
                </span>
        </a>
    <!-- Instagram -->
        <a href="#" class="group relative w-12 h-12 flex items-center justify-center rounded-xl bg-white/70 dark:bg-slate-800/70 border border-slate-200/70 dark:border-slate-700/70 backdrop-blur hover:scale-105 transition-transform">
                <span class="absolute inset-0 rounded-xl ring-1 ring-pink-500/40 group-hover:ring-pink-500/70 transition"></span>
                <span class="z-10 flex items-center justify-center h-full w-full">
                    <svg viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5 text-pink-500 dark:text-pink-400">
                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07a6.518 6.518 0 014.675 4.676c.058 1.265.069 1.645.069 4.847s-.011 3.583-.069 4.849a6.517 6.517 0 01-4.675 4.675c-1.266.058-1.646.069-4.85.069s-3.583-.011-4.849-.069a6.517 6.517 0 01-4.676-4.675c-.057-1.266-.068-1.646-.068-4.849s.011-3.583.069-4.849A6.517 6.517 0 017.15 2.233C8.416 2.176 8.796 2.163 12 2.163z"/>
                        <path d="M12 5.838a6.163 6.163 0 106.162 6.162A6.162 6.162 0 0012 5.838zm0 10.162a3.999 3.999 0 113.999-3.999A4 4 0 0112 16z"/>
                        <circle cx="18.406" cy="5.594" r="1.44"/>
                    </svg>
                </span>
        </a>
</div>

    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const card = document.getElementById('auth-card');
        if (card) requestAnimationFrame(() => card.classList.add('auth-enter'));

        document.querySelectorAll('a[data-transition]').forEach((a) => {
            a.addEventListener('click', (e) => {
                const url = a.getAttribute('href');
                if (!url || url.startsWith('#')) return; // let hash links pass
                e.preventDefault();
                if (!card) { window.location.href = url; return; }
                card.classList.remove('auth-enter');
                card.classList.add('auth-exit');
                card.addEventListener('animationend', () => window.location.href = url, { once: true });
            });
        });

        // Password show/hide
        const pwd = document.getElementById('password');
        const btn = document.getElementById('togglePassword');
        const icon = document.getElementById('eyeIcon');
        if (pwd && btn && icon) {
            btn.addEventListener('click', () => {
                const isHidden = pwd.getAttribute('type') === 'password';
                pwd.setAttribute('type', isHidden ? 'text' : 'password');
                icon.innerHTML = isHidden
                    ? '<path d="M3 3l18 18"/><path d="M2 12s3.5-7 10-7c2.1 0 3.9.6 5.4 1.4"/><path d="M22 12s-3.5 7-10 7c-2.1 0-3.9-.6-5.4-1.4"/>'
                    : '<path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/>';
            });
        }
    });
</script>

@include('include.footer')