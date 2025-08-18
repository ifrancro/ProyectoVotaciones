@extends('layouts.app')

@section('title', 'Editar Elección')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-edit me-2"></i>Editar Elección</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('elections.update', $election) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre de la Elección</label>
                        <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                               id="nombre" name="nombre" value="{{ old('nombre', $election->nombre) }}" required>
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="fecha" class="form-label">Fecha de la Elección</label>
                        <input type="date" class="form-control @error('fecha') is-invalid @enderror" 
                               id="fecha" name="fecha" value="{{ old('fecha', $election->fecha->format('Y-m-d')) }}" required>
                        @error('fecha')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                                   {{ old('is_active', $election->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Elección Activa
                            </label>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('elections.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Actualizar Elección
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
