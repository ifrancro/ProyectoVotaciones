@extends('layouts.app')

@section('title', 'Candidatos')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-user-tie me-2"></i>Candidatos</h1>
    <a href="{{ route('candidates.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i>Nuevo Candidato
    </a>
</div>

<div class="row">
    @forelse($candidates as $candidate)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">{{ $candidate->nombre }}</h5>
                    <span class="badge" style="background-color: {{ $candidate->color_hex }}; color: white;">
                        {{ $candidate->sigla }}
                    </span>
                </div>
                <div class="card-body">
                    <p class="card-text">
                        <i class="fas fa-calendar me-1"></i>
                        <strong>Elección:</strong> {{ $candidate->election->nombre }}
                    </p>
                    <p class="card-text">
                        <i class="fas fa-tag me-1"></i>
                        <strong>Sigla:</strong> {{ $candidate->sigla }}
                    </p>
                    <p class="card-text">
                        <i class="fas fa-palette me-1"></i>
                        <strong>Color:</strong> 
                        <span class="badge" style="background-color: {{ $candidate->color_hex }}; color: white;">
                            {{ $candidate->color_hex }}
                        </span>
                    </p>
                </div>
                <div class="card-footer">
                    <div class="btn-group w-100" role="group">
                        <a href="{{ route('candidates.show', $candidate) }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('candidates.edit', $candidate) }}" class="btn btn-outline-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('candidates.destroy', $candidate) }}" method="POST" class="d-inline">
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
                No hay candidatos registrados. 
                <a href="{{ route('candidates.create') }}" class="alert-link">Crear el primer candidato</a>
            </div>
        </div>
    @endforelse
</div>
@endsection
