<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($titulo); ?></title>
    <style>
        /* Estilos base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11pt;
            line-height: 1.4;
            color: #333;
        }

        /* Header del reporte */
        .header {
            text-align: center;
            padding: 20px 0;
            border-bottom: 3px solid #1a5276;
            margin-bottom: 20px;
        }

        .header-logo {
            font-size: 24pt;
            color: #1a5276;
            margin-bottom: 10px;
        }

        .header-title {
            font-size: 16pt;
            font-weight: bold;
            color: #1a5276;
            margin-bottom: 5px;
        }

        .header-subtitle {
            font-size: 12pt;
            color: #666;
        }

        /* Información del reporte */
        .report-info {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .report-info table {
            width: 100%;
        }

        .report-info td {
            padding: 3px 0;
        }

        .report-info .label {
            font-weight: bold;
            color: #555;
            width: 30%;
        }

        /* Filtros aplicados */
        .filtros {
            background-color: #e8f4f8;
            padding: 10px 15px;
            border-left: 4px solid #1a5276;
            margin-bottom: 20px;
            font-size: 10pt;
        }

        .filtros strong {
            color: #1a5276;
        }

        /* Tabla de datos */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 9pt;
        }

        .data-table thead {
            background-color: #1a5276;
            color: white;
        }

        .data-table th {
            padding: 10px 8px;
            text-align: left;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 8pt;
        }

        .data-table td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }

        .data-table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .data-table tbody tr:hover {
            background-color: #e8f4f8;
        }

        /* Badges de estado */
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 8pt;
            font-weight: bold;
            text-transform: uppercase;
        }

        .badge-pendiente { background-color: #f39c12; color: white; }
        .badge-proceso { background-color: #3498db; color: white; }
        .badge-resuelto { background-color: #27ae60; color: white; }
        .badge-archivado { background-color: #6c757d; color: white; }
        .badge-derivado { background-color: #1a5276; color: white; }

        /* Badges de prioridad */
        .prioridad-baja { color: #27ae60; }
        .prioridad-media { color: #3498db; }
        .prioridad-alta { color: #f39c12; }
        .prioridad-urgente { color: #c0392b; font-weight: bold; }

        /* Resumen */
        .resumen {
            margin-top: 30px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }

        .resumen-title {
            font-size: 12pt;
            font-weight: bold;
            color: #1a5276;
            margin-bottom: 10px;
        }

        .resumen-grid {
            display: table;
            width: 100%;
        }

        .resumen-item {
            display: table-cell;
            text-align: center;
            padding: 10px;
        }

        .resumen-number {
            font-size: 18pt;
            font-weight: bold;
            color: #1a5276;
        }

        .resumen-label {
            font-size: 9pt;
            color: #666;
            text-transform: uppercase;
        }

        /* Footer */
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 8pt;
            color: #666;
        }

        /* Utilidades */
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-monospace { font-family: 'Courier New', monospace; }
        .text-muted { color: #666; }
        .small { font-size: 9pt; }

        /* Saltos de página */
        .page-break {
            page-break-after: always;
        }

        /* Para evitar que las filas se corten */
        tr {
            page-break-inside: avoid;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="header-logo">⚖</div>
        <div class="header-title">MINISTERIO PÚBLICO</div>
        <div class="header-subtitle">Sistema de Gestión de Documentos y Trámites</div>
    </div>

    <!-- Información del Reporte -->
    <div class="report-info">
        <table>
            <tr>
                <td class="label">Título:</td>
                <td><?php echo e($titulo); ?></td>
            </tr>
            <tr>
                <td class="label">Fecha de generación:</td>
                <td><?php echo e($fecha); ?></td>
            </tr>
            <tr>
                <td class="label">Generado por:</td>
                <td><?php echo e($usuario); ?> (<?php echo e($rol); ?>)</td>
            </tr>
            <tr>
                <td class="label">Total de registros:</td>
                <td><strong><?php echo e($total); ?></strong></td>
            </tr>
        </table>
    </div>

    <!-- Filtros Aplicados -->
    <?php if($filtros['buscar'] || $filtros['estado'] || $filtros['tipo'] || $filtros['fecha_inicio'] || $filtros['fecha_fin']): ?>
    <div class="filtros">
        <strong>Filtros aplicados:</strong>
        <?php if($filtros['buscar']): ?> <span>| Búsqueda: "<?php echo e($filtros['buscar']); ?>"</span> <?php endif; ?>
        <?php if($filtros['estado']): ?> <span>| Estado: <?php echo e($filtros['estado']); ?></span> <?php endif; ?>
        <?php if($filtros['tipo']): ?> <span>| Tipo: <?php echo e($filtros['tipo']); ?></span> <?php endif; ?>
        <?php if($filtros['fecha_inicio']): ?> <span>| Desde: <?php echo e($filtros['fecha_inicio']); ?></span> <?php endif; ?>
        <?php if($filtros['fecha_fin']): ?> <span>| Hasta: <?php echo e($filtros['fecha_fin']); ?></span> <?php endif; ?>
    </div>
    <?php endif; ?>

    <!-- Tabla de Documentos -->
    <table class="data-table">
        <thead>
            <tr>
                <th width="12%">N° Expediente</th>
                <th width="25%">Título</th>
                <th width="10%">Tipo</th>
                <th width="10%">Estado</th>
                <th width="8%">Prior.</th>
                <th width="10%">Fecha Reg.</th>
                <th width="15%">Registrado por</th>
                <th width="10%">Asignado a</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $documentos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td class="font-monospace"><?php echo e($doc->numero_expediente); ?></td>
                <td><?php echo e(Str::limit($doc->titulo, 50)); ?></td>
                <td><?php echo e($doc->tipo); ?></td>
                <td>
                    <span class="badge badge-<?php echo e(strtolower(str_replace(' ', '-', $doc->estado))); ?>">
                        <?php echo e($doc->estado); ?>

                    </span>
                </td>
                <td class="prioridad-<?php echo e(strtolower($doc->prioridad)); ?>">
                    <?php echo e($doc->prioridad); ?>

                </td>
                <td><?php echo e($doc->fecha_registro->format('d/m/Y')); ?></td>
                <td><?php echo e($doc->usuario->name); ?></td>
                <td><?php echo e($doc->fiscalAsignado?->name ?? 'N/A'); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="8" class="text-center" style="padding: 20px;">
                    No se encontraron documentos con los filtros aplicados.
                </td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Resumen -->
    <?php if($documentos->count() > 0): ?>
    <div class="resumen">
        <div class="resumen-title">Resumen por Estado</div>
        <div class="resumen-grid">
            <div class="resumen-item">
                <div class="resumen-number"><?php echo e($documentos->where('estado', 'Pendiente')->count()); ?></div>
                <div class="resumen-label">Pendientes</div>
            </div>
            <div class="resumen-item">
                <div class="resumen-number"><?php echo e($documentos->where('estado', 'En proceso')->count()); ?></div>
                <div class="resumen-label">En Proceso</div>
            </div>
            <div class="resumen-item">
                <div class="resumen-number"><?php echo e($documentos->where('estado', 'Resuelto')->count()); ?></div>
                <div class="resumen-label">Resueltos</div>
            </div>
            <div class="resumen-item">
                <div class="resumen-number"><?php echo e($documentos->where('estado', 'Archivado')->count()); ?></div>
                <div class="resumen-label">Archivados</div>
            </div>
            <div class="resumen-item">
                <div class="resumen-number"><?php echo e($documentos->where('estado', 'Derivado')->count()); ?></div>
                <div class="resumen-label">Derivados</div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Footer -->
    <div class="footer">
        <p>Este es un documento generado automáticamente por el Sistema de Gestión de Documentos y Trámites.</p>
        <p>© <?php echo e(date('Y')); ?> Ministerio Público - Demo para postulación CAS</p>
        <p class="small">Página 1 de 1</p>
    </div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\sistema-gestion-documentos-mpfn\resources\views/pdf/reporte-documentos.blade.php ENDPATH**/ ?>