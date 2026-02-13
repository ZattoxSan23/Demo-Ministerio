<?php

use App\Http\Controllers\DocumentoController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Aquí se registran todas las rutas web de la aplicación.
| Estas rutas son cargadas por el RouteServiceProvider.
|
*/

// ============================================
// RUTAS PÚBLICAS
// ============================================

Route::get('/', function () {
    return redirect()->route('login');
});

// ============================================
// RUTAS DE AUTENTICACIÓN (Laravel Breeze)
// ============================================

require __DIR__.'/auth.php';

// ============================================
// RUTAS PROTEGIDAS - Requieren autenticación
// ============================================

Route::middleware('auth')->group(function () {
    
    // ----------------------------------------
    // DASHBOARD PRINCIPAL
    // ----------------------------------------
    Route::get('/dashboard', [HomeController::class, 'index'])
        ->name('dashboard');
    
    // API para datos actualizados del dashboard (AJAX)
    Route::get('/dashboard/datos', [HomeController::class, 'datosActualizados'])
        ->name('dashboard.datos');

    // ----------------------------------------
    // GESTIÓN DE DOCUMENTOS / TRÁMITES
    // ----------------------------------------
    
    // Listado con filtros y búsqueda
    Route::get('/documentos', [DocumentoController::class, 'index'])
        ->name('documentos.index');
    
    // Crear nuevo documento
    Route::get('/documentos/create', [DocumentoController::class, 'create'])
        ->name('documentos.create')
        ->middleware('can:crear-documentos');
    
    Route::post('/documentos', [DocumentoController::class, 'store'])
        ->name('documentos.store')
        ->middleware('can:crear-documentos');
    
    // Ver detalle de documento
    Route::get('/documentos/{documento}', [DocumentoController::class, 'show'])
        ->name('documentos.show');
    
    // Descargar archivo adjunto
    Route::get('/documentos/{documento}/descargar', [DocumentoController::class, 'descargarArchivo'])
        ->name('documentos.descargar');
    
    // Editar documento
    Route::get('/documentos/{documento}/edit', [DocumentoController::class, 'edit'])
        ->name('documentos.edit')
        ->middleware('can:editar,documento');
    
    Route::put('/documentos/{documento}', [DocumentoController::class, 'update'])
        ->name('documentos.update')
        ->middleware('can:editar,documento');
    
    // Eliminar documento
    Route::delete('/documentos/{documento}', [DocumentoController::class, 'destroy'])
        ->name('documentos.destroy')
        ->middleware('can:eliminar,documento');
    
    // Cambiar estado (AJAX)
    Route::patch('/documentos/{documento}/estado', [DocumentoController::class, 'cambiarEstado'])
        ->name('documentos.estado')
        ->middleware('can:cambiar-estado-documento');
    
    // Generar reporte PDF
    Route::get('/documentos/reporte/pdf', [DocumentoController::class, 'generarPdf'])
        ->name('documentos.pdf')
        ->middleware('can:generar-pdf');

    // ----------------------------------------
    // PERFIL DE USUARIO
    // ----------------------------------------
    
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');
    
    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');
    
    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

});

// ============================================
// RUTAS ADMINISTRATIVAS (Solo Admin)
// ============================================

Route::middleware(['auth', 'role:Admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Gestión de usuarios (ejemplo para futura expansión)
    // Route::get('/usuarios', [UserController::class, 'index'])->name('usuarios.index');
    
});

// ============================================
// RUTAS DE PRUEBA/DESARROLLO
// ============================================

if (app()->environment('local')) {
    Route::get('/test-roles', function () {
        $user = auth()->user();
        return [
            'usuario' => $user?->name,
            'roles' => $user?->getRoleNames(),
            'permisos' => $user?->getPermissionNames(),
        ];
    })->middleware('auth');
}
