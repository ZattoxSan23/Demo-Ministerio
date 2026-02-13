@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="fade-in">
    <!-- Header del Dashboard -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">
                <i class="bi bi-speedometer2 text-primary me-2"></i>
                Dashboard
            </h2>
            <p class="text-muted mb-0">
                Bienvenido, <strong>{{ Auth::user()->name }}</strong> | 
                <span class="badge bg-{{ Auth::user()->rolBadgeColor() }}">
                    {{ Auth::user()->rolPrincipal() }}
                </span>
            </p>
        </div>
        <div>
            <a href="{{ route('documentos.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Nuevo Documento
            </a>
        </div>
    </div>

    <!-- Cards de Estadísticas -->
    <div class="row g-3 mb-4">
        <!-- Total de Documentos -->
        <div class="col-6 col-lg">
            <div class="card stat-card total h-100">
                <div class="card-body text-center">
                    <div class="text-muted small text-uppercase mb-2">Total Documentos</div>
                    <div class="stat-number text-primary">{{ $estadisticas['total'] }}</div>
                    <i class="bi bi-folder text-primary opacity-25 fs-1 position-absolute end-0 bottom-0 me-3 mb-3"></i>
                </div>
            </div>
        </div>

        <!-- Pendientes -->
        <div class="col-6 col-lg">
            <div class="card stat-card pendientes h-100">
                <div class="card-body text-center">
                    <div class="text-muted small text-uppercase mb-2">Pendientes</div>
                    <div class="stat-number text-warning">{{ $estadisticas['pendientes'] }}</div>
                    <i class="bi bi-clock-history text-warning opacity-25 fs-1 position-absolute end-0 bottom-0 me-3 mb-3"></i>
                </div>
            </div>
        </div>

        <!-- En Proceso -->
        <div class="col-6 col-lg">
            <div class="card stat-card proceso h-100">
                <div class="card-body text-center">
                    <div class="text-muted small text-uppercase mb-2">En Proceso</div>
                    <div class="stat-number text-info">{{ $estadisticas['en_proceso'] }}</div>
                    <i class="bi bi-arrow-repeat text-info opacity-25 fs-1 position-absolute end-0 bottom-0 me-3 mb-3"></i>
                </div>
            </div>
        </div>

        <!-- Resueltos -->
        <div class="col-6 col-lg">
            <div class="card stat-card resueltos h-100">
                <div class="card-body text-center">
                    <div class="text-muted small text-uppercase mb-2">Resueltos</div>
                    <div class="stat-number text-success">{{ $estadisticas['resueltos'] }}</div>
                    <i class="bi bi-check-circle text-success opacity-25 fs-1 position-absolute end-0 bottom-0 me-3 mb-3"></i>
                </div>
            </div>
        </div>

        <!-- Archivados -->
        <div class="col-6 col-lg">
            <div class="card stat-card archivados h-100">
                <div class="card-body text-center">
                    <div class="text-muted small text-uppercase mb-2">Archivados</div>
                    <div class="stat-number text-secondary">{{ $estadisticas['archivados'] }}</div>
                    <i class="bi bi-archive text-secondary opacity-25 fs-1 position-absolute end-0 bottom-0 me-3 mb-3"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos y Tablas -->
    <div class="row g-4">
        <!-- Gráfico de Distribución por Estado -->
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header">
                    <i class="bi bi-pie-chart me-2"></i>
                    Distribución por Estado
                </div>
                <div class="card-body">
                    <canvas id="chartEstados" height="250"></canvas>
                </div>
            </div>
        </div>

        <!-- Gráfico de Documentos por Tipo -->
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header">
                    <i class="bi bi-bar-chart me-2"></i>
                    Documentos por Tipo
                </div>
                <div class="card-body">
                    <canvas id="chartTipos" height="250"></canvas>
                </div>
            </div>
        </div>

        <!-- Gráfico de Tendencia Mensual -->
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header">
                    <i class="bi bi-graph-up me-2"></i>
                    Tendencia Mensual
                </div>
                <div class="card-body">
                    <canvas id="chartMensual" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Últimos Documentos -->
    <div class="card mt-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>
                <i class="bi bi-clock-history me-2"></i>
                Últimos Documentos Registrados
            </span>
            <a href="{{ route('documentos.index') }}" class="btn btn-sm btn-outline-primary">
                Ver todos <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>N° Expediente</th>
                            <th>Título</th>
                            <th>Tipo</th>
                            <th>Estado</th>
                            <th>Prioridad</th>
                            <th>Fecha</th>
                            <th>Registrado por</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ultimosDocumentos as $doc)
                        <tr>
                            <td>
                                <span class="badge bg-light text-dark border">
                                    {{ $doc->numero_expediente }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('documentos.show', $doc) }}" class="text-decoration-none">
                                    {{ Str::limit($doc->titulo, 40) }}
                                </a>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $doc->tipo }}</span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $doc->estado_color }}">
                                    {{ $doc->estado }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $doc->prioridad_color }}">
                                    {{ $doc->prioridad }}
                                </span>
                            </td>
                            <td>{{ $doc->fecha_registro->format('d/m/Y') }}</td>
                            <td>
                                <small>{{ Str::limit($doc->usuario->name, 20) }}</small>
                            </td>
                            <td>
                                <a href="{{ route('documentos.show', $doc) }}" 
                                   class="btn btn-sm btn-outline-primary" title="Ver">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                No hay documentos registrados
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Documentos por Prioridad -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Documentos por Prioridad
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-around text-center">
                        <div>
                            <div class="stat-number text-danger">{{ $documentosPrioridad['urgentes'] }}</div>
                            <small class="text-muted">Urgentes</small>
                        </div>
                        <div>
                            <div class="stat-number text-warning">{{ $documentosPrioridad['altas'] }}</div>
                            <small class="text-muted">Alta</small>
                        </div>
                        <div>
                            <div class="stat-number text-info">{{ $documentosPrioridad['medias'] }}</div>
                            <small class="text-muted">Media</small>
                        </div>
                        <div>
                            <div class="stat-number text-secondary">{{ $documentosPrioridad['bajas'] }}</div>
                            <small class="text-muted">Baja</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($estadisticasUsuarios)
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-people me-2"></i>
                    Estadísticas de Usuarios
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-around text-center">
                        <div>
                            <div class="stat-number text-primary">{{ $estadisticasUsuarios['total'] }}</div>
                            <small class="text-muted">Total</small>
                        </div>
                        <div>
                            <div class="stat-number text-success">{{ $estadisticasUsuarios['activos'] }}</div>
                            <small class="text-muted">Activos</small>
                        </div>
                        <div>
                            <div class="stat-number text-danger">{{ $estadisticasUsuarios['por_rol']['Admin'] }}</div>
                            <small class="text-muted">Admins</small>
                        </div>
                        <div>
                            <div class="stat-number text-info">{{ $estadisticasUsuarios['por_rol']['Fiscal'] }}</div>
                            <small class="text-muted">Fiscales</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Configuración común para gráficos
    Chart.defaults.font.family = "'Segoe UI', 'Helvetica Neue', 'Arial', sans-serif";
    Chart.defaults.color = '#6c757d';

    // Gráfico de Distribución por Estado (Pie)
    const ctxEstados = document.getElementById('chartEstados').getContext('2d');
    new Chart(ctxEstados, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode(array_keys($datosGrafico['por_estado'])) !!},
            datasets: [{
                data: {!! json_encode(array_values($datosGrafico['por_estado'])) !!},
                backgroundColor: [
                    '#f39c12', // Pendiente - warning
                    '#3498db', // En proceso - info
                    '#27ae60', // Resuelto - success
                    '#6c757d', // Archivado - secondary
                    '#1a5276'  // Derivado - primary
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        usePointStyle: true
                    }
                }
            }
        }
    });

    // Gráfico de Documentos por Tipo (Bar)
    const ctxTipos = document.getElementById('chartTipos').getContext('2d');
    new Chart(ctxTipos, {
        type: 'bar',
        data: {
            labels: {!! json_encode(array_keys($datosGrafico['por_tipo'])) !!},
            datasets: [{
                label: 'Cantidad',
                data: {!! json_encode(array_values($datosGrafico['por_tipo'])) !!},
                backgroundColor: '#1a5276',
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    // Gráfico de Tendencia Mensual (Line)
    const ctxMensual = document.getElementById('chartMensual').getContext('2d');
    new Chart(ctxMensual, {
        type: 'line',
        data: {
            labels: {!! json_encode($datosGrafico['por_mes']['meses']) !!},
            datasets: [{
                label: 'Documentos',
                data: {!! json_encode($datosGrafico['por_mes']['conteos']) !!},
                borderColor: '#2e86ab',
                backgroundColor: 'rgba(46, 134, 171, 0.1)',
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointBackgroundColor: '#2e86ab'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
</script>
@endpush
