@if(auth()->check())
    <a href="{{ route('authors.show', $author->slug ?? $author->id) }}" 
       class="flex items-center gap-3 bg-white/5 backdrop-blur-md border border-gray-200/20 dark:border-white/10 rounded-full px-2 py-2 pr-6 hover:bg-white/10 transition-colors shrink-0 min-w-[200px]">
        <div class="w-10 h-10 rounded-full overflow-hidden border border-gray-100 dark:border-gray-700 flex items-center justify-center bg-gradient-to-br from-yellow-500 via-amber-500 to-yellow-600">
            @if($author->profile_image)
                <img src="{{ asset(\Illuminate\Support\Str::startsWith($author->profile_image ?? '', ['http', '/']) ? $author->profile_image : 'storage/' . ltrim($author->profile_image ?? '', '/')) }}" 
                     alt="{{ $author->name }}" 
                     class="w-full h-full object-cover">
            @else
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                </svg>
            @endif
        </div>
        <div class="flex flex-col">
            <span class="text-sm font-medium text-gray-800 dark:text-gray-100 whitespace-nowrap">{{ $author->name }}</span>
             <span class="text-[10px] text-gray-500 dark:text-gray-400">{{ $author->books_count }} Books</span>
        </div>
    </a>
@else
    <a href="{{ route('login') }}" 
       class="flex items-center gap-3 bg-white/5 backdrop-blur-md border border-red-200/30 dark:border-red-500/30 rounded-full px-2 py-2 pr-6 hover:bg-white/10 transition-colors shrink-0 min-w-[200px] relative">
        <div class="absolute inset-0 bg-red-500/10 dark:bg-red-500/20 rounded-full"></div>
        <div class="w-10 h-10 rounded-full overflow-hidden border border-gray-100 dark:border-gray-700 relative opacity-60 flex items-center justify-center bg-gradient-to-br from-yellow-500 via-amber-500 to-yellow-600">
            @if($author->profile_image)
                <img src="{{ asset(\Illuminate\Support\Str::startsWith($author->profile_image ?? '', ['http', '/']) ? $author->profile_image : 'storage/' . ltrim($author->profile_image ?? '', '/')) }}" 
                     alt="{{ $author->name }}" 
                     class="w-full h-full object-cover">
            @else
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                </svg>
            @endif
            <div class="absolute inset-0 flex items-center justify-center bg-black/50">
                <span class="text-white text-xs">🔒</span>
            </div>
        </div>
        <div class="flex flex-col relative z-10">
            <span class="text-sm font-medium text-gray-800 dark:text-gray-100 whitespace-nowrap">{{ $author->name }}</span>
             <span class="text-[10px] text-red-600 dark:text-red-400 font-semibold">Нэвтрэх шаардлагатай</span>
        </div>
    </a>
@endif