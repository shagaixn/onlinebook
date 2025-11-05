<!DOCTYPE html>
<html lang="mn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mbook</title>

    <!-- Tailwind config: dark mode via class -->
    <script>
      tailwind = window.tailwind || {};
      tailwind.config = { darkMode: 'class' };
    </script>

    <!-- No-flash: эхний буулгалтаас өмнө theme-г тогтооно -->
    <script>
      (function () {
        try {
          const saved = localStorage.getItem('theme'); // 'dark' | 'light' | null
          const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
          if (saved === 'dark' || (!saved && prefersDark)) {
            document.documentElement.classList.add('dark');
          } else {
            document.documentElement.classList.remove('dark');
          }
        } catch (_) {}
      })();
    </script>

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Fonts / libs -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <script src="https://instant.page/5.2.0" type="module"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>

    <style>
      
body {
  background: #f6f9ff;
  color: #23272f;
}
header, footer {
  background: #fff;
  color: #23272f;
}
.search-input {
  background: #fff;
  color: #374151;
  border: 1px solid #d1d5db;
}

body.dark {
  background: #0f172a;
  color: #e5e7eb;
}
body.dark header,
body.dark footer {
  background: #1e293b;
  color: #e5e7eb;
}
body.dark .search-input {
  background: #1e293b;
  color: #e5e7eb;
  border-color: #334155;
}
body.dark .nav-link {
  color: #94a3b8;
}
body.dark .nav-link:hover {
  background: #334155;
  color: #60a5fa;
}
      #testimonials .controls button {
        background: #007bff;
        border: none;
        color: #fff;
        padding: 10px 16px;
        margin: 0 5px;
        border-radius: 8px;
        cursor: pointer;
      }
      .search-input {
        width: 18rem;
        padding: 0.5rem 1rem 0.5rem 2.5rem;
        border: 1px solid #d1d5db;
        border-radius: 9999px;
        color: #374151;
        outline: none;
        transition: border-color 0.2s;
        font-size: 1rem;
        background: #fff;
      }
      .search-input:focus {
        border-color: #3b82f6;
        box-shadow: none;
      }

      /* Dark mode-д хайлтын талбар */
      .dark .search-input {
        background: #0f172a; /* slate-900 */
        border-color: #334155; /* slate-700 */
        color: #e5e7eb; /* gray-200 */
      }
      .dark .search-input::placeholder {
        color: #94a3b8; /* slate-400 */
      }

      /* Page transition */
      @media (prefers-color-scheme: dark) {
  body {
    background: #0f172a;
    color: #e5e7eb;
  }
  header, footer {
    background: #1e293b;
    color: #e5e7eb;
  }
  /* бусад элементүүд... */
}
body.dark {
  background: #0f172a;
  color: #e5e7eb;
}
body.dark header,
body.dark footer {
  background: #1e293b;
  color: #e5e7eb;
}  --
body.pt {
  transition: background-color 0.3s, color 0.3s;
}
header {
  background-image: url('/images/bookshelves.jpg');
  background-repeat: repeat;
  background-size: auto;
  background-position: center;
}
body.dark header {
  background-image: url('/images/bookshelves.jpg');
  filter: brightness(0.7);
}

    </style>
</head>
<body class="bg-gradient-to-b from-[#f6f9ff] to-white dark:from-slate-950 dark:to-slate-900 min-h-screen text-slate-800 dark:text-slate-100">
  
  <!-- Header -->
  <header class="dark:bg-slate-900 border-b border-gray-100 dark:border-slate-800 px-0 pt-4 pb-2">
    <div class="max-w-7xl mx-auto flex items-center justify-between px-8">
      <div class="flex items-center gap-2">
        <a href="pages.home"><span class="text-2xl font-bold text-blue-600">M</span>
        <span class="text-xl font-semibold">book</span></a>
      </div>

        <div class="flex items-center gap-4">
          <div class="relative" style="width: 18rem;">
            <input
              type="text"
              class="search-input pr-10 h-10"
              placeholder="Ном хайх..."
              autocomplete="off"
              >
            <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
            
          </div>

        <!-- Theme toggle -->
        <button id="dark-toggle" aria-label="Theme солих">🌙/☀️</button>
<script>
  document.getElementById('dark-toggle').onclick = function() {
    document.body.classList.toggle('dark');
    // Хүсвэл localStorage-тай болгоорой
  }
