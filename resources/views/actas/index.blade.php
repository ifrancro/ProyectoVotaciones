@extends('layouts.app')

@section('title', 'Actas de Votación')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-file-alt me-2"></i>Actas de Votación</h1>
    <a href="{{ route('actas.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i>Nueva Acta
    </a>
</div>

<div class="row">
    @forelse($actas as $acta)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Acta #{{ $acta->id }}</h5>
                    <span class="badge bg-success">{{ $acta->election->nombre }}</span>
                </div>
                <div class="card-body">
                    <p class="card-text">
                        <i class="fas fa-calendar me-1"></i>
                        <strong>Elección:</strong> {{ $acta->election->nombre }}
                    </p>
                    <p class="card-text">
                        <i class="fas fa-building me-1"></i>
                        <strong>Mesa:</strong> {{ $acta->mesa->codigo }}
                    </p>
                    <p class="card-text">
                        <i class="fas fa-user me-1"></i>
                        <strong>Usuario:</strong> {{ $acta->user->full_name ?? $acta->user->name }}
                    </p>
                    <p class="card-text">
                        <i class="fas fa-vote-yea me-1"></i>
                        <strong>Votos Blancos:</strong> {{ $acta->blancos }}
                    </p>
                    <p class="card-text">
                        <i class="fas fa-times me-1"></i>
                        <strong>Votos Nulos:</strong> {{ $acta->nulos }}
                    </p>
                </div>
                <div class="card-footer">
                    <div class="btn-group w-100" role="group">
                        <a href="{{ route('actas.show', $acta) }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('actas.edit', $acta) }}" class="btn btn-outline-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('actas.destroy', $acta) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('¿Estás seguro?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle me-2"></i>
                No hay actas registradas. 
                <a href="{{ route('actas.create') }}" class="alert-link">Crear la primera acta</a>
            </div>
        </div>
    @endforelse
</div>
@endsection
