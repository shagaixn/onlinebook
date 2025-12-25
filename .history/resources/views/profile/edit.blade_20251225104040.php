@include('include.header')

<div class="min-h-screen bg-[#0f172a] text-white pt-24 pb-12 relative overflow-hidden">
    <!-- Background Elements -->
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0 pointer-events-none">
        <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-purple-500/20 rounded-full blur-[120px]"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-blue-500/20 rounded-full blur-[120px]"></div>
    </div>

    <div class="container mx-auto px-4 relative z-10">
        <div class="max-w-3xl mx-auto">
            <div class="flex items-center gap-4 mb-8">
                <a href="{{ route('profile.show') }}" class="p-2 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <h1 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-400 to-purple-400">
                    Профайл засах
                </h1>
            </div>

            <div class="bg-slate-800/50 backdrop-blur-xl border border-slate-700 rounded-2xl p-8 shadow-xl">
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    
                    <!-- Profile Image -->
                    <div class="flex items-center gap-6 pb-6 border-b border-slate-700">
                        <div class="relative group">
                            <div class="w-24 h-24 rounded-full p-1 bg-gradient-to-br from-blue-500 to-purple-500">
                                <img src="{{ $user->profile_image ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=0f172a&color=3b82f6' }}" 
                                     class="w-full h-full rounded-full object-cover border-4 border-slate-900">
                            </div>
                            <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                <div class="bg-black/50 rounded-full p-2">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </div>
                            </div>
                        </div>
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-slate-300 mb-2">Профайл зураг</label>
                            <input type="file" name="profile_image" class="block w-full text-sm text-slate-400
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-full file:border-0
                                file:text-sm file:font-semibold
                                file:bg-blue-600 file:text-white
                                hover:file:bg-blue-500
                                cursor-pointer">
                            <p class="mt-1 text-xs text-slate-500">PNG, JPG, GIF (Дээд хэмжээ: 2MB)</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Нэр</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required 
                                   class="w-full px-4 py-3 bg-slate-900/50 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Хүйс</label>
                            <select name="gender" class="w-full px-4 py-3 bg-slate-900/50 border border-slate-700 rounded-xl text-white focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors">
                                <option value="" class="bg-slate-900">Сонгох</option>
                                <option value="Эрэгтэй" {{ old('gender', $user->gender) == 'Эрэгтэй' ? 'selected' : '' }} class="bg-slate-900">Эрэгтэй</option>
                                <option value="Эмэгтэй" {{ old('gender', $user->gender) == 'Эмэгтэй' ? 'selected' : '' }} class="bg-slate-900">Эмэгтэй</option>
                                <option value="Бусад" {{ old('gender', $user->gender) == 'Бусад' ? 'selected' : '' }} class="bg-slate-900">Бусад</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Нас</label>
                            <input type="number" name="age" value="{{ old('age', $user->age) }}" 
                                   class="w-full px-4 py-3 bg-slate-900/50 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Утас</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" 
                                   class="w-full px-4 py-3 bg-slate-900/50 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-300 mb-2">Хаяг</label>
                            <input type="text" name="address" value="{{ old('address', $user->address) }}" 
                                   class="w-full px-4 py-3 bg-slate-900/50 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-300 mb-2">Танилцуулга</label>
                            <textarea name="bio" rows="4" 
                                      class="w-full px-4 py-3 bg-slate-900/50 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors">{{ old('bio', $user->bio) }}</textarea>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-300 mb-2">Сонирхол</label>
                            <input type="text" name="interests" value="{{ old('interests', $user->interests) }}" placeholder="Жишээ: Уран зохиол, Шинжлэх ухаан..."
                                   class="w-full px-4 py-3 bg-slate-900/50 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors">
                        </div>
                    </div>

                    <div class="flex justify-end gap-4 pt-6 border-t border-slate-700">
                        <a href="{{ route('profile.show') }}" class="px-6 py-2.5 rounded-xl border border-slate-600 text-slate-300 hover:bg-slate-800 transition-colors">
                            Болих
                        </a>
                        <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-medium shadow-lg shadow-blue-900/20 transition-all hover:-translate-y-0.5">
                            Хадгалах
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@include('include.footer')
