
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
    <div class="relative flex items-center">
        <span class="absolute left-3 top-1/2 -translate-y-1/2 flex items-center h-5">
            <svg class="pointer-events-none h-5 w-5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
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
    <div class="relative flex items-center">
        <span class="absolute left-3 top-1/2 -translate-y-1/2 flex items-center h-5">
            <svg class="pointer-events-none h-5 w-5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                <path d="M12 17a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/>
                <path d="M19 11V7a7 7 0 1 0-14 0v4"/>
                <rect x="5" y="11" width="14" height="10" rx="2"/>
            </svg>
        </span>
        <input type="password" name="password" id="password" placeholder="••••••••" autocomplete="current-password"
            class="w-full border rounded-lg pl-10 pr-10 py-2.5 bg-white/90 dark:bg-slate-800 border-gray-300 dark:border-slate-700 text-slate-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-800 transition-colors"
            required>
        <button type="button" id="togglePassword" class="absolute right-2.5 top-1/2 -translate-y-1/2 flex items-center p-2 text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200" aria-label="Нууц үг харах/нуух">
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
                <span class="text-blue-600 dark:text-blue-400 z-10">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd"></path></svg>
                </span>
    </a>
    <!-- Twitter -->
        <a href="#" class="group relative w-12 h-12 flex items-center justify-center rounded-xl bg-white/70 dark:bg-slate-800/70 border border-slate-200/70 dark:border-slate-700/70 backdrop-blur hover:scale-105 transition-transform">
                <span class="absolute inset-0 rounded-xl ring-1 ring-sky-400/40 group-hover:ring-sky-400/70 transition"></span>
                <span class="text-sky-500 dark:text-sky-300 z-10">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"></path></svg>
                </span>
    </a>
    <!-- Instagram -->
        <a href="#" class="group relative w-12 h-12 flex items-center justify-center rounded-xl bg-white/70 dark:bg-slate-800/70 border border-slate-200/70 dark:border-slate-700/70 backdrop-blur hover:scale-105 transition-transform">
                <span class="absolute inset-0 rounded-xl ring-1 ring-pink-500/40 group-hover:ring-pink-500/70 transition"></span>
                <span class="text-pink-500 dark:text-pink-400 z-10">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.468 2.373c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd"></path></svg>
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