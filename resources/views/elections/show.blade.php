@extends('layouts.app')

@section('title', 'Detalles de Elección')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-calendar-alt me-2"></i>{{ $election->nombre }}</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Fecha:</strong> {{ $election->fecha->format('d/m/Y') }}</p>
                        <p><strong>Estado:</strong> 
                            <span class="badge {{ $election->is_active ? 'bg-success' : 'bg-secondary' }}">
                                {{ $election->is_active ? 'Activa' : 'Inactiva' }}
                            </span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Candidatos:</strong> {{ $election->candidates->count() }}</p>
                        <p><strong>Actas:</strong> {{ $election->actas->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        @if($election->candidates->count() > 0)
        <div class="card mt-4">
            <div class="card-header">
                <h4><i class="fas fa-users me-2"></i>Candidatos</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($election->candidates as $candidate)
                    <div class="col-md-6 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">{{ $candidate->nombre }}</h5>
                                <p class="card-text">
                                    <strong>Sigla:</strong> {{ $candidate->sigla }}<br>
                                    <strong>Color:</strong> 
                                    <span class="badge" style="background-color: {{ $candidate->color_hex }}; color: white;">
                                        {{ $candidate->color_hex }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-cogs me-2"></i>Acciones</h4>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('elections.edit', $election) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-1"></i>Editar
                    </a>
                    <a href="{{ route('candidates.create') }}?election_id={{ $election->id }}" class="btn btn-success">
                        <i class="fas fa-user-plus me-1"></i>Agregar Candidato
                    </a>
                    <a href="{{ route('actas.create') }}?election_id={{ $election->id }}" class="btn btn-info">
                        <i class="fas fa-file-plus me-1"></i>Crear Acta
                    </a>
                    <form action="{{ route('elections.destroy', $election) }}" method="POST" class="d-grid">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('¿Estás seguro?')">
                            <i class="fas fa-trash me-1"></i>Eliminar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
