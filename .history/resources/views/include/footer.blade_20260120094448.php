<!-- FOOTER -->
<footer class="site-footer relative mt-24 border-t border-gray-200 dark:border-white/10 bg-dark dark:bg-[#0f172a] text-slate-600 dark:text-slate-300 overflow-hidden">
  <!-- Background Glow -->
  <div class="absolute top-0 left-1/4 w-96 h-96 bg-blue-600/10 rounded-full blur-3xl -translate-y-1/2 pointer-events-none"></div>
  <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-purple-600/10 rounded-full blur-3xl translate-y-1/2 pointer-events-none"></div>

  <div class="relative max-w-7xl mx-auto px-6 py-16 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10 text-sm">

    <!-- Column 1: Logo & description -->
    <div class="lg:col-span-2">
      <div class="flex items-center gap-2 mb-6">
        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-cyan-400 flex items-center justify-center text-white  font-bold text-xl shadow-lg shadow-blue-500/20">
          B
        </div>
        <span class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">Book Plus</span>
      </div>
      <p class="text-slate-600 dark:text-slate-400 mb-6 leading-relaxed max-w-sm">
        Цахим ном, Аудио ном, Подкастын цогц платформ. <br>
        <span class="text-slate-800 dark:text-slate-300 font-medium">#Мэдрэмж, Мэдлэгийг өнгөлнө.</span>
      </p>
      
      <div class="flex gap-4">
        <a href="https://www.facebook.com/" class="w-10 h-10 rounded-full bg-slate-100 dark:bg-white/5 text-slate-500 dark:text-slate-400 hover:bg-blue-600 hover:text-white flex items-center justify-center transition-all duration-300" aria-label="Facebook">
          <i class="fab fa-facebook-f"></i>
        </a>
        <a href="https://www.instagram.com/portgas_d_shagai/" class="w-10 h-10 rounded-full bg-slate-100 dark:bg-white/5 text-slate-500 dark:text-slate-400 hover:bg-pink-600 hover:text-white flex items-center justify-center transition-all duration-300" aria-label="Instagram">
          <i class="fab fa-instagram"></i>
        </a>
        <a href="#" class="w-10 h-10 rounded-full bg-slate-100 dark:bg-white/5 text-slate-500 dark:text-slate-400 hover:bg-red-600 hover:text-white flex items-center justify-center transition-all duration-300" aria-label="YouTube">
          <i class="fab fa-youtube"></i>
        </a>
        <a href="#" class="w-10 h-10 rounded-full bg-slate-100 dark:bg-white/5 text-slate-500 dark:text-slate-400 hover:bg-blue-500 hover:text-white flex items-center justify-center transition-all duration-300" aria-label="LinkedIn">
          <i class="fab fa-linkedin-in"></i>
        </a>
      </div>
    </div>

    <!-- Column 2: Бүтээл нийтлэх -->
    <div>
      <h3 class="font-bold text-slate-900 dark:text-white text-lg mb-6">Бүтээл нийтлэх</h3>
      @if(auth()->check() && auth()->user()->role === 'admin')
          <a href="{{ route('admin.dashboard') }}"
             class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-600 to-blue-500 text-white py-2.5 px-5 rounded-xl hover:shadow-lg hover:shadow-blue-500/25 hover:-translate-y-0.5 transition-all duration-300 font-medium mb-4 w-full justify-center">
              <span>Бүтээл нийтлэх</span>
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
          </a>
      @else
          <button
              class="inline-flex items-center gap-2 bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-400 py-2.5 px-5 rounded-xl cursor-not-allowed font-medium mb-4 w-full justify-center border border-gray-200 dark:border-white/5"
              type="button"
              disabled
          >
              <span>Бүтээл нийтлэх</span>
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
          </button>
      @endif
      <p class="text-xs text-slate-500 dark:text-slate-500 leading-relaxed">
        Таны нийтэлсэн бүтээлийг уншигч, сонсогчдод хил хязгааргүй хүргэнэ.
      </p>
    </div>

    <!-- Column 3: Бидний тухай -->
    <div>
      <h3 class="font-bold text-slate-900 dark:text-white text-lg mb-6">Бидний тухай</h3>
      <ul class="space-y-3">
        <li><a href="#" class="hover:text-blue-600 dark:hover:text-cyan-400 transition-colors duration-200 flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-slate-400 dark:bg-slate-600"></span> Танилцуулга</a></li>
        <li><a href="#" class="hover:text-blue-600 dark:hover:text-cyan-400 transition-colors duration-200 flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-slate-400 dark:bg-slate-600"></span> Хамтран ажиллах</a></li>
        <li><a href="{{ route('authors.index') }}" class="hover:text-blue-600 dark:hover:text-cyan-400 transition-colors duration-200 flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-slate-400 dark:bg-slate-600"></span> Зохиолчид</a></li>
      </ul>
    </div>

    <!-- Column 4: Тусламж & Холбоо барих -->
    <div>
      <h3 class="font-bold text-slate-900 dark:text-white text-lg mb-6">Тусламж</h3>
      <ul class="space-y-3 mb-8">
        <li><a href="#" class="hover:text-blue-600 dark:hover:text-cyan-400 transition-colors duration-200 flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-slate-400 dark:bg-slate-600"></span> Түгээмэл асуултууд</a></li>
        <li><a href="#" class="hover:text-blue-600 dark:hover:text-cyan-400 transition-colors duration-200 flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-slate-400 dark:bg-slate-600"></span> Холбоо барих</a></li>
      </ul>
      
      <a href="mailto:support@bookplus.mn" class="inline-flex items-center gap-2 text-slate-600 dark:text-slate-400 hover:text-blue-600 dark:hover:text-white transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
        support@bookplus.mn
      </a>
    </div>
  </div>

  
  </div>
</footer>
</body>
</html>