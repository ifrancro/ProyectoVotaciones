@extends('layouts.app')

@section('title', 'Búsqueda de Actas')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="fas fa-search"></i> Búsqueda de Actas</h2>
        </div>
    </div>

    <!-- Formulario de búsqueda -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-filter"></i> Filtros de Búsqueda</h5>
                    
                    <form action="{{ route('actas.search.results') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="election_id" class="form-label">Elección</label>
                                <select class="form-select" id="election_id" name="election_id">
                                    <option value="">Todas las elecciones</option>
                                    @foreach($elections as $election)
                                        <option value="{{ $election->id }}" {{ request('election_id') == $election->id ? 'selected' : '' }}>
                                            {{ $election->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <label for="mesa_number" class="form-label">Número de Mesa</label>
                                <select class="form-select" id="mesa_number" name="mesa_number">
                                    <option value="">Todas las mesas</option>
                                    @for($i = 1; $i <= 16; $i++)
                                        <option value="{{ $i }}" {{ request('mesa_number') == $i ? 'selected' : '' }}>
                                            Mesa {{ $i }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <label for="user_name" class="form-label">Nombre del Usuario</label>
                                <input type="text" class="form-control" id="user_name" name="user_name" 
                                       value="{{ request('user_name') }}" placeholder="Buscar por nombre...">
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <label for="username" class="form-label">Usuario</label>
                                <input type="text" class="form-control" id="username" name="username" 
                                       value="{{ request('username') }}" placeholder="Buscar por usuario...">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Buscar
                                </button>
                                <a href="{{ route('actas.search') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Limpiar
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Resultados de búsqueda -->
    @if(isset($actas))
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-list"></i> Resultados ({{ $actas->count() }} actas encontradas)
                        </h5>
                        
                        @if($actas->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Mesa</th>
                                            <th>Usuario</th>
                                            <th>Elección</th>
                                            <th>Total Votos</th>
                                            <th>Fecha Registro</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($actas as $acta)
                                            <tr>
                                                <td>
                                                    <span class="badge bg-primary">Mesa {{ $acta->mesa_number }}</span>
                                                </td>
                                                <td>
                                                    <strong>{{ $acta->user->name }}</strong><br>
                                                    <small class="text-muted">{{ $acta->user->username }}</small>
                                                </td>
                                                <td>{{ $acta->election->name }}</td>
                                                <td>
                                                    <span class="badge bg-success">{{ $acta->total_votes }}/240</span>
                                                </td>
                                                <td>{{ $acta->created_at->format('d/m/Y H:i') }}</td>
                                                <td>
                                                    <a href="{{ route('actas.show', $acta) }}" class="btn btn-sm btn-info">
                                                        <i class="fas fa-eye"></i> Ver
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info text-center">
                                <i class="fas fa-info-circle"></i>
                                No se encontraron actas con los filtros especificados.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
.card {
    border-radius: 15px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    border: none;
}

.btn {
    border-radius: 8px;
    padding: 8px 16px;
}

.form-control, .form-select {
    border-radius: 8px;
    border: 2px solid #e9ecef;
}

.form-control:focus, .form-select:focus {
    border-color: #3498db;
    box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
}

.table {
    border-radius: 10px;
    overflow: hidden;
}

.badge {
    font-size: 0.8rem;
    padding: 6px 10px;
}
</style>
@endsection
