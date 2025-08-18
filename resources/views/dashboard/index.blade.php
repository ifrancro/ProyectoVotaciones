@extends('layouts.app')

@section('title', 'Resumen en tiempo real')

@section('content')
<div class="container-fluid">
    <!-- Información del usuario -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-info">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <strong>Bienvenido, {{ $user->name }}!</strong>
                        @if($user->isAdmin())
                            <span class="badge bg-primary ms-2">Administrador</span>
                        @else
                            <span class="badge bg-success ms-2">Mesa {{ $user->mesa_number }}</span>
                        @endif
                    </div>
                    <div>
                        <small>Usuario: {{ $user->username }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <h1 class="mb-4">Resumen en tiempo real</h1>

    @if($election)
        <!-- Progreso de actas registradas -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-clipboard-list me-2"></i>
                            Progreso de Actas Registradas
                        </h5>
                        <div class="progress mb-3" style="height: 25px;">
                            @php
                                $porcentaje = ($mesasCompletadas / 16) * 100;
                            @endphp
                            <div class="progress-bar {{ $todasCompletadas ? 'bg-success' : 'bg-primary' }}" 
                                 role="progressbar" 
                                 style="width: {{ $porcentaje }}%"
                                 aria-valuenow="{{ $mesasCompletadas }}" 
                                 aria-valuemin="0" 
                                 aria-valuemax="16">
                                {{ $mesasCompletadas }}/16 mesas
                            </div>
                        </div>
                        <p class="card-text">
                            <strong>{{ $mesasCompletadas }}</strong> de <strong>16</strong> mesas han registrado sus actas.
                        </p>
                        @if($todasCompletadas)
                            <div class="alert alert-success mb-0">
                                <i class="fas fa-check-circle me-2"></i>
                                <strong>¡Conteo completo!</strong> Todas las 16 mesas han registrado sus actas. El proceso electoral ha finalizado.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Candidate Summary Cards -->
        <div class="row mb-4">
            <div class="col-12">
                <h4 class="mb-3">
                    <i class="fas fa-users me-2"></i>
                    Resultados por Candidato
                </h4>
            </div>
        </div>
        <div class="row mb-4">
            @foreach($candidates as $index => $candidate)
                <div class="col-md-3 mb-3">
                    <div class="candidate-card" style="background: linear-gradient(135deg, {{ $candidate['color_hex'] }} 0%, {{ $candidate['color_hex'] }}dd 100%); color: white;">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-user me-2"></i>
                            <h5 class="mb-0">{{ $candidate['nombre'] }}</h5>
                        </div>
                        <div class="text-center">
                            <h6>Votos</h6>
                            <h2 class="mb-0">{{ number_format($candidate['votos']) }}</h2>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Last Update -->
        <div class="text-muted mb-4">
            ultima actualizacion: {{ $lastUpdate->format('d/m/Y h:iA') }}
        </div>

        <!-- Charts Row -->
        <div class="row">
            <!-- Line Chart -->
            <div class="col-md-8">
                <div class="chart-container">
                    <h5 class="mb-3">Tendencia de Votos</h5>
                    <div style="height: 300px; position: relative;">
                        <canvas id="lineChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Donut Chart -->
            <div class="col-md-4">
                <div class="chart-container">
                    <h5 class="mb-3">Distribución de Votos</h5>
                    <div class="donut-chart">
                        <canvas id="donutChart"></canvas>
                    </div>
                                         <div class="legend">
                         @foreach($candidates as $index => $candidate)
                             <div class="legend-item">
                                 <div class="legend-color" style="background-color: {{ $candidate['color_hex'] }}"></div>
                                 <span>{{ $candidate['nombre'] }}</span>
                             </div>
                         @endforeach
                     </div>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-info text-center">
            <i class="fas fa-info-circle me-2"></i>
            No hay elecciones activas. 
            <a href="{{ route('elections.create') }}" class="alert-link">Crear una elección</a>
        </div>
    @endif
</div>

@if($election)
<script>
// Line Chart
const lineCtx = document.getElementById('lineChart').getContext('2d');
const lineChart = new Chart(lineCtx, {
    type: 'line',
    data: {
        labels: ['Hora 1', 'Hora 2', 'Hora 3', 'Hora 4', 'Hora 5', 'Hora 6'],
        datasets: [
            @foreach($candidates as $index => $candidate)
            {
                label: '{{ $candidate["nombre"] }}',
                data: [
                    {{ $candidate['votos'] * 0.75 }},
                    {{ $candidate['votos'] * 0.85 }},
                    {{ $candidate['votos'] * 0.8 }},
                    {{ $candidate['votos'] * 0.9 }},
                    {{ $candidate['votos'] * 0.95 }},
                    {{ $candidate['votos'] }}
                ],
                borderColor: '{{ $candidate["color_hex"] }}',
                backgroundColor: '{{ $candidate["color_hex"] }}20',
                borderWidth: 2,
                pointBackgroundColor: '{{ $candidate["color_hex"] }}',
                pointBorderColor: '#fff',
                pointBorderWidth: 1,
                pointRadius: 4,
                tension: 0.3
            }@if(!$loop->last),@endif
            @endforeach
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    font: {
                        size: 12
                    }
                },
                grid: {
                    color: 'rgba(0,0,0,0.1)'
                }
            },
            x: {
                ticks: {
                    font: {
                        size: 12
                    }
                },
                grid: {
                    color: 'rgba(0,0,0,0.1)'
                }
            }
        },
        plugins: {
            legend: {
                position: 'top',
                labels: {
                    font: {
                        size: 10
                    },
                    usePointStyle: true,
                    pointStyle: 'circle',
                    boxWidth: 8
                }
            }
        },
        elements: {
            line: {
                borderWidth: 2
            },
            point: {
                radius: 4
            }
        }
    }
});

// Donut Chart
const donutCtx = document.getElementById('donutChart').getContext('2d');
const donutChart = new Chart(donutCtx, {
    type: 'doughnut',
    data: {
        labels: [
            @foreach($candidates as $candidate)
                '{{ $candidate["nombre"] }}',
            @endforeach
        ],
        datasets: [{
            data: [
                @foreach($candidates as $candidate)
                    {{ $candidate['votos'] }},
                @endforeach
            ],
            backgroundColor: [
                @foreach($candidates as $candidate)
                    '{{ $candidate["color_hex"] }}',
                @endforeach
            ],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                        const percentage = ((context.parsed / total) * 100).toFixed(1);
                        return context.label + ': ' + context.parsed.toLocaleString() + ' votos (' + percentage + '%)';
                    }
                }
            }
        }
    }
});

// Center text in donut chart
const centerText = {
    id: 'centerText',
    afterDatasetsDraw(chart, args, options) {
        const { ctx, chartArea: { left, right, top, bottom, width, height } } = chart;
        
        ctx.save();
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        ctx.font = 'bold 16px Arial';
        ctx.fillStyle = '#2c3e50';
        
        const centerX = left + width / 2;
        const centerY = top + height / 2;
        
        ctx.fillText('{{ number_format($totalVotes) }}', centerX, centerY - 10);
        ctx.font = '12px Arial';
        ctx.fillText('Total Votos', centerX, centerY + 10);
        
        ctx.restore();
    }
};

donutChart.options.plugins.centerText = centerText;
donutChart.update();
</script>
@endif
@endsection
