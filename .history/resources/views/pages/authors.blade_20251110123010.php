@extends('includes.header')

@section('content')
<div class="container py-5">
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card">
                @if($author->profile_image)
                    <img src="{{ asset('storage/'.$author->profile_image) }}" class="card-img-top" alt="{{ $author->name }}" style="object-fit:cover;height:360px;">
                @else
                    <div class="bg-light d-flex align-items-center justify-content-center" style="height:360px;">
                        <i class="bi bi-person" style="font-size:80px;color:#aaa"></i>
                    </div>
                @endif
                <div class="card-body">
                    <h3 class="card-title">{{ $author->name }}</h3>
                    @if($author->nationality)
                        <p class="mb-1"><strong>Үндэс:</strong> {{ $author->nationality }}</p>
                    @endif
                    @if($author->birth_date)
                        <p class="mb-1"><strong>Төрсөн:</strong> {{ $author->birth_date }}</p>
                    @endif
                    @if($author->death_date)
                        <p class="mb-1"><strong>Өнгөрсөн:</strong> {{ $author->death_date }}</p>
                    @endif
                    @if($author->awards)
                        <p class="mb-1"><strong>Шагнал/Онцлох бүтээл:</strong><br>{!! nl2br(e($author->awards)) !!}</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <h4>Товч намтар</h4>
            <div class="mb-4">
                {!! nl2br(e($author->biography ?? 'Тодорхойлолт байхгүй байна.')) !!}
            </div>

            <h5>Номнууд</h5>
            <div>
                {{-- Хэрэв Books загвартай холбоос байгаа бол энд жагсааж болно. Жишээ: --}}
                {{-- @foreach($author->books as $book) ... @endforeach --}}
                <p class="text-muted">Энд зохиолчийн номнуудыг жагсаана.</p>
            </div>
        </div>
    </div>
</div>
@endsection
include 