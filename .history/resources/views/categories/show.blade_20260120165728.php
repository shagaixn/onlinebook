@include('include.header')

<main class="bg-dark-50 dark:bg-slate-900 min-h-screen py-12 transition-colors duration-300">
   <div class="max-w-7xl mx-auto px-6">
       <a href="{{ route('book') }}" class="inline-flex items-center gap-2 text-blue-600 dark:text-blue-300 hover:underline mb-6">
           ← Буцах
       </a>

       <div class="mb-8">
           <h1 class="text-4xl font-extrabold text-slate-900 dark:text-white mb-2">{{ $category->name }}</h1>
           @if($category->description)
               <p class="text-gray-700 dark:text-gray-300">{{ $category->description }}</p>
           @endif
           <p class="text-gray-600 dark:text-gray-400 mt-2">
               <span class="font-semibold">{{ $books->total() }}</span> ном олдлоо
           </p>
       </div>

       @if($books->count() > 0)
           <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
               @foreach($books as $book)
                   <a href="{{ route('books.show', $book->id) }}" class="group block bg-white dark:bg-white/5 backdrop-blur border border-gray-200 dark:border-white/10 rounded-xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                       <div class="aspect-[3/4] bg-gradient-to-br from-blue-50 to-blue-100 dark:from-slate-800 dark:to-slate-700 overflow-hidden">
                           @if($book->cover_image)
                               <img src="{{ asset('storage/' . $book->cover_image) }}" 
                                    alt="{{ $book->title }}" 
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                           @else
                               <div class="w-full h-full flex items-center justify-center">
                                   <span class="text-6xl">📚</span>
                               </div>
                           @endif
                       </div>
                       <div class="p-4">
                           <h3 class="font-bold text-lg text-slate-900 dark:text-white mb-1 line-clamp-2 group-hover:text-blue-600 dark:group-hover:text-blue-300 transition-colors">
                               {{ $book->title }}
                           </h3>
                           <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                               {{ $book->author_display ?? '-' }}
                           </p>
                           @if($book->price)
                               <p class="text-sm font-semibold text-green-600 dark:text-green-400">
                                   {{ $book->price }}₮
                               </p>
                           @endif
                       </div>
                   </a>
               @endforeach
           </div>

           <div class="mt-8">
               {{ $books->links() }}
           </div>
       @else
           <div class="text-center py-12">
               <div class="text-6xl mb-4">📚</div>
               <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-2">Ном олдсонгүй</h2>
               <p class="text-gray-600 dark:text-gray-400">Энэ ангилалд одоогоор ном байхгүй байна.</p>
           </div>
       @endif
   </div>
</main>

@include('include.footer')