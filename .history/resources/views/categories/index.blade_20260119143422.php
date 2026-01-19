<!DOCTYPE html>
<html lang="mn">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Ангиллууд - OnlineBook</title>
   @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
   @include('include.header')

   <div class="container mx-auto px-4 py-8">
       <div class="mb-8">
           <h1 class="text-4xl font-bold text-gray-800 mb-4">📂 Номын ангилал</h1>
           <p class="text-gray-600">Таны сонирхолд таарсан ангиллын номуудыг хайна уу</p>
       </div>

       <!-- Search Bar -->
       <div class="mb-8">
           <form action="{{ route('categories.index') }}" method="GET" class="flex gap-3 max-w-xl">
               <input 
                   type="text" 
                   name="q" 
                   value="{{ request('q') }}" 
                   placeholder="Ангилал хайх..." 
                   class="flex-1 px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
               >
               <button 
                   type="submit" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl transition">
                   🔍 Хайх
               </button>
           </form>
       </div>

       @if ($categories->count() > 0)
       <!-- Categories Grid -->
       <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
           @foreach ($categories as $category)
           <a href="{{ route('categories.show', $category->slug ?: $category->id) }}" 
              class="bg-white rounded-xl shadow-md hover:shadow-xl transition p-6 border border-gray-100">
               <div class="text-center">
                   <div class="text-5xl mb-3">📚</div>
                   <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $category->name }}</h3>
                   @if ($category->description)
                       <p class="text-gray-600 text-sm mb-3">
                           {{ Str::limit($category->description, 80) }}
                       </p>
                   @endif
                   <div class="inline-block bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold">
                       {{ $category->books_count }} ном
                   </div>
               </div>
           </a>
           @endforeach
       </div>

       <!-- Pagination -->
       <div class="mt-8">
           {{ $categories->links() }}
       </div>
       @else
       <div class="bg-yellow-100 border border-yellow-400 text-yellow-800 px-6 py-4 rounded-xl text-center">
           <p class="text-lg">Ангилал олдсонгүй</p>
       </div>
       @endif
   </div>
</body>
</html>