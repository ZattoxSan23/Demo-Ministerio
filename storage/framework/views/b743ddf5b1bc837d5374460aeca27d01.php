<?php $__env->startSection('title', 'Listado de Documentos'); ?>

<?php $__env->startSection('content'); ?>
<div class="fade-in">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">
                <i class="bi bi-folder text-primary me-2"></i>
                Documentos y Trámites
            </h2>
            <p class="text-muted mb-0">Gestión de documentos del sistema</p>
        </div>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('crear-documentos')): ?>
        <a href="<?php echo e(route('documentos.create')); ?>" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Nuevo Documento
        </a>
        <?php endif; ?>
    </div>

    <!-- Cards de Resumen -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card bg-warning bg-opacity-10 border-warning">
                <div class="card-body text-center">
                    <h3 class="text-warning mb-1"><?php echo e($contadores['pendientes']); ?></h3>
                    <small class="text-muted">Pendientes</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card bg-info bg-opacity-10 border-info">
                <div class="card-body text-center">
                    <h3 class="text-info mb-1"><?php echo e($contadores['en_proceso']); ?></h3>
                    <small class="text-muted">En Proceso</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card bg-success bg-opacity-10 border-success">
                <div class="card-body text-center">
                    <h3 class="text-success mb-1"><?php echo e($contadores['resueltos']); ?></h3>
                    <small class="text-muted">Resueltos</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card bg-primary bg-opacity-10 border-primary">
                <div class="card-body text-center">
                    <h3 class="text-primary mb-1"><?php echo e($contadores['total']); ?></h3>
                    <small class="text-muted">Total</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="bi bi-funnel me-2"></i> Filtros de Búsqueda
        </div>
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('documentos.index')); ?>">
                <div class="row g-3">
                    <!-- Búsqueda -->
                    <div class="col-md-4">
                        <label class="form-label">Buscar</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" name="buscar" class="form-control" 
                                   placeholder="Título, descripción o expediente..."
                                   value="<?php echo e(request('buscar')); ?>">
                        </div>
                    </div>

                    <!-- Estado -->
                    <div class="col-md-2">
                        <label class="form-label">Estado</label>
                        <select name="estado" class="form-select">
                            <option value="">Todos</option>
                            <?php $__currentLoopData = $estados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $valor => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($valor); ?>" <?php echo e(request('estado') == $valor ? 'selected' : ''); ?>>
                                    <?php echo e($label); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <!-- Tipo -->
                    <div class="col-md-2">
                        <label class="form-label">Tipo</label>
                        <select name="tipo" class="form-select">
                            <option value="">Todos</option>
                            <?php $__currentLoopData = $tipos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $valor => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($valor); ?>" <?php echo e(request('tipo') == $valor ? 'selected' : ''); ?>>
                                    <?php echo e($label); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <!-- Fecha Inicio -->
                    <div class="col-md-2">
                        <label class="form-label">Desde</label>
                        <input type="date" name="fecha_inicio" class="form-control" 
                               value="<?php echo e(request('fecha_inicio')); ?>">
                    </div>

                    <!-- Fecha Fin -->
                    <div class="col-md-2">
                        <label class="form-label">Hasta</label>
                        <input type="date" name="fecha_fin" class="form-control" 
                               value="<?php echo e(request('fecha_fin')); ?>">
                    </div>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('ver-todos-documentos')): ?>
                    <!-- Fiscal Asignado -->
                    <div class="col-md-3">
                        <label class="form-label">Fiscal Asignado</label>
                        <select name="fiscal_id" class="form-select">
                            <option value="">Todos</option>
                            <?php $__currentLoopData = $fiscales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fiscal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($fiscal->id); ?>" <?php echo e(request('fiscal_id') == $fiscal->id ? 'selected' : ''); ?>>
                                    <?php echo e($fiscal->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <?php endif; ?>

                    <!-- Botones -->
                    <div class="col-md-3 <?php echo e(Auth::user()->can('ver-todos-documentos') ? '' : 'offset-md-6'); ?>">
                        <label class="form-label d-block">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-fill">
                                <i class="bi bi-search me-1"></i> Filtrar
                            </button>
                            <a href="<?php echo e(route('documentos.index')); ?>" class="btn btn-outline-secondary">
                                <i class="bi bi-x-lg"></i>
                            </a>
                        </div>
                    </div>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('generar-pdf')): ?>
                    <div class="col-md-2">
                        <label class="form-label d-block">&nbsp;</label>
                        <button type="submit" formaction="<?php echo e(route('documentos.pdf')); ?>" 
                                class="btn btn-danger w-100">
                            <i class="bi bi-file-pdf me-1"></i> PDF
                        </button>
                    </div>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de Documentos -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>
                <i class="bi bi-list me-2"></i>
                Listado de Documentos
            </span>
            <span class="badge bg-primary"><?php echo e($documentos->total()); ?> registros</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>N° Expediente</th>
                            <th>Título</th>
                            <th>Tipo</th>
                            <th>Estado</th>
                            <th>Prioridad</th>
                            <th>Fecha Reg.</th>
                            <th>Registrado por</th>
                            <th width="120">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $documentos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <span class="badge bg-light text-dark border font-monospace">
                                    <?php echo e($doc->numero_expediente); ?>

                                </span>
                            </td>
                            <td>
                                <a href="<?php echo e(route('documentos.show', $doc)); ?>" class="text-decoration-none fw-medium">
                                    <?php echo e(Str::limit($doc->titulo, 35)); ?>

                                </a>
                                <?php if($doc->tiene_archivo): ?>
                                    <i class="bi bi-paperclip text-muted ms-1" title="Tiene archivo adjunto"></i>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge bg-secondary bg-opacity-75"><?php echo e($doc->tipo); ?></span>
                            </td>
                            <td>
                                <span class="badge bg-<?php echo e($doc->estado_color); ?>">
                                    <?php echo e($doc->estado); ?>

                                </span>
                            </td>
                            <td>
                                <span class="badge bg-<?php echo e($doc->prioridad_color); ?>">
                                    <?php echo e($doc->prioridad); ?>

                                </span>
                            </td>
                            <td><?php echo e($doc->fecha_registro->format('d/m/Y')); ?></td>
                            <td>
                                <small><?php echo e(Str::limit($doc->usuario->name, 18)); ?></small>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="<?php echo e(route('documentos.show', $doc)); ?>" 
                                       class="btn btn-outline-primary" title="Ver">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('editar', $doc)): ?>
                                    <a href="<?php echo e(route('documentos.edit', $doc)); ?>" 
                                       class="btn btn-outline-warning" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <?php endif; ?>
                                    
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('eliminar', $doc)): ?>
                                    <form action="<?php echo e(route('documentos.destroy', $doc)); ?>" method="POST" 
                                          class="d-inline" 
                                          onsubmit="return confirmarEliminacion(event, '¿Eliminar este documento?')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-outline-danger" title="Eliminar">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-3 opacity-50"></i>
                                <h5>No se encontraron documentos</h5>
                                <p class="mb-0">Intenta ajustar los filtros de búsqueda</p>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <?php if($documentos->hasPages()): ?>
        <div class="card-footer">
            <?php echo e($documentos->links()); ?>

        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\sistema-gestion-documentos-mpfn\resources\views/documentos/index.blade.php ENDPATH**/ ?>