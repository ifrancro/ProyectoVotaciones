@extends('layouts.app')

@section('title', 'Registrar Votos - Mesa {{ auth()->user()->mesa_number }}')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-info">
                <h5><i class="fas fa-info-circle"></i> Registro de Votos - Mesa {{ auth()->user()->mesa_number }}</h5>
                <p class="mb-0">Elección: <strong>{{ $election->name }}</strong></p>
                <p class="mb-0">Máximo de votos permitidos: <strong>240</strong></p>
            </div>
        </div>
    </div>

    <form action="{{ route('actas.store') }}" method="POST" id="voteForm" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="election_id" value="{{ $election->id }}">
        
        <!-- Candidatos -->
        <div class="row mb-4">
            <div class="col-12">
                <h4><i class="fas fa-users"></i> Candidatos</h4>
            </div>
            
            @foreach($candidates->take(8) as $index => $candidate)
                <div class="col-md-3 mb-3">
                    <div class="candidate-card" style="background: linear-gradient(135deg, {{ $candidate->color_hex }} 0%, {{ $candidate->color_hex }}dd 100%); color: white;">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-user me-2"></i>
                            <h6 class="mb-0">{{ $candidate->name }}</h6>
                        </div>
                        @if($candidate->party)
                            <small style="opacity: 0.9;">{{ $candidate->party }}</small>
                        @endif
                        <div class="mt-3">
                            <label class="form-label">Votos</label>
                            <input type="number" 
                                   class="form-control vote-input" 
                                   name="candidate_votes[{{ $candidate->id }}]" 
                                   value="0" 
                                   min="0" 
                                   max="240"
                                   data-candidate="{{ $candidate->name }}">
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Votos especiales -->
        <div class="row mb-4">
            <div class="col-12">
                <h4><i class="fas fa-exclamation-triangle"></i> Votos Especiales</h4>
            </div>
            
            <div class="col-md-6 mb-3">
                <div class="candidate-card">
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-times-circle me-2 text-danger"></i>
                        <h6 class="mb-0">Votos Nulos</h6>
                    </div>
                    <div>
                        <label class="form-label">Cantidad</label>
                        <input type="number" 
                               class="form-control vote-input" 
                               name="null_votes" 
                               value="0" 
                               min="0" 
                               max="240"
                               data-candidate="Nulos">
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 mb-3">
                <div class="candidate-card">
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-circle me-2 text-secondary"></i>
                        <h6 class="mb-0">Votos en Blanco</h6>
                    </div>
                    <div>
                        <label class="form-label">Cantidad</label>
                        <input type="number" 
                               class="form-control vote-input" 
                               name="blank_votes" 
                               value="0" 
                               min="0" 
                               max="240"
                               data-candidate="Blancos">
                    </div>
                </div>
            </div>
        </div>

        <!-- Foto del Acta -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5><i class="fas fa-camera"></i> Foto del Acta (Opcional)</h5>
                        <p class="text-muted">Sube una foto del acta de votación para respaldo</p>
                        <div class="mb-3">
                            <input type="file" 
                                   class="form-control" 
                                   name="acta_photo" 
                                   accept="image/*"
                                   id="actaPhoto">
                            <div class="form-text">Formatos permitidos: JPG, PNG, GIF. Máximo 2MB</div>
                        </div>
                        <div id="photoPreview" class="mt-3" style="display: none;">
                            <img id="previewImage" src="" alt="Vista previa" class="img-fluid rounded" style="max-height: 200px;">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Observaciones -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5><i class="fas fa-comment"></i> Observaciones</h5>
                        <textarea class="form-control" 
                                  name="observations" 
                                  rows="3" 
                                  placeholder="Observaciones adicionales (opcional)"></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Resumen y validación -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-warning">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Total de votos: <span id="totalVotes">0</span> / 240</strong>
                        </div>
                        <div>
                            <span id="validationMessage" class="text-danger"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" class="save-btn" id="submitBtn" disabled>
            <i class="fas fa-save me-2"></i>Registrar Votos
        </button>
    </form>
