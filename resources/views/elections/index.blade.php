@extends('layouts.app')

@section('title', 'Elecciones')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-calendar-alt me-2"></i>Elecciones</h1>
    <a href="{{ route('elections.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i>Nueva Elección
    </a>
</div>

<div class="row">
    @forelse($elections as $election)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">{{ $election->nombre }}</h5>
                    <span class="badge {{ $election->is_active ? 'bg-success' : 'bg-secondary' }}">
                        {{ $election->is_active ? 'Activa' : 'Inactiva' }}
                    </span>
                </div>
                <div class="card-body">
                    <p class="card-text">
                        <i class="fas fa-calendar me-1"></i>
                        <strong>Fecha:</strong> {{ $election->fecha->format('d/m/Y') }}
                    </p>
                    <p class="card-text">
                        <i class="fas fa-users me-1"></i>
                        <strong>Candidatos:</strong> {{ $election->candidates->count() }}
                    </p>
                    <p class="card-text">
                        <i class="fas fa-file-alt me-1"></i>
                        <strong>Actas:</strong> {{ $election->actas->count() }}
                    </p>
                </div>
                <div class="card-footer">
                    <div class="btn-group w-100" role="group">
                        <a href="{{ route('elections.show', $election) }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('elections.edit', $election) }}" class="btn btn-outline-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('elections.destroy', $election) }}" method="POST" class="d-inline">
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
                No hay elecciones registradas. 
                <a href="{{ route('elections.create') }}" class="alert-link">Crear la primera elección</a>
            </div>
        </div>
    @endforelse
</div>
@endsection
