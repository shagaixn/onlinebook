@include('include.header')

<div class="max-w-4xl mx-auto mt-16 bg-dark dark:bg-slate-900 p-10 rounded-2xl shadow-2xl transition-all duration-300 transition-colors border border-gray-200 dark:border-slate-800">
    <h2 class="text-2xl font-bold mb-6 text-center">
        👤 Хэрэглэгчийн Профайл
    </h2>

    @auth
        @if(session('success'))
            <div class="bg-green-100 border border-green-4text-2xl00 text-green-700 px-4 py-3 rounded mb-6 shadow-sm
                        dark:bg-green-900 dark:border-green-700 dark:text-green-200 transition-colors">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex flex-col items-center mb-10">
            <div class="relative group">
                <img src="{{ Auth::user()->profile_image ?? 'https://cdn-icons-png.flaticon.com/512/149/149071.png' }}" 
                     alt="Profile Picture" 
                     class="w-32 h-32 rounded-full object-cover border-4 border-blue-600 shadow-md group-hover:scale-105 transition-transform duration-300">
                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex items-center justify-center text-white text-sm font-medium rounded-full transition-opacity">
                    Зураг солих
                </div>
            </div>

            <h3 class="mt-4 text-2xl font-bold text-gray-800 dark:text-gray-100">
                {{ Auth::user()->name }}
            </h3>
            <p class="text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</p>
        </div>

        <div class="space-y-8">
            <div class="bg-dark-50 dark:bg-slate-800 p-6 rounded-xl shadow-md hover:shadow-lg dark:shadow-none dark:hover:shadow-lg transition-shadow">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-id-card text-blue-500"></i> Үндсэн мэдээлэл
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm text-2xl font-medium text-gray-600 dark:text-gray-400 mb-1">Нэр</label>
                        <p class="text-gray-800 dark:text-gray-100 font-medium">{{ Auth::user()->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Имэйл</label>
                        <p class="text-gray-800 dark:text-gray-100 font-medium">{{ Auth::user()->email }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Хүйс</label>
                        <p class="text-gray-800 dark:text-gray-100 font-medium">{{ Auth::user()->gender ?? 'Тодорхойгүй' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Нас</label>
                        <p class="text-gray-800 dark:text-gray-100 font-medium">{{ Auth::user()->age ?? 'Оруулаагүй' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Утас</label>
                        <p class="text-gray-800 dark:text-gray-100 font-medium">{{ Auth::user()->phone ?? 'Оруулаагүй' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Хаяг</label>
                        <p class="text-gray-800 dark:text-gray-100 font-medium">{{ Auth::user()->address ?? 'Оруулаагүй' }}</p>
                    </div>
                </div>
            </div>

           

            <div class="flex justify-center flex-wrap gap-4">
                     <a href="{{ url('/profile/edit') }}" 
                         class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-600 dark:hover:bg-blue-500 text-white px-6 py-2.5 rounded-lg shadow transition-transform hover:-translate-y-0.5">
                    ✏️ Засах
                </a>
                <button id="changePasswordBtn"
                        class="bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white hover:bg-gray-300 dark:hover:bg-gray-800 px-6 py-2.5 rounded-lg shadow transition-transform hover:-translate-y-0.5">
                    🔒 Нууц үг солих
                </button>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" 
                            class="bg-red-600 hover:bg-red-700 dark:bg-red-600 dark:hover:bg-red-500 text-white px-6 py-2.5 rounded-lg shadow transition-transform hover:-translate-y-0.5">
                        🚪 Гарах
                    </button>
                </form>
            </div>
        </div>
    @else
        <div class="text-center py-10">
            <p class="text-gray-600 dark:text-gray-400 mb-4 text-lg">Профайл харахын тулд нэвтэрнэ үү.</p>
            <a href="{{ route('login') }}" class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-600 dark:hover:bg-blue-700 text-white px-6 py-3 rounded-lg shadow-md transition-transform transition-colors hover:-translate-y-0.5">
                Нэвтрэх
            </a>
        </div>
    @endauth
</div>

@include('include.footer')