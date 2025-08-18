@extends('layouts.app')

@section('title', 'Mesas de Votación')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-building me-2"></i>Mesas de Votación</h1>
    <a href="{{ route('mesas.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i>Nueva Mesa
    </a>
</div>

<div class="row">
    @forelse($mesas as $mesa)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">{{ $mesa->codigo }}</h5>
                    <span class="badge bg-info">{{ $mesa->ciudad }}</span>
                </div>
                <div class="card-body">
                    <p class="card-text">
                        <i class="fas fa-map-marker-alt me-1"></i>
                        <strong>Recinto:</strong> {{ $mesa->recinto }}
                    </p>
                    <p class="card-text">
                        <i class="fas fa-city me-1"></i>
                        <strong>Ciudad:</strong> {{ $mesa->ciudad }}
                    </p>
                    <p class="card-text">
                        <i class="fas fa-users me-1"></i>
                        <strong>Usuarios:</strong> {{ $mesa->users->count() }}
                    </p>
                </div>
                <div class="card-footer">
                    <div class="btn-group w-100" role="group">
                        <a href="{{ route('mesas.show', $mesa) }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('mesas.edit', $mesa) }}" class="btn btn-outline-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('mesas.destroy', $mesa) }}" method="POST" class="d-inline">
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
                No hay mesas registradas. 
                <a href="{{ route('mesas.create') }}" class="alert-link">Crear la primera mesa</a>
            </div>
        </div>
    @endforelse
</div>
@endsection
