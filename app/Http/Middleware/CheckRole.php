<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * CheckRole - Middleware para verificar roles de usuario
 * 
 * Verifica que el usuario autenticado tenga al menos uno de los roles
 * especificados en la ruta.
 * 
 * Uso: ->middleware('check.role:Admin,Fiscal')
 */
class CheckRole
{
    /**
     * Maneja la solicitud entrante
     * 
     * @param Request $request
     * @param Closure $next
     * @param string ...$roles
     * @return Response
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        // Verificar que el usuario esté autenticado
        if (!$user) {
            return redirect()->route('login');
        }

        // Verificar si tiene alguno de los roles requeridos
        foreach ($roles as $role) {
            if ($user->hasRole($role)) {
                return $next($request);
            }
        }

        // Si no tiene el rol, redirigir con mensaje de error
        return redirect()
            ->route('dashboard')
            ->with('error', 'No tiene permisos para acceder a esta sección.');
    }
}
