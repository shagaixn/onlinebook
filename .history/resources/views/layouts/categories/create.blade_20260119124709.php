@extends('layouts.admin')

@section('title', 'Ангиллын жагсаалт')

@section('content')
<div class="p-6">
   <div class="flex justify-between items-center mb-6">
       <h1 class="text-3xl font-bold text-gray-800">📂 Ангиллын жагсаалт</h1>
       <a href="{{ route('admin.categories.create') }}"
          class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow">
           + Ангилал нэмэх
       </a>
   </div>

   <div class="mb-8 flex justify-start">
       <form action="{{ route('admin.categories.index') }}" method="GET" class="flex gap-3 w-full max-w-md">
           <input 
               type="text" 
               name="search" 
               value="{{ request('search') }}" 
               placeholder="Ангиллын нэрээр хайх..." 
               class="w-full px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white"
           >
           <button 
               type="submit" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-xl transition">
               🔍 Хайх
           </button>
       </form>
   </div>

   @if (session('success'))
       <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
           {{ session('success') }}
       </div>
   @endif

   @if ($categories->count() > 0)
   <div class="overflow-x-auto bg-white rounded-xl shadow">
       <table class="min-w-full border-collapse">
           <thead class="bg-gray-100">
               <tr>
                   <th class="px-4 py-2 text-left text-gray-600 font-semibold">Нэр</th>
                   <th class="px-4 py-2 text-left text-gray-600 font-semibold">Slug</th>
                   <th class="px-4 py-2 text-left text-gray-600 font-semibold">Тайлбар</th>
                   <th class="px-4 py-2 text-left text-gray-600 font-semibold">Номын тоо</th>
                   <th class="px-4 py-2 text-center text-gray-600 font-semibold">Үйлдэл</th>
               </tr>
           </thead>
           <tbody>
               @foreach ($categories as $category)
               <tr class="border-b hover:bg-gray-50">
                   <td class="px-4 py-2 font-medium">{{ $category->name }}</td>
                   <td class="px-4 py-2 text-gray-600">{{ $category->slug }}</td>
                   <td class="px-4 py-2 text-gray-600">
                       {{ Str::limit($category->description ?? '-', 50) }}
                   </td>
                   <td class="px-4 py-2 text-center">
                       <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded">
                           {{ $category->books_count }}
                       </span>
                   </td>
                   <td class="px-4 py-2 text-center">
                       <div class="flex gap-2 justify-center">
                           <a href="{{ route('admin.categories.show', $category) }}"
                              class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm">
                               Харах
                           </a>
                           <a href="{{ route('admin.categories.edit', $category) }}"
                              class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm">
                               Засах
                           </a>
                           <form action="{{ route('admin.categories.destroy', $category) }}" 
                                 method="POST" 
                                 onsubmit="return confirm('Энэ ангилалыг устгах уу?');">
                               @csrf
                               @method('DELETE')
                               <button type="submit" 
                                       class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                                   Устгах
                               </button>
                           </form>
                       </div>
                   </td>
               </tr>
               @endforeach
           </tbody>
       </table>
   </div>

   <div class="mt-4">
       {{ $categories->links() }}
   </div>
   @else
       <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded">
           Ангилал олдсонгүй.
       </div>
   @endif
</div>
@endsection