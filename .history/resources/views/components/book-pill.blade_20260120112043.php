<a href="{{ route('books.show', $book->id) }}" 
   class="flex items-center gap-3 bg-white/5 backdrop-blur-md border border-gray-200/20 dark:border-white/10 rounded-xl px-2 py-2 pr-6 hover:bg-white/10 transition-colors shrink-0 min-w-[220px]">
    <div class="w-10 h-14 rounded overflow-hidden border border-gray-100 dark:border-gray-700 shadow-sm relative shrink-0">
        <img src="{{ $book->cover_image ? asset('storage/'.$book->cover_image) : asset('images/placeholder-book.png') }}" 
             alt="{{ $book->title }}" 
             class="w-full h-full object-cover">
    </div>
    <div class="flex flex-col min-w-0">
        <span class="text-sm font-medium text-gray-800 dark:text-gray-100 truncate w-full block">{{ $book->title }}</span>
         <span class="text-[10px] text-gray-500 dark:text-gray-400 truncate">{{ $book->author ?? $book->authorModel?->name ?? 'Unknown' }}</span>
         <div class="flex items-center gap-1 mt-0.5">
            <span class="text-yellow-500 text-[10px]">★</span>
            <span class="text-[10px] text-gray-600 dark:text-gray-300">{{ number_format($book->reviews_avg_rating ?? 0, 1) }}</span>
         </div>
    </div>
</a>