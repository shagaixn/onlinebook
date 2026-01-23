@include('include.header')

<main class="bg-dark-50 dark:bg-slate-900 min-h-screen py-12 transition-colors duration-300">
   <div class="max-w-7xl mx-auto px-6">
       <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-blue-600 dark:text-blue-300 hover:underline mb-6">
           ← Буцах
       </a>

       <div class="mb-8">
           <h1 class="text-4xl font-extrabold text-slate-900 dark:text-white mb-4">Ангиллууд</h1>
           <p class="text-gray-700 dark:text-gray-300">Сонирхож буй ангилалаа сонгоно уу</p>
       </div>

       @if($categories->count() > 0)
           <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
               @foreach($categories as $category)
                   <a href="{{ route('categories.show', $category->slug ?? $category->id) }}" 
                      class="group block bg-white dark:bg-white/5 backdrop-blur border border-gray-200 dark:border-white/10 rounded-xl p-6 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                       <div class="flex items-start justify-between mb-3">
                           <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center text-white text-2xl shadow-md">
                               📚
                           </div>
                           @if($category->books_count ?? 0 > 0)
                               <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-xs font-semibold rounded-full">
                                   {{ $category->books_count }}
                               </span>
                           @endif
                       </div>
                       <h3 class="font-bold text-xl text-slate-900 dark:text-white mb-2 group-hover:text-blue-600 dark:group-hover:text-blue-300 transition-colors">
                           {{ $category->name }}
                       </h3>
                       @if($category->description)
                           <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2">
                               {{ $category->description }}
                           </p>
                       @endif
                   </a>
               @endforeach
           </div>

           <div class="mt-8">
               {{ $categories->links() }}
           </div>
       @else
           <div class="text-center py-12">
               <div class="text-6xl mb-4">📚</div>
               <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-2">Ангилал олдсонгүй</h2>
               <p class="text-gray-600 dark:text-gray-400">Одоогоор ангилал байхгүй байна.</p>
           </div>
       @endif
   </div>
</main>

@include('include.footer')