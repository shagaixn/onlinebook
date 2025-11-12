{{-- @include('include.header')

<div class="max-w-3xl mx-auto mt-16 p-6 text-gray-100">
    <h1 class="text-3xl font-bold mb-8 text-center text-blue-400">Зохиолч дэлгэрэнгүй</h1>
    <!-- Зохиолчийн мэдээлэл энд орно -->
    <div class="bg-slate-800 p-8 rounded-2xl shadow-lg flex flex-col items-center">
        <img src="{{ $author->avatar ? Storage::disk('public')->url($author->avatar) : '/images/authors/default.jpg' }}" alt="Author" class="w-32 h-32 rounded-full mb-4 object-cover border-2 border-blue-500">
        <h2 class="text-2xl font-semibold mb-2">{{ $author->name }}</h2>
        <p class="text-gray-400 text-center mb-4">{{ $author->bio }}</p>
        <div class="flex flex-col items-center space-y-2 text-sm text-gray-400">
            <div>Төрсөн огноо: {{ $author->birth_date }}</div>
            <div>Төрсөн газар: {{ $author->birth_place }}</div>
            <div>Овог: {{ $author->position }}</div>
            <div>Улс: {{ $author->country }}</div>
        </div>
        <div class="mt-6 w-full">
            <h3 class="text-lg font-bold mb-2 text-blue-300">Бүтээлүүд</h3>
            <ul class="list-disc list-inside text-gray-300">
                @if(is_array($author->notable_works))
                    @foreach($author->notable_works as $work)
                        <li>{{ $work }}</li>
                    @endforeach
                @elseif($author->notable_works)
                    <li>{{ $author->notable_works }}</li>
                @else
                    <li>Бүтээл байхгүй</li>
                @endif
            </ul>
        </div>
    </div>
</div>

@include('include.footer') --}}
