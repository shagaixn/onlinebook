@include('include.header')

<div class="max-w-3xl mx-auto p-6">
    <h1 class="text-xl font-bold mb-4">Автор засах: {{ $author->name }}</h1>

    @if($errors->any())
        <div class="mb-4 text-red-600">
            <ul>
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @php $action = route('admin.authors.update', $author); $method = 'PUT'; @endphp
    @include('admin.authors._form')
</div>

@include('include.footer')
