@include('include.header')

<main class="bg-gradient-to-b from-blue-50 to-white dark:from-slate-950 dark:to-slate-900 min-h-screen py-12 transition-colors duration-300">
  <div class="max-w-4xl mx-auto bg-white dark:bg-slate-800 rounded-3xl shadow-2xl overflow-hidden flex flex-col md:flex-row transition-colors duration-300 border border-transparent dark:border-slate-700">
      <!-- Book Image -->
      <div class="md:w-1/2 flex items-center justify-center bg-gradient-to-br from-blue-100 to-blue-200 dark:from-slate-800 dark:to-slate-700 p-8 transition-colors duration-300">
        <img src="{{ $book->cover_image ? asset('storage/' . $book->cover_image) : 'https://via.placeholder.com/250x360?text=No+Image' }}"
             alt="{{ $book->title }}"
             class="rounded-xl shadow-lg w-64 h-96 object-cover border-4 border-white dark:border-slate-700 transition-all duration-300">
      </div>
      <!-- Book Info -->
      <div class="md:w-1/2 p-8 flex flex-col justify-center transition-colors duration-300 bg-white dark:bg-slate-800">
        <h2 class="text-3xl font-extrabold text-blue-800 dark:text-blue-200 mb-2 drop-shadow">{{ $book->title }}</h2>
        <p class="text-gray-700 dark:text-gray-300 font-medium mb-2">
          Зохиолч: <span class="font-semibold text-blue-500 dark:text-blue-300">{{ $book->author ?? '-' }}</span>
        </p>
        <p class="text-gray-700 dark:text-gray-300 font-medium mb-2">
          Хэвлэгдсэн огноо: 
          <span class="font-semibold text-blue-500 dark:text-blue-300">
            {{ $book->published_date ? \Carbon\Carbon::parse($book->published_date)->format('Y-m-d') : '-' }}
          </span>
        </p>
        <p class="text-gray-700 dark:text-gray-300 font-medium mb-4">
          Үнэ: <span class="font-semibold text-green-700 dark:text-green-300">{{ $book->price }}₮</span>
        </p>
        <div class="flex items-center gap-6 mb-4">
          <span class="inline-block bg-blue-200 dark:bg-blue-900 text-blue-900 dark:text-blue-200 px-3 py-1 rounded-full font-semibold text-lg shadow">{{ $book->duration ?? '-' }}</span>
        </div>
        <div class="mb-4 text-gray-700 dark:text-gray-300">
          <span class="font-semibold">Ангилал:</span>
          {{ $category ? $category->name : '-' }}
        </div>
        <button aria-label="Худалдан авах" class="mt-2 bg-gradient-to-r from-blue-600 to-blue-400 dark:from-blue-800 dark:to-blue-600 text-white px-8 py-3 rounded-full shadow-md hover:from-blue-700 hover:to-blue-500 dark:hover:from-blue-900 dark:hover:to-blue-700 hover:scale-105 transition transform duration-200 font-semibold text-lg focus:outline-none focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-800">
          Худалдан авах
        </button>
        <hr class="my-6 border-blue-200 dark:border-slate-700">
        <h4 class="text-xl font-bold text-blue-700 dark:text-blue-300 mb-2">Номын тухай</h4>
        <p class="text-gray-600 dark:text-gray-300 leading-relaxed text-base">{{ $book->description }}</p>
      </div>
  </div>
</main>

@include('include.footer')