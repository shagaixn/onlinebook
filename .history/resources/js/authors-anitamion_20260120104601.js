 <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-8" id="authors-grid">
    @forelse($featuredAuthors as $author)
      <a href="{{ route('authors.show', $author->slug ?? $author->id) }}"
         class="group text-center transform-gpu will-change-transform transition-transform duration-300 ease-out
                opacity-0 translate-y-4"
         data-author-card
         data-tilt="8"    {{-- max deg tilt --}}
         data-magnet="10" {{-- magnet px --}}
      >
        <div class="w-16 h-16 mx-auto rounded-full overflow-hidden border border-gray-200 dark:border-white/30 mb-3 group-hover:border-gray-400 dark:group-hover:border-white/50 transition-colors">
          <img
            src="{{ asset(\Illuminate\Support\Str::startsWith($author->profile_image, ['http://', 'https://', '/']) ? $author->profile_image : 'storage/' . ltrim($author->profile_image, '/') ) }}"
            alt="{{ $author->name }}"
            class="w-full h-full object-cover author-avatar"
            loading="lazy"
          />
        </div>
        <h3 class="text-gray-900 dark:text-white font-medium text-sm mb-1 group-hover:text-gray-600 dark:group-hover:text-gray-300 transition-colors">
          {{ $author->name }}
        </h3>
        <p class="text-gray-500 dark:text-gray-400 text-xs">{{ $author->books_count }} ном</p>
      </a>
    @empty
      <div class="col-span-full text-center py-16 text-gray-500 dark:text-gray-400">Зохиолч байхгүй байна.</div>
    @endforelse
  </div>