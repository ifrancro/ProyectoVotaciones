@extends('layouts.app')

@section('title', 'Nueva Mesa')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-plus me-2"></i>Nueva Mesa de Votación</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('mesas.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="codigo" class="form-label">Código de Mesa</label>
                        <input type="text" class="form-control @error('codigo') is-invalid @enderror" 
                               id="codigo" name="codigo" value="{{ old('codigo') }}" required>
                        @error('codigo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="recinto" class="form-label">Recinto</label>
                        <input type="text" class="form-control @error('recinto') is-invalid @enderror" 
                               id="recinto" name="recinto" value="{{ old('recinto') }}" required>
                        @error('recinto')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="ciudad" class="form-label">Ciudad</label>
                        <input type="text" class="form-control @error('ciudad') is-invalid @enderror" 
                               id="ciudad" name="ciudad" value="{{ old('ciudad') }}" required>
                        @error('ciudad')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('mesas.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Guardar Mesa
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
