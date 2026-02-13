<?php

namespace App\Providers;

use App\Models\Documento;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

/**
 * AuthServiceProvider - Proveedor de servicios de autorización
 * 
 * Registra las políticas de acceso y gates personalizados del sistema.
 */
class AuthServiceProvider extends ServiceProvider
{
    /**
     * Las políticas de la aplicación
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Registra cualquier servicio de autenticación/autorización
     */
    public function boot(): void
    {
        // ============================================
        // GATES PARA DOCUMENTOS
        // ============================================

        /**
         * Gate: Ver documento
         * Admin y Fiscal ven todos, los demás solo sus propios documentos
         */
        Gate::define('ver', function (User $user, Documento $documento) {
            if ($user->hasRole(['Admin', 'Fiscal'])) {
                return true;
            }
            
            if ($user->hasRole('Asistente')) {
                return true;
            }
            
            return $documento->user_id === $user->id;
        });

        /**
         * Gate: Editar documento
         * Admin y Fiscal editan todos, Asistente y Ciudadano solo propios
         */
        Gate::define('editar', function (User $user, Documento $documento) {
            if ($user->hasRole(['Admin', 'Fiscal'])) {
                return true;
            }
            
            if ($user->hasRole('Asistente')) {
                return $documento->user_id === $user->id;
            }
            
            // Ciudadano solo puede editar documentos pendientes o en proceso propios
            if ($user->hasRole('Ciudadano')) {
                return $documento->user_id === $user->id 
                    && in_array($documento->estado, ['Pendiente', 'En proceso']);
            }
            
            return false;
        });

        /**
         * Gate: Eliminar documento
         * Solo Admin puede eliminar cualquier documento
         * Ciudadano puede eliminar sus propios documentos pendientes
         */
        Gate::define('eliminar', function (User $user, Documento $documento) {
            if ($user->hasRole('Admin')) {
                return true;
            }
            
            // Ciudadano solo puede eliminar sus documentos pendientes
            if ($user->hasRole('Ciudadano')) {
                return $documento->user_id === $user->id 
                    && $documento->estado === 'Pendiente';
            }
            
            return false;
        });
    }
}
