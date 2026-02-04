@include('include.header')

<main class="night-sky min-h-[100svh] max-w-7xl mx-auto px-4 py-20">
  <div class="max-w-4xl mx-auto">
    <!-- Hero Section -->
    <div class="text-center mb-16">
      <h1 class="text-4xl sm:text-5xl font-extrabold mb-4 text-slate-900 dark:text-white">
        <span class="text-gradient drop-shadow-sm">Бидний тухай</span>
      </h1>
      <p class="text-xl text-slate-600 dark:text-slate-300 leading-relaxed">
        Мэдлэг, мэдрэмжийг чөлөөтэй түгээх платформ
      </p>
    </div>

    <!-- Mission Section -->
    <section class="mb-12 bg-white/50 dark:bg-white/5 backdrop-blur border border-gray-200 dark:border-white/10 rounded-2xl p-8 shadow-sm">
      <div class="flex items-start gap-4 mb-4">
        <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-full flex items-center justify-center">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
          </svg>
        </div>
        <div>
          <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-3">Бидний зорилго</h2>
          <p class="text-slate-700 dark:text-slate-300 leading-relaxed mb-4">
            Манай платформын гол зорилго бол <strong>хэрэглэгчдэд үнэгүй, хязгааргүй мэдлэг мэдрэмжийг номоор болон бусад бүтээлээр дамжуулан түгээх</strong> явдал юм.
          </p>
          <p class="text-slate-700 dark:text-slate-300 leading-relaxed">
            Бид хүн бүр санхүүгийн боломжоос үл хамааран чанартай контентод нэвтрэх эрхтэй гэдэгт итгэдэг. Иймд бидний бүх контент <span class="text-cyan-600 dark:text-cyan-400 font-semibold">100% үнэгүй</span> бөгөөд ямар нэгэн subscription эсвэл төлбөр шаарддаггүй.
          </p>
        </div>
      </div>
    </section>

    <!-- What We Offer -->
    <section class="mb-12">
      <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-6 text-center">Бид юу санал болгож байна</h2>
      <div class="grid md:grid-cols-2 gap-6">
        <!-- Books -->
        <article class="bg-white/50 dark:bg-white/5 backdrop-blur border border-gray-200 dark:border-white/10 rounded-xl p-6 shadow-sm hover:shadow-md transition">
          <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center mb-4">
            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
          </div>
          <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">Ном</h3>
          <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed">
            Олон төрөл жанрын электрон номууд. Уран зохиол, эрдэм шинжилгээ, өөрийгөө хөгжүүлэх болон бусад номууд - бүгдийг үнэгүй уншаарай.
          </p>
        </article>

        <!-- Podcasts -->
        <article class="bg-white/50 dark:bg-white/5 backdrop-blur border border-gray-200 dark:border-white/10 rounded-xl p-6 shadow-sm hover:shadow-md transition">
          <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center mb-4">
            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/>
            </svg>
          </div>
          <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">Podcast</h3>
          <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed">
            Сонирхолтой яриа, шинжээч, зохиолчдын ярилцлага. Аудио контентоор дамжуулан мэдлэгээ баяжуулаарай.
          </p>
        </article>

        <!-- Manga -->
        <article class="bg-white/50 dark:bg-white/5 backdrop-blur border border-gray-200 dark:border-white/10 rounded-xl p-6 shadow-sm hover:shadow-md transition">
          <div class="w-10 h-10 bg-pink-100 dark:bg-pink-900/30 rounded-lg flex items-center justify-center mb-4">
            <svg class="w-6 h-6 text-pink-600 dark:text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
          </div>
          <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">Manga</h3>
          <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed">
            Япон манга болон комиксууд. Зураг дүрслэлээр дамжуулсан үлгэрүүдийг чөлөөтэй уншаарай.
          </p>
        </article>

        <!-- Authors -->
        <article class="bg-white/50 dark:bg-white/5 backdrop-blur border border-gray-200 dark:border-white/10 rounded-xl p-6 shadow-sm hover:shadow-md transition">
          <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center mb-4">
            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
          </div>
          <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">Зохиолчид</h3>
          <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed">
            Олон улсын болон дотоодын зохиолчдын бүтээлүүдтэй танилцаж, тэдний тухай дэлгэрэнгүй мэдээлэл аваарай.
          </p>
        </article>
      </div>
    </section>

    <!-- Values Section -->
    <section class="mb-12 bg-gradient-to-br from-cyan-50 to-blue-50 dark:from-slate-800/50 dark:to-slate-900/50 border border-cyan-200 dark:border-cyan-800/30 rounded-2xl p-8">
      <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-6 text-center">Бидний үнэт зүйлс</h2>
      <div class="grid md:grid-cols-3 gap-6">
        <div class="text-center">
          <div class="w-16 h-16 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
          </div>
          <h3 class="font-bold text-slate-900 dark:text-white mb-2">100% Үнэгүй</h3>
          <p class="text-sm text-slate-600 dark:text-slate-400">
            Ямар нэгэн нууц төлбөр, subscription, эсвэл хязгаарлалт байхгүй
          </p>
        </div>
        <div class="text-center">
          <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-600 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
          </div>
          <h3 class="font-bold text-slate-900 dark:text-white mb-2">Чанартай контент</h3>
          <p class="text-sm text-slate-600 dark:text-slate-400">
            Сонгосон, шалгагдсан, чанартай ном болон бусад контент
          </p>
        </div>
        <div class="text-center">
          <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-teal-600 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
          </div>
          <h3 class="font-bold text-slate-900 dark:text-white mb-2">Хүртээмжтэй</h3>
          <p class="text-sm text-slate-600 dark:text-slate-400">
            Хаанаас ч, ямар төхөөрөмжөөс ч нэвтэрч уншаарай
          </p>
        </div>
      </div>
    </section
  </div>
</main>

@include('include.footer')
