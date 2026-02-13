<?php $__env->startSection('title', 'Ver Documento'); ?>

<?php $__env->startSection('content'); ?>
<div class="fade-in">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">
                <i class="bi bi-file-text text-primary me-2"></i>
                Detalle del Documento
            </h2>
            <p class="text-muted mb-0">
                Expediente: <span class="badge bg-light text-dark border font-monospace fs-6"><?php echo e($documento->numero_expediente); ?></span>
            </p>
        </div>
        <div class="d-flex gap-2">
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('editar', $documento)): ?>
            <a href="<?php echo e(route('documentos.edit', $documento)); ?>" class="btn btn-warning">
                <i class="bi bi-pencil me-1"></i> Editar
            </a>
            <?php endif; ?>
            <a href="<?php echo e(route('documentos.index')); ?>" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Volver
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Columna Principal -->
        <div class="col-lg-8">
            <!-- Información Principal -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>
                        <i class="bi bi-info-circle me-2"></i>
                        Información del Documento
                    </span>
                    <span class="badge bg-<?php echo e($documento->estado_color); ?> fs-6">
                        <?php echo e($documento->estado); ?>

                    </span>
                </div>
                <div class="card-body">
                    <!-- Título -->
                    <h4 class="mb-3"><?php echo e($documento->titulo); ?></h4>

                    <!-- Descripción -->
                    <div class="mb-4">
                        <h6 class="text-muted mb-2">
                            <i class="bi bi-text-paragraph me-1"></i> Descripción
                        </h6>
                        <p class="mb-0"><?php echo e($documento->descripcion ?: 'Sin descripción'); ?></p>
                    </div>

                    <!-- Observaciones -->
                    <?php if($documento->observaciones): ?>
                    <div class="mb-4">
                        <h6 class="text-muted mb-2">
                            <i class="bi bi-chat-left-text me-1"></i> Observaciones
                        </h6>
                        <div class="alert alert-light border">
                            <?php echo e($documento->observaciones); ?>

                        </div>
                    </div>
                    <?php endif; ?>

                    <hr>

                    <!-- Detalles en grid -->
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td class="text-muted" width="40%"><i class="bi bi-tag me-1"></i> Tipo:</td>
                                    <td><span class="badge bg-secondary"><?php echo e($documento->tipo); ?></span></td>
                                </tr>
                                <tr>
                                    <td class="text-muted"><i class="bi bi-flag me-1"></i> Prioridad:</td>
                                    <td>
                                        <span class="badge bg-<?php echo e($documento->prioridad_color); ?>">
                                            <?php echo e($documento->prioridad); ?>

                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted"><i class="bi bi-calendar me-1"></i> Fecha Registro:</td>
                                    <td><?php echo e($documento->fecha_registro->format('d/m/Y')); ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td class="text-muted" width="40%"><i class="bi bi-person me-1"></i> Registrado por:</td>
                                    <td><?php echo e($documento->usuario->name); ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted"><i class="bi bi-person-check me-1"></i> Asignado a:</td>
                                    <td><?php echo e($documento->fiscalAsignado?->name ?? 'Sin asignar'); ?></td>
                                </tr>
                                <?php if($documento->fecha_resolucion): ?>
                                <tr>
                                    <td class="text-muted"><i class="bi bi-calendar-check me-1"></i> Fecha Resolución:</td>
                                    <td><?php echo e($documento->fecha_resolucion->format('d/m/Y')); ?></td>
                                </tr>
                                <?php endif; ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Archivo Adjunto -->
            <div class="card mt-4">
                <div class="card-header">
                    <i class="bi bi-paperclip me-2"></i>
                    Archivo Adjunto
                </div>
                <div class="card-body">
                    <?php if($documento->tiene_archivo): ?>
                        <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-file-pdf fs-1 text-danger me-3"></i>
                                <div>
                                    <h6 class="mb-1"><?php echo e($documento->archivo_nombre_original); ?></h6>
                                    <p class="text-muted mb-0">
                                        <small>
                                            <i class="bi bi-hdd me-1"></i> <?php echo e($documento->archivo_tamano_formateado); ?>

                                            <span class="mx-2">|</span>
                                            <i class="bi bi-calendar me-1"></i> Subido el <?php echo e($documento->created_at->format('d/m/Y')); ?>

                                        </small>
                                    </p>
                                </div>
                            </div>
                            <a href="<?php echo e(route('documentos.descargar', $documento)); ?>" 
                               class="btn btn-primary" target="_blank">
                                <i class="bi bi-download me-1"></i> Descargar PDF
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-file-earmark-x fs-1 d-block mb-2 opacity-50"></i>
                            <p class="mb-0">Este documento no tiene archivo adjunto</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Columna Lateral -->
        <div class="col-lg-4">
            <!-- Acciones Rápidas -->
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('cambiar-estado-documento')): ?>
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-lightning me-2"></i>
                    Acciones Rápidas
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <form action="<?php echo e(route('documentos.estado', $documento)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PATCH'); ?>
                            <input type="hidden" name="estado" value="En proceso">
                            <button type="submit" class="btn btn-info w-100" 
                                    <?php echo e($documento->estado == 'En proceso' ? 'disabled' : ''); ?>>
                                <i class="bi bi-arrow-repeat me-1"></i> Marcar En Proceso
                            </button>
                        </form>

                        <form action="<?php echo e(route('documentos.estado', $documento)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PATCH'); ?>
                            <input type="hidden" name="estado" value="Resuelto">
                            <button type="submit" class="btn btn-success w-100"
                                    <?php echo e($documento->estado == 'Resuelto' ? 'disabled' : ''); ?>>
                                <i class="bi bi-check-circle me-1"></i> Marcar Resuelto
                            </button>
                        </form>

                        <form action="<?php echo e(route('documentos.estado', $documento)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PATCH'); ?>
                            <input type="hidden" name="estado" value="Archivado">
                            <button type="submit" class="btn btn-secondary w-100"
                                    <?php echo e($documento->estado == 'Archivado' ? 'disabled' : ''); ?>>
                                <i class="bi bi-archive me-1"></i> Archivar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Información del Sistema -->
            <div class="card mt-4">
                <div class="card-header">
                    <i class="bi bi-info-circle me-2"></i>
                    Información del Sistema
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted">ID:</span>
                            <span class="font-monospace">#<?php echo e($documento->id); ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted">Creado:</span>
                            <span><?php echo e($documento->created_at->format('d/m/Y H:i')); ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted">Modificado:</span>
                            <span><?php echo e($documento->updated_at->format('d/m/Y H:i')); ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted">Estado:</span>
                            <span class="badge bg-<?php echo e($documento->estado_color); ?>"><?php echo e($documento->estado); ?></span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Eliminar Documento -->
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('eliminar', $documento)): ?>
            <div class="card mt-4 border-danger">
                <div class="card-header bg-danger text-white">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Zona de Peligro
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">
                        Eliminar este documento es una acción irreversible. 
                        Se eliminará también el archivo adjunto si existe.
                    </p>
                    <form action="<?php echo e(route('documentos.destroy', $documento)); ?>" method="POST"
                          onsubmit="return confirmarEliminacion(event, '¿Está seguro de eliminar este documento? Esta acción no se puede deshacer.')">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn btn-outline-danger w-100">
                            <i class="bi bi-trash me-1"></i> Eliminar Documento
                        </button>
                    </form>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\sistema-gestion-documentos-mpfn\resources\views/documentos/show.blade.php ENDPATH**/ ?>