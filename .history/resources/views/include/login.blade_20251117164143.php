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

<section class="relative min-h-[80vh] flex items-center justify-center px-4 py-12 bg-dark from-slate-50 to-indigo-50 dark:from-slate-950 dark:to-slate-900">
    <!-- soft background accents -->
    <div aria-hidden="true" class="pointer-events-none absolute inset-0 -z-10 overflow-hidden">
        <div class="absolute -top-28 -left-20 h-72 w-72 rounded-full bg-blue-400/15 blur-3xl"></div>
        <div class="absolute -bottom-24 -right-16 h-72 w-72 rounded-full bg-indigo-400/15 blur-3xl"></div>
    </div>

    <div id="auth-card" class="w-full max-w-md bg-dark dark:bg-slate-900/70 backdrop-blur-xl p-8 rounded-2xl shadow-xl transition-colors duration-200 border border-slate-200/70 dark:border-slate-700/70">
        <div class="text-center mb-6">
            <h2 class="text-3xl font-extrabold tracking-tight text-slate-400 dark:text-white">Нэвтрэх</h2>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Mbook-д дахин тавтай морил!</p>
        </div>
        <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf
                <div>
                        <label for="email" class="block mb-1 font-medium text-slate-800 dark:text-gray-200">Имэйл</label>
                        <div class="relative">
                            <svg class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M3 7l9 6 9-6"/><rect x="3" y="5" width="18" height="14" rx="2" ry="2"/></svg>
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
                            <svg class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M12 17a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/><path d="M19 11V7a7 7 0 1 0-14 0v4"/><rect x="5" y="11" width="14" height="10" rx="2"/></svg>
                            <input type="password" name="password" id="password" placeholder="••••••••" autocomplete="current-password"
                                     class="w-full border rounded-lg pl-10 pr-10 py-2.5 bg-white/90 dark:bg-slate-800 border-gray-300 dark:border-slate-700 text-slate-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-800 transition-colors"
                                     required>
                            <button type="button" id="togglePassword" class="absolute right-2.5 top-1/2 -translate-y-1/2 p-2 text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200" aria-label="Нууц үг харах/нуух">
                                <svg id="eyeIcon" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/></svg>
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
                <span class="text-blue-600 dark:text-blue-400 text-xl z-10"><i class="fab fa-facebook-f"></i></span>
    </a>
    <!-- Twitter -->
        <a href="#" class="group relative w-12 h-12 flex items-center justify-center rounded-xl bg-white/70 dark:bg-slate-800/70 border border-slate-200/70 dark:border-slate-700/70 backdrop-blur hover:scale-105 transition-transform">
                <span class="absolute inset-0 rounded-xl ring-1 ring-sky-400/40 group-hover:ring-sky-400/70 transition"></span>
                <span class="text-sky-500 dark:text-sky-300 text-xl z-10"><i class="fab fa-twitter"></i></span>
    </a>
    <!-- Instagram -->
        <a href="#" class="group relative w-12 h-12 flex items-center justify-center rounded-xl bg-white/70 dark:bg-slate-800/70 border border-slate-200/70 dark:border-slate-700/70 backdrop-blur hover:scale-105 transition-transform">
                <span class="absolute inset-0 rounded-xl ring-1 ring-pink-500/40 group-hover:ring-pink-500/70 transition"></span>
                <span class="text-pink-500 dark:text-pink-400 text-xl z-10"><i class="fab fa-instagram"></i></span>
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