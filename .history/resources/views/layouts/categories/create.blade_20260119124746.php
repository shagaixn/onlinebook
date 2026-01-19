@extends('layouts.admin')

@section('title', 'Ангилал нэмэх')

@section('content')
<div class="p-6">
   <div class="mb-6">
       <h1 class="text-3xl font-bold text-gray-800">📂 Ангилал нэмэх</h1>
   </div>

   <div class="bg-white rounded-xl shadow p-6 max-w-2xl">
       <form action="{{ route('admin.categories.store') }}" method="POST">
           @csrf

           <div class="mb-4">
               <label for="name" class="block text-gray-700 font-semibold mb-2">
                   Нэр <span class="text-red-500">*</span>
               </label>
               <input 
                   type="text" 
                   id="name" 
                   name="name" 
                   value="{{ old('name') }}"
                   class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                   required
               >
               @error('name')
                   <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
               @enderror
           </div>

           <div class="mb-4">
               <label for="slug" class="block text-gray-700 font-semibold mb-2">
                   Slug <span class="text-gray-500 text-sm">(хоосон үлдээвэл автоматаар үүснэ)</span>
               </label>
               <input 
                   type="text" 
                   id="slug" 
                   name="slug" 
                   value="{{ old('slug') }}"
                   class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('slug') border-red-500 @enderror"
               >
               @error('slug')
                   <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
               @enderror
           </div>

           <div class="mb-4">
               <label for="description" class="block text-gray-700 font-semibold mb-2">
                   Тайлбар
               </label>
               <textarea 
                   id="description" 
                   name="description" 
                   rows="4"
                   class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror"
               >{{ old('description') }}</textarea>
               @error('description')
                   <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
               @enderror
           </div>

           <div class="flex gap-3">
               <button 
                   type="submit"
                   class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                   Хадгалах
               </button>
               <a 
                   href="{{ route('admin.categories.index') }}"
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg">
                   Буцах
               </a>
           </div>
       </form>
   </div>
</div>
@endsection