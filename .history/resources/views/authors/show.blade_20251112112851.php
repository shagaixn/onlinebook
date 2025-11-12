@include('include.header')

<div class="max-w-6xl mx-auto mt-16 p-6 text-gray-100">
    <h1 class="text-3xl font-bold mb-8 text-center text-blue-400">📚 Зохиолчид</h1>

    <!-- Хайлт хэсэг -->
    <div class="flex justify-center mb-10">
        <input 
            type="text" 
            placeholder="Зохиолч хайх..." 
            class="w-1/2 px-4 py-2 rounded-l-full bg-slate-800 text-gray-200 border border-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
        />
        <button class="px-6 py-2 rounded-r-full bg-blue-600 hover:bg-blue-700">Хайх</button>
    </div>

    <!-- Ангилал хэсэг -->
    <div class="flex justify-center space-x-4 mb-12">
        <button class="px-4 py-2 bg-slate-700 rounded-full hover:bg-blue-600">Бүх зохиолчид</button>
        <button class="px-4 py-2 bg-slate-700 rounded-full hover:bg-blue-600">Монгол зохиолчид</button>
        <button class="px-4 py-2 bg-slate-700 rounded-full hover:bg-blue-600">Гадаад зохиолчид</button>
    </div>

    <!-- Зохиолчдын grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
        <!-- Зохиолч карт -->
        <div class="bg-slate-800 p-6 rounded-2xl shadow-lg hover:shadow-2xl hover:-translate-y-1 transition">
            <img src="/images/authors/default.jpg" alt="Author" class="w-24 h-24 mx-auto rounded-full mb-4 object-cover border-2 border-blue-500">
            <h3 class="text-xl font-semibold text-center mb-2">Д.Нацагдорж</h3>
            <p class="text-gray-400 text-sm text-center mb-4">Монголын сонгодог уран зохиолын төлөөлөгч</p>
            <div class="flex justify-center space-x-4 text-sm">
                <span class="text-gray-400">📖 12 бүтээл</span>
                <a href="#" class="text-blue-400 hover:underline">Дэлгэрэнгүй</a>
            </div>
        </div>

        <!-- Жишээ нэмэлт картууд -->
        <div class="bg-slate-800 p-6 rounded-2xl shadow-lg hover:shadow-2xl hover:-translate-y-1 transition">
            <img src="/images/authors/default.jpg" alt="Author" class="w-24 h-24 mx-auto rounded-full mb-4 object-cover border-2 border-blue-500">
            <h3 class="text-xl font-semibold text-center mb-2">Лев Толстой</h3>
            <p class="text-gray-400 text-sm text-center mb-4">Оросын алдарт зохиолч</p>
            <div class="flex justify-center space-x-4 text-sm">
                <span class="text-gray-400">📖 34 бүтээл</span>
                <a href="#" class="text-blue-400 hover:underline">Дэлгэрэнгүй</a>
            </div>
        </div>
    </div>

    <!-- Хуудас шилжүүлэгч -->
    <div class="flex justify-center mt-12 space-x-2">
        <button class="px-3 py-1 bg-slate-700 rounded hover:bg-blue-600">1</button>
        <button class="px-3 py-1 bg-slate-700 rounded hover:bg-blue-600">2</button>
        <button class="px-3 py-1 bg-slate-700 rounded hover:bg-blue-600">3</button>
    </div>
</div>

@include('include.footer')
