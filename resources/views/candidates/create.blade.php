@extends('layouts.app')

@section('title', 'Nuevo Candidato')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-user-plus me-2"></i>Nuevo Candidato</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('candidates.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="election_id" class="form-label">Elección</label>
                        <select class="form-select @error('election_id') is-invalid @enderror" 
                                id="election_id" name="election_id" required>
                            <option value="">Seleccionar elección</option>
                            @foreach($elections as $election)
                                <option value="{{ $election->id }}" {{ old('election_id') == $election->id ? 'selected' : '' }}>
                                    {{ $election->nombre }} - {{ $election->fecha->format('d/m/Y') }}
                                </option>
                            @endforeach
                        </select>
                        @error('election_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre del Candidato</label>
                        <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                               id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="sigla" class="form-label">Sigla/Partido</label>
                        <input type="text" class="form-control @error('sigla') is-invalid @enderror" 
                               id="sigla" name="sigla" value="{{ old('sigla') }}" required>
                        @error('sigla')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="color_hex" class="form-label">Color (Hex)</label>
                        <div class="input-group">
                            <input type="color" class="form-control form-control-color @error('color_hex') is-invalid @enderror" 
                                   id="color_hex" name="color_hex" value="{{ old('color_hex', '#000000') }}" required>
                            <input type="text" class="form-control @error('color_hex') is-invalid @enderror" 
                                   id="color_hex_text" value="{{ old('color_hex', '#000000') }}" 
                                   placeholder="#000000" pattern="^#[0-9A-Fa-f]{6}$">
                        </div>
                        @error('color_hex')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('candidates.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Guardar Candidato
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('color_hex').addEventListener('input', function() {
    document.getElementById('color_hex_text').value = this.value;
});

document.getElementById('color_hex_text').addEventListener('input', function() {
    if (this.value.match(/^#[0-9A-Fa-f]{6}$/)) {
        document.getElementById('color_hex').value = this.value;
    }
});
</script>
@endsection