</div>

<style>
.candidate-card {
    background: white;
    border-radius: 15px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    border: none;
    height: 100%;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.candidate-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(0,0,0,0.15);
}

.candidate-card.dark {
    background: linear-gradient(135deg, #3BA3BC 0%, #2c3e50 100%);
    color: white;
}

.candidate-card.medium {
    background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%);
    color: white;
}

.vote-input {
    background: white;
    border: 2px solid #ddd;
    border-radius: 8px;
    padding: 12px;
    font-size: 18px;
    text-align: center;
    width: 100%;
    transition: all 0.3s ease;
}

.vote-input:focus {
    border-color: #3498db;
    outline: none;
    box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
}

.vote-input.invalid {
    border-color: #e74c3c;
    background-color: #fdf2f2;
}

.save-btn {
    background-color: #2c3e50;
    color: white;
    border: none;
    padding: 15px 30px;
    border-radius: 10px;
    font-size: 16px;
    position: fixed;
    bottom: 30px;
    right: 30px;
    z-index: 1000;
    transition: all 0.3s ease;
}

.save-btn:hover:not(:disabled) {
    background-color: #34495e;
    transform: translateY(-2px);
}

.save-btn:disabled {
    background-color: #95a5a6;
    cursor: not-allowed;
    transform: none;
}

.alert {
    border-radius: 10px;
    border: none;
}

.card {
    border-radius: 15px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    border: none;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const voteInputs = document.querySelectorAll('.vote-input');
    const totalVotesSpan = document.getElementById('totalVotes');
    const validationMessage = document.getElementById('validationMessage');
    const submitBtn = document.getElementById('submitBtn');
    const maxVotes = 240;
    const actaPhotoInput = document.getElementById('actaPhoto');
    const photoPreview = document.getElementById('photoPreview');
    const previewImage = document.getElementById('previewImage');

    function calculateTotal() {
        let total = 0;
        voteInputs.forEach(input => {
            const value = parseInt(input.value) || 0;
            total += value;
        });
        return total;
    }

    function updateTotal() {
        const total = calculateTotal();
        totalVotesSpan.textContent = total;
        
        if (total > maxVotes) {
            validationMessage.textContent = `Excede el máximo de ${maxVotes} votos`;
            submitBtn.disabled = true;
            voteInputs.forEach(input => {
                input.classList.add('invalid');
            });
        } else if (total === 0) {
            validationMessage.textContent = 'Debe registrar al menos un voto';
            submitBtn.disabled = true;
            voteInputs.forEach(input => {
                input.classList.remove('invalid');
            });
        } else {
            validationMessage.textContent = '';
            submitBtn.disabled = false;
            voteInputs.forEach(input => {
                input.classList.remove('invalid');
            });
        }
    }

    // Event listeners para inputs de votos
    voteInputs.forEach(input => {
        input.addEventListener('input', updateTotal);
        input.addEventListener('change', function() {
            const value = parseInt(this.value) || 0;
            if (value < 0) {
                this.value = 0;
            }
            updateTotal();
        });
    });

    // Preview de foto
    actaPhotoInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            if (file.size > 2 * 1024 * 1024) { // 2MB
                alert('La imagen es demasiado grande. Máximo 2MB permitido.');
                this.value = '';
                photoPreview.style.display = 'none';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                photoPreview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            photoPreview.style.display = 'none';
        }
    });

    // Form validation
    const form = document.getElementById('voteForm');
    form.addEventListener('submit', function(e) {
        const total = calculateTotal();
        
        if (total === 0) {
            e.preventDefault();
            alert('Debe registrar al menos un voto');
            return;
        }
        
        if (total > maxVotes) {
            e.preventDefault();
            alert(`El total de votos no puede exceder ${maxVotes}`);
            return;
        }
        
        // Confirmar envío
        if (!confirm('¿Está seguro de registrar estos votos?')) {
            e.preventDefault();
            return;
        }
    });

    // Inicializar
    updateTotal();
});
</script>
@endsection
