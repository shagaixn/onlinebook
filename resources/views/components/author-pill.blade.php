<a href="{{ route('authors.show', $author->slug ?? $author->id) }}" 
   class="flex items-center gap-3 bg-white/5 backdrop-blur-md border border-gray-200/20 dark:border-white/10 rounded-full px-2 py-2 pr-6 hover:bg-white/10 transition-colors shrink-0 min-w-[200px]">
    <div class="w-10 h-10 rounded-full overflow-hidden border border-gray-100 dark:border-gray-700">
        <img src="{{ asset(\Illuminate\Support\Str::startsWith($author->profile_image ?? '', ['http', '/']) ? $author->profile_image : 'storage/' . ltrim($author->profile_image ?? '', '/')) }}" 
             alt="{{ $author->name }}" 
             class="w-full h-full object-cover">
    </div>
    <div class="flex flex-col">
        <span class="text-sm font-medium text-gray-800 dark:text-gray-100 whitespace-nowrap">{{ $author->name }}</span>
         <span class="text-[10px] text-gray-500 dark:text-gray-400">{{ $author->books_count }} Books</span>
    </div>
</a>