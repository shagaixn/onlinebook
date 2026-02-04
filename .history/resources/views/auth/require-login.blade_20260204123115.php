@include('include.header')

<div class="min-h-screen bg-dark-50 night-sky dark:bg-[#0f172a] text-slate-900 dark:text-white pt-24 pb-12 relative overflow-hidden flex items-center justify-center">
    <div class="w-full max-w-2xl px-4 relative z-10">
        <div class="bg-dark dark:bg-slate-800/50 backdrop-blur-xl border border-gray-200 dark:border-slate-700 rounded-2xl p-12 shadow-2xl text-center">
            <!-- Icon -->
            <div class="mx-auto w-20 h-20 bg-yellow-500/10 rounded-full flex items-center justify-center mb-6">
                <svg class="w-10 h-10 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
            </div>

            <!-- Message -->
            <h1 class="text-3xl font-bold text-white mb-4">
                Уучлаарай!
            </h1>
            <p class="text-xl text-slate-300 dark:text-slate-400 mb-8">
                Та эхлээд бүртгэлээ баталгаажуулна уу
            </p>

            <p class="text-slate-400 dark:text-slate-500 mb-8 max-w-md mx-auto">
                Энэ хуудсыг үзэхийн тулд танд бүртгэлтэй байх шаардлагатай. Хэрэв танд бүртгэл байгаа бол нэвтэрнэ үү, эсвэл шинээр бүртгүүлнэ үү.
            </p>

            <!-- Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="{{ route('login') }}" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white text-lg font-bold rounded-xl shadow-lg shadow-blue-900/20 transition-all hover:-translate-y-0.5">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                    </svg>
                    Нэвтрэх хэсэгт очих
                </a>

                <a href="{{ route('register') }}" class="inline-flex items-center px-8 py-4 bg-slate-700 hover:bg-slate-600 border border-slate-600 text-white text-lg font-bold rounded-xl transition-all hover:-translate-y-0.5">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                    Шинээр бүртгүүлэх
                </a>
            </div>

            <!-- Back to home -->
            <div class="mt-8">
                <a href="{{ route('home') }}" class="text-slate-400 hover:text-slate-300 text-sm transition-colors inline-flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Нүүр хуудас руу буцах
                </a>
            </div>
        </div>
    </div>
</div>

@include('include.footer')
