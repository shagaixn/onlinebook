@include('include.header')

<div class="max-w-5xl mx-auto px-6 py-10">
    <a href="{{ route('authors.index') }}" class="inline-flex items-center gap-2 text-blue-600 dark:text-blue-400 hover:underline mb-6">
        ← Буцах
    </a>

    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow border border-gray-100 dark:border-slate-800 overflow-hidden">
        <div class="flex flex-col md:flex-row">
            <div class="md:w-1/3">
                @if($author->profile_image)
                    <img src="{{ asset('storage/'.$author->profile_image) }}" alt="{{ $author->name }}" class="w-full h-72 object-cover md:h-full">
                @else
                    <div class="w-full h-72 md:h-full bg-gray-200 dark:bg-slate-800 flex items-center justify-center text-gray-500">No Image</div>
                @endif
            </div>
            <div class="md:w-2/3 p-6">
                <h1 class="text-3xl font-bold text-slate-900 dark:text-slate-100">{{ $author->name }}</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">{{ $author->nationality ?? 'Үндэс тодорхойгүй' }}</p>

                @if($author->birth_date || $author->death_date)
                    <div class="mt-3 text-sm text-gray-600 dark:text-gray-400">
                        Амьдрал: 
                        <span>{{ optional($author->birth_date)->format('Y-m-d') ?? 'Тодорхойгүй' }}</span>
                        —
                        <span>{{ optional($author->death_date)->format('Y-m-d') ?? 'одоо' }}</span>
                    </div>
                @endif

                @if(!empty($author->biography))
                    <div class="mt-5 text-gray-800 dark:text-gray-200 whitespace-pre-line leading-7">{{ $author->biography }}</div>
                @endif

                @php
                    $awards = $author->awards ? preg_split('/\r\n|\r|\n/', $author->awards) : [];
                    $awards = array_filter(array_map('trim', $awards));
                @endphp
                @if(!empty($awards))
                    <div class="mt-6">
                        <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-2">Шагнал/Бүтээлүүд</h2>
                        <ul class="list-disc list-inside text-gray-700 dark:text-gray-300">
                            @foreach($awards as $line)
                                <li>{{ $line }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@include('include.footer')