</script>
          
          <svg class="w-5 h-5 hidden dark:inline-block" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
            <path d="M6.76 4.84l-1.8-1.79-1.41 1.41 1.79 1.8 1.42-1.42zM1 13h3v-2H1v2zm10 10h2v-3h-2v3zm9.04-3.95l1.79 1.8 1.41-1.41-1.8-1.79-1.4 1.4zM20 11v2h3v-2h-3zM4.22 19.78l1.41 1.41 1.8-1.79-1.41-1.41-1.8 1.79zM13 1h-2v3h2V1zm5.66 4.05l1.8-1.79-1.41-1.41-1.79 1.8 1.4 1.4zM12 6a6 6 0 100 12 6 6 0 000-12z"></path>
          </svg>
        </button>

        @auth
          <a href="" class="text-blue-600 font-semibold flex items-center gap-2">
            <i class="fa-regular fa-user text-blue-500"></i>
            Профайл
          </a>

          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button
              type="submit"
              class="ml-2 p-2 rounded-full bg-transparent text-red-600 hover:bg-red-50 dark:hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-red-300"
              aria-label="Гарах"
              title="Гарах"
            >
              <!-- Heroicons: ArrowRightOnRectangle (Logout) -->
              <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6A2.25 2.25 0 005.25 5.25v13.5A2.25 2.25 0 007.5 21h6A2.25 2.25 0 0015.75 18.75V15" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 9l3 3m0 0l-3 3m3-3H3" />
              </svg>
            </button>
          </form>
        @else
          <a href="{{ route('login') }}" class="text-blue-600 font-semibold flex items-center gap-2">
            <i class="fa-solid fa-right-to-bracket text-blue-500"></i>
            Нэвтрэх
          </a>
        @endauth
      </div>
    </div>

    <nav class="max-w-7xl mx-auto px-8 mt-4 flex items-center gap-8 text-gray-700 dark:text-gray-300 font-medium">
      <a href="{{ route('home') }}" class="nav-link px-2 py-1 rounded-full transition-colors hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-slate-800 dark:hover:text-blue-400 text-blue-600 dark:text-blue-400 font-semibold">Танд зориулав</a>
      <a href="{{ route('book') }}" class="nav-link px-2 py-1 rounded-full transition-colors hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-slate-800 dark:hover:text-blue-400">Ном</a>
      <a href="{{ route('subscription') }}" class="nav-link px-2 py-1 rounded-full transition-colors hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-slate-800 dark:hover:text-blue-400">Subscription</a>
      <a href="" class="nav-link px-2 py-1 rounded-full transition-colors hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-slate-800 dark:hover:text-blue-400">Зохиолчид</a>
    </nav>

    <!-- Page transition JS -->
    <script>
      (function () {
        document.addEventListener('DOMContentLoaded', function () {
          document.body.classList.add('pt');
          requestAnimationFrame(function () {
            document.body.classList.add('pt-enter');
          });
        });

        document.addEventListener('click', function (e) {
          const a = e.target.closest('a[href]');
          if (!a) return;
          const href = a.getAttribute('href') || '';

          if (
            href.startsWith('#') ||
            a.target ||
            a.hasAttribute('download') ||
            a.rel === 'external' ||
            (a.origin && a.origin !== location.origin) ||
            a.dataset.noTransition !== undefined
          ) return;

          e.preventDefault();
          document.body.classList.remove('pt-enter');
          document.body.classList.add('pt-leave');

          const go = () => { window.location.href = a.href; };
          document.body.addEventListener('transitionend', go, { once: true });
          setTimeout(go, 160);
        }, true);
      })();
    </script>

    <!-- Theme toggle JS -->
    <script>
      (function () {
        const btn = document.getElementById('theme-toggle');
        if (!btn) return;

        function setTheme(mode) {
          if (mode === 'dark') {
            document.documentElement.classList.add('dark');
            localStorage.setItem('theme', 'dark');
          } else {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('theme', 'light');
          }
        }

        btn.addEventListener('click', function () {
          const isDark = document.documentElement.classList.contains('dark');
          setTheme(isDark ? 'light' : 'dark');
        });

        // Хэрэв хэрэглэгч сонголт хадгалаагүй бол системийн өөрчлөлтийг дагана
        const mql = window.matchMedia('(prefers-color-scheme: dark)');
        if (mql.addEventListener) {
          mql.addEventListener('change', (e) => {
            const saved = localStorage.getItem('theme');
            if (!saved) setTheme(e.matches ? 'dark' : 'light');
          });
        } else if (mql.addListener) {
          // legacy Safari
          mql.addListener((e) => {
            const saved = localStorage.getItem('theme');
            if (!saved) setTheme(e.matches ? 'dark' : 'light');
          });
        }
      })();
    </script>
    <script>
(function () {
  try {
    const saved = localStorage.getItem('theme');
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    if (saved === 'dark' || (!saved && prefersDark)) {
      document.documentElement.classList.add('dark');
    } else {
      document.documentElement.classList.remove('dark');
    }
  } catch (_) {}
})();
</script>
  </header>
</body>
</html>