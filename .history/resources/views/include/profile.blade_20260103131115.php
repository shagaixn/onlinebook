@include('include.header')

<div class="min-h-screen bg-dark dark:dark text-slate-900 dark:text-white pt-24 pb-12 relative overflow-hidden transition-colors">
    <!-- Background Elements -->
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0 pointer-events-none">
        <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-purple-500/20 rounded-full blur-[120px]"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-blue-500/20 rounded-full blur-[120px]"></div>
    </div>

    <div class="container mx-auto px-4 relative z-10">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-end mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-purple-600 dark:from-blue-400 dark:to-purple-400">
                    Миний Профайл
                </h1>
                <p class="text-slate-600 dark:text-slate-400 mt-2">Таны хувийн мэдээлэл болон тохиргоо</p>
            </div>
            <div class="flex gap-3">
                 <a href="{{ url('/profile/edit') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-lg font-medium transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                    Засах
                </a>
                <a href="{{ route('profile.changePasswordForm') }}" class="px-4 py-2 bg-white dark:bg-slate-700 hover:bg-gray-50 dark:hover:bg-slate-600 border border-gray-200 dark:border-slate-600 text-slate-700 dark:text-white rounded-lg font-medium transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    Нууц үг
                </a>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-white dark:bg-slate-700 hover:bg-gray-50 dark:hover:bg-slate-600 border border-gray-200 dark:border-slate-600 text-slate-700 dark:text-white rounded-lg font-medium transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        Гарах
                    </button>
                </form>
            </div>
        </div>

        @auth
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column: Profile Card -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- User Card -->
                    <div class="bg-dark dark:bg-slate-800/50 backdrop-blur-xl border border-gray-200 dark:border-slate-700 rounded-2xl p-6 text-center relative overflow-hidden group shadow-sm dark:shadow-none">
                        <div class="absolute inset-0  to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        
                        <div class="relative inline-block mb-4">
                            <div class="w-32 h-32 rounded-full p-1 bg-gradient-to-br from-blue-500 to-purple-500">
                                <img src="{{ Auth::user()->profile_image ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=0f172a&color=3b82f6' }}" 
                                     alt="{{ Auth::user()->name }}" 
                                     class="w-full h-full rounded-full object-cover border-4 border-gray-200 dark:border-slate-900">
                            </div>
                            <button class="absolute bottom-2 right-2 p-2 bg-blue-600 rounded-full hover:bg-blue-500 transition-colors shadow-lg">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </button>
                        </div>

                        <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-1">{{ Auth::user()->name }}</h2>
                        <p class="text-blue-600 dark:text-blue-400 text-sm mb-4">{{ Auth::user()->email }}</p>
                        
                        <div class="flex justify-center gap-2 mb-6">
                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-blue-500/10 dark:bg-blue-500/20 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-500/30">
                                {{ Auth::user()->role ?? 'Уншигч' }}
                            </span>
                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-purple-500/10 dark:bg-purple-500/20 text-purple-700 dark:text-purple-300 border border-purple-200 dark:border-purple-500/30">
                                ID: #{{ Auth::user()->id }}
                            </span>
                        </div>

                        <div class="grid grid-cols-3 gap-2 border-t border-gray-200 dark:border-slate-700 pt-4">
                            <div class="text-center">
                                <div class="text-lg font-bold text-slate-900 dark:text-white">0</div>
                                <div class="text-xs text-slate-500 dark:text-slate-400">Ном</div>
                            </div>
                            <div class="text-center border-l border-gray-200 dark:border-slate-700">
                                <div class="text-lg font-bold text-slate-900 dark:text-white">0</div>
                                <div class="text-xs text-slate-500 dark:text-slate-400">Сэтгэгдэл</div>
                            </div>
                            <div class="text-center border-l border-gray-200 dark:border-slate-700">
                                <div class="text-lg font-bold text-slate-900 dark:text-white">0</div>
                                <div class="text-xs text-slate-500 dark:text-slate-400">Хадгалсан</div>
                            </div>
                        </div>
                    </div>

                    <!-- Subscription Status -->
                    <div class="bg-gradient-to-br from-indigo-900/50 to-purple-900/50 backdrop-blur-xl border border-indigo-500/30 rounded-2xl p-6 relative overflow-hidden">
                        <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-indigo-500/20 rounded-full blur-2xl"></div>
                        
                        <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                            Book Plus Эрх
                        </h3>
                        
                        <div class="mb-4">
                            <div class="text-sm text-indigo-200 mb-1">Төлөв</div>
                            <div class="text-xl font-bold text-white">Идэвхгүй</div>
                        </div>
                        
                        <button class="w-full py-2 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg font-medium transition-colors shadow-lg shadow-indigo-900/20">
                            Эрх авах
                        </button>
                    </div>
                </div>

                <!-- Right Column: Details & Activity -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Personal Info -->
                    <div class="bg-dark dark:bg-slate-800/50 backdrop-blur-xl border border-gray-200 dark:border-slate-700 rounded-2xl p-6">
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-6 flex items-center gap-2 border-b border-gray-200 dark:border-slate-700 pb-4">
                            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            Хувийн мэдээлэл
                        </h3>

                        <div class="grid bg-dark grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-1">
                                <label class="text-xs uppercase tracking-wider text-slate-500 dark:text-slate-400 font-semibold">Нэр</label>
                                <div class="text-slate-900 dark:text-slate-200 font-medium bg-dark:bg-slate-900/50 px-4 py-3 rounded-lg border border-gray-200 dark:border-slate-700/50">
                                    {{ Auth::user()->name }}
                                </div>
                            </div>
                            <div class="space-y-1">
                                <label class="text-xs uppercase tracking-wider text-slate-500 dark:text-slate-400 font-semibold">Имэйл</label>
                                <div class="text-slate-900 dark:text-slate-200 font-medium bg-slate-50 dark:bg-slate-900/50 px-4 py-3 rounded-lg border border-gray-200 dark:border-slate-700/50">
                                    {{ Auth::user()->email }}
                                </div>
                            </div>
                            <div class="space-y-1">
                                <label class="text-xs uppercase tracking-wider text-slate-500 dark:text-slate-400 font-semibold">Утас</label>
                                <div class="text-slate-900 dark:text-slate-200 font-medium bg-slate-50 dark:bg-slate-900/50 px-4 py-3 rounded-lg border border-gray-200 dark:border-slate-700/50">
                                    {{ Auth::user()->phone ?? 'Бүртгэлгүй' }}
                                </div>
                            </div>
                            <div class="space-y-1">
                                <label class="text-xs uppercase tracking-wider text-slate-500 dark:text-slate-400 font-semibold">Хаяг</label>
                                <div class="text-slate-900 dark:text-slate-200 font-medium bg-slate-50 dark:bg-slate-900/50 px-4 py-3 rounded-lg border border-gray-200 dark:border-slate-700/50">
                                    {{ Auth::user()->address ?? 'Бүртгэлгүй' }}
                                </div>
                            </div>
                            <div class="space-y-1">
                                <label class="text-xs uppercase tracking-wider text-slate-500 dark:text-slate-400 font-semibold">Хүйс</label>
                                <div class="text-slate-900 dark:text-slate-200 font-medium bg-slate-50 dark:bg-slate-900/50 px-4 py-3 rounded-lg border border-gray-200 dark:border-slate-700/50">
                                    {{ Auth::user()->gender ?? 'Тодорхойгүй' }}
                                </div>
                            </div>
                            <div class="space-y-1">
                                <label class="text-xs uppercase tracking-wider text-slate-500 dark:text-slate-400 font-semibold">Нас</label>
                                <div class="text-slate-900 dark:text-slate-200 font-medium bg-slate-50 dark:bg-slate-900/50 px-4 py-3 rounded-lg border border-gray-200 dark:border-slate-700/50">
                                    {{ Auth::user()->age ?? 'Тодорхойгүй' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activity (Placeholder) -->
                    <div class="bg-white dark:bg-slate-800/50 backdrop-blur-xl border border-gray-200 dark:border-slate-700 rounded-2xl p-6">
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-6 flex items-center gap-2 border-b border-gray-200 dark:border-slate-700 pb-4">
                            <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Сүүлийн үйл ажиллагаа
                        </h3>
                        
                        <div class="space-y-4">
                            <div class="flex items-center gap-4 p-4 rounded-xl bg-slate-50 dark:bg-slate-900/30 border border-gray-200 dark:border-slate-700/50 hover:border-gray-300 dark:hover:border-slate-600 transition-colors">
                                <div class="w-10 h-10 rounded-full bg-blue-500/20 flex items-center justify-center text-blue-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-slate-900 dark:text-slate-200 text-sm">Бүртгэл үүсгэсэн</p>
                                    <p class="text-slate-600 dark:text-slate-500 text-xs">{{ Auth::user()->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="flex flex-col items-center justify-center py-20 text-center">
                <div class="w-24 h-24 bg-slate-200 dark:bg-slate-800 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-12 h-12 text-slate-500 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </div>
                <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-2">Нэвтрэх шаардлагатай</h2>
                <p class="text-slate-600 dark:text-slate-400 mb-8 max-w-md">Та өөрийн профайл мэдээллийг харахын тулд системд нэвтрэх эсвэл бүртгүүлэх шаардлагатай.</p>
                <div class="flex gap-4">
                    <a href="{{ route('login') }}" class="px-8 py-3 bg-blue-600 hover:bg-blue-500 text-white rounded-xl font-bold transition-all shadow-lg shadow-blue-900/20">
                        Нэвтрэх
                    </a>
                    <a href="{{ route('register') }}" class="px-8 py-3 bg-slate-700 hover:bg-slate-600 text-white rounded-xl font-bold transition-all border border-slate-600">
                        Бүртгүүлэх
                    </a>
                </div>
            </div>
        @endauth
    </div>
</div>

@include('include.footer')