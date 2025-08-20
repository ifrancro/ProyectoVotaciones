@extends('layouts.app')

@section('title', 'Detalle del Acta - Mesa ' . $acta->mesa_number)

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-file-alt"></i> Acta de Votación - Mesa {{ $acta->mesa_number }}</h2>
                <a href="{{ route('actas.search') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver a Búsqueda
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Información del Acta -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Información del Acta</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Elección:</strong></td>
                                    <td>{{ $acta->election->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Mesa:</strong></td>
                                    <td><span class="badge bg-primary">Mesa {{ $acta->mesa_number }}</span></td>
                                </tr>
                                <tr>
                                    <td><strong>Registrado por:</strong></td>
                                    <td>{{ $acta->user->name }} ({{ $acta->user->username }})</td>
                                </tr>
                                <tr>
                                    <td><strong>Fecha de registro:</strong></td>
                                    <td>{{ $acta->created_at->format('d/m/Y H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Última actualización:</strong></td>
                                    <td>{{ $acta->updated_at->format('d/m/Y H:i:s') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h6 class="card-title">Resumen de Votos</h6>
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="text-success">
                                                <h4>{{ $acta->total_votes }}</h4>
                                                <small>Total</small>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="text-danger">
                                                <h4>{{ $acta->null_votes }}</h4>
                                                <small>Nulos</small>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="text-warning">
                                                <h4>{{ $acta->blank_votes }}</h4>
                                                <small>Blancos</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <span class="badge {{ $acta->isComplete() ? 'bg-success' : 'bg-warning' }}">
                                            {{ $acta->isComplete() ? 'Completo (240/240)' : 'Incompleto (' . $acta->total_votes . '/240)' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Votos por Candidato -->
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-users"></i> Votos por Candidato</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($acta->candidateVotes as $vote)
                            <div class="col-md-6 mb-3">
                                <div class="card candidate-vote-card" style="border-left: 4px solid {{ $vote->candidate->color_hex }};">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1">{{ $vote->candidate->name }}</h6>
                                                <small class="text-muted">{{ $vote->candidate->party }}</small>
                                            </div>
                                            <div class="text-end">
                                                <h4 class="mb-0 text-primary">{{ $vote->votes }}</h4>
                                                <small class="text-muted">votos</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Observaciones -->
            @if($acta->observations)
                <div class="card mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-comment"></i> Observaciones</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">{{ $acta->observations }}</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Foto del Acta -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fas fa-camera"></i> Foto del Acta</h5>
                </div>
                <div class="card-body text-center">
                    @if($acta->photo_path)
                        <div class="acta-photo-container">
                            <img src="{{ $acta->photo_url }}" 
                                 alt="Foto del Acta - Mesa {{ $acta->mesa_number }}" 
                                 class="img-fluid acta-photo"
                                 onclick="openPhotoModal(this.src)">
                            <div class="mt-3">
                                <button class="btn btn-sm btn-outline-primary" onclick="openPhotoModal('{{ $acta->photo_url }}')">
                                    <i class="fas fa-expand"></i> Ver en grande
                                </button>
                                <a href="{{ $acta->photo_url }}" download class="btn btn-sm btn-outline-success">
                                    <i class="fas fa-download"></i> Descargar
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="text-muted">
                            <i class="fas fa-image fa-3x mb-3"></i>
                            <p>No se ha subido foto del acta</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para ver foto en grande -->
<div class="modal fade" id="photoModal" tabindex="-1" aria-labelledby="photoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="photoModalLabel">Foto del Acta - Mesa {{ $acta->mesa_number }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalPhoto" src="" alt="Foto del Acta" class="img-fluid">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <a href="" id="downloadLink" class="btn btn-primary" download>
                    <i class="fas fa-download"></i> Descargar
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border-radius: 15px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    border: none;
}

.card-header {
    border-radius: 15px 15px 0 0 !important;
    border: none;
}

.candidate-vote-card {
    transition: transform 0.2s ease;
}

.candidate-vote-card:hover {
    transform: translateY(-2px);
}

.acta-photo-container {
    max-width: 100%;
}

.acta-photo {
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    cursor: pointer;
    transition: transform 0.2s ease;
}

.acta-photo:hover {
    transform: scale(1.05);
}

.badge {
    font-size: 0.8rem;
    padding: 6px 10px;
}

.table-borderless td {
    padding: 8px 0;
    border: none;
}

.modal-content {
    border-radius: 15px;
}

.modal-header {
    border-radius: 15px 15px 0 0;
}
</style>

<script>
function openPhotoModal(photoSrc) {
    document.getElementById('modalPhoto').src = photoSrc;
    document.getElementById('downloadLink').href = photoSrc;
    new bootstrap.Modal(document.getElementById('photoModal')).show();
}
</script>
@endsection
