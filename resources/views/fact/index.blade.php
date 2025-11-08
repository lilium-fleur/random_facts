@extends('common.layout')

@section('content')
    <div class="container py-4">

        <h1 class="mb-4 fw-bold">
            🎲 Random Facts
        </h1>

        @forelse($facts as $fact)
            <div class="card shadow-sm mb-3 border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge bg-primary text-uppercase">
                            {{ $fact->category }}
                        </span>

                        <span class="text-muted small">
                            Source: <span class="fw-semibold">{{ $fact->source }}</span>
                        </span>
                    </div>

                    <p class="mb-0 fs-5">
                        {{ $fact->text }}
                    </p>
                </div>
            </div>
        @empty
            <div class="alert alert-info">
                Пока нет ни одного факта. Добавь первый и вселенная станет чуть интереснее ✨
            </div>
        @endforelse

    </div>
@endsection
