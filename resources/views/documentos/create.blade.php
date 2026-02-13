@extends('layouts.app')

@section('title', 'Nuevo Documento')

@section('content')
<div class="fade-in">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">
                <i class="bi bi-plus-circle text-primary me-2"></i>
                Nuevo Documento
            </h2>
            <p class="text-muted mb-0">Registre un nuevo documento o trámite en el sistema</p>
        </div>
        <a href="{{ route('documentos.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Volver
        </a>
    </div>

    <form action="{{ route('documentos.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row g-4">
            <!-- Columna Principal -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-file-text me-2"></i>
                        Información del Documento
                    </div>
                    <div class="card-body">
                        <!-- Título -->
                        <div class="mb-3">
                            <label for="titulo" class="form-label">
                                Título <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('titulo') is-invalid @enderror" 
                                   id="titulo" 
                                   name="titulo" 
                                   value="{{ old('titulo') }}"
                                   placeholder="Ingrese el título del documento"
                                   required
                                   maxlength="255">
                            @error('titulo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Máximo 255 caracteres</div>
                        </div>

                        <!-- Descripción -->
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                                      id="descripcion" 
                                      name="descripcion" 
                                      rows="4"
                                      placeholder="Describa el contenido o detalles del documento">{{ old('descripcion') }}</textarea>
                            @error('descripcion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Observaciones -->
                        <div class="mb-3">
                            <label for="observaciones" class="form-label">Observaciones</label>
                            <textarea class="form-control @error('observaciones') is-invalid @enderror" 
                                      id="observaciones" 
                                      name="observaciones" 
                                      rows="2"
                                      placeholder="Observaciones adicionales (opcional)">{{ old('observaciones') }}</textarea>
                            @error('observaciones')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Archivo Adjunto -->
                <div class="card mt-4">
                    <div class="card-header">
                        <i class="bi bi-paperclip me-2"></i>
                        Archivo Adjunto (PDF)
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="archivo" class="form-label">Seleccionar archivo PDF</label>
                            <input type="file" 
                                   class="form-control @error('archivo') is-invalid @enderror" 
                                   id="archivo" 
                                   name="archivo"
                                   accept="application/pdf">
                            @error('archivo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <i class="bi bi-info-circle me-1"></i>
                                Tamaño máximo: 5MB. Solo archivos PDF.
                            </div>
                        </div>

                        <!-- Vista previa del archivo -->
                        <div id="vistaPrevia" class="d-none mt-3">
                            <div class="alert alert-info d-flex align-items-center">
                                <i class="bi bi-file-pdf fs-4 me-3 text-danger"></i>
                                <div>
                                    <strong>Archivo seleccionado:</strong>
                                    <span id="nombreArchivo" class="ms-2"></span><br>
                                    <small id="tamanoArchivo" class="text-muted"></small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Columna Lateral -->
            <div class="col-lg-4">
                <!-- Clasificación -->
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-tags me-2"></i>
                        Clasificación
                    </div>
                    <div class="card-body">
                        <!-- Tipo -->
                        <div class="mb-3">
                            <label for="tipo" class="form-label">
                                Tipo <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('tipo') is-invalid @enderror" 
                                    id="tipo" 
                                    name="tipo" 
                                    required>
                                <option value="">Seleccione tipo</option>
                                @foreach($tipos as $valor => $label)
                                    <option value="{{ $valor }}" {{ old('tipo') == $valor ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('tipo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Estado -->
                        <div class="mb-3">
                            <label for="estado" class="form-label">
                                Estado <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('estado') is-invalid @enderror" 
                                    id="estado" 
                                    name="estado" 
                                    required>
                                @foreach($estados as $valor => $label)
                                    <option value="{{ $valor }}" {{ old('estado', 'Pendiente') == $valor ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('estado')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Prioridad -->
                        <div class="mb-3">
                            <label for="prioridad" class="form-label">
                                Prioridad <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('prioridad') is-invalid @enderror" 
                                    id="prioridad" 
                                    name="prioridad" 
                                    required>
                                @foreach($prioridades as $valor => $label)
                                    <option value="{{ $valor }}" {{ old('prioridad', 'Media') == $valor ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('prioridad')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Fechas -->
                <div class="card mt-4">
                    <div class="card-header">
                        <i class="bi bi-calendar me-2"></i>
                        Fechas
                    </div>
                    <div class="card-body">
                        <!-- Fecha de Registro -->
                        <div class="mb-3">
                            <label for="fecha_registro" class="form-label">
                                Fecha de Registro <span class="text-danger">*</span>
                            </label>
                            <input type="date" 
                                   class="form-control @error('fecha_registro') is-invalid @enderror" 
                                   id="fecha_registro" 
                                   name="fecha_registro"
                                   value="{{ old('fecha_registro', date('Y-m-d')) }}"
                                   required>
                            @error('fecha_registro')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Asignación -->
                @can('asignar-documentos')
                <div class="card mt-4">
                    <div class="card-header">
                        <i class="bi bi-person-check me-2"></i>
                        Asignación
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="asignado_a" class="form-label">Asignar a Fiscal</label>
                            <select class="form-select @error('asignado_a') is-invalid @enderror" 
                                    id="asignado_a" 
                                    name="asignado_a">
                                <option value="">Sin asignar</option>
                                @foreach($fiscales as $fiscal)
                                    <option value="{{ $fiscal->id }}" {{ old('asignado_a') == $fiscal->id ? 'selected' : '' }}>
                                        {{ $fiscal->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('asignado_a')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                @endcan

                <!-- Botones de Acción -->
                <div class="d-grid gap-2 mt-4">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="bi bi-check-circle me-2"></i>
                        Guardar Documento
                    </button>
                    <a href="{{ route('documentos.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle me-2"></i>
                        Cancelar
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    // Mostrar información del archivo seleccionado
    document.getElementById('archivo').addEventListener('change', function() {
        const vistaPrevia = document.getElementById('vistaPrevia');
        const nombreArchivo = document.getElementById('nombreArchivo');
        const tamanoArchivo = document.getElementById('tamanoArchivo');

        if (this.files && this.files[0]) {
            const archivo = this.files[0];
            
            // Validar que sea PDF
            if (archivo.type !== 'application/pdf') {
                alert('El archivo debe ser un PDF.');
                this.value = '';
                vistaPrevia.classList.add('d-none');
                return;
            }

            // Validar tamaño (5MB)
            if (archivo.size > 5 * 1024 * 1024) {
                alert('El archivo no puede superar los 5MB.');
                this.value = '';
                vistaPrevia.classList.add('d-none');
                return;
            }

            // Mostrar vista previa
            nombreArchivo.textContent = archivo.name;
            tamanoArchivo.textContent = (archivo.size / 1024 / 1024).toFixed(2) + ' MB';
            vistaPrevia.classList.remove('d-none');
        } else {
            vistaPrevia.classList.add('d-none');
        }
    });
</script>
@endpush
