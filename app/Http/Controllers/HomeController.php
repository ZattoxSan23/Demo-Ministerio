<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * HomeController - Controlador del Dashboard Principal
 * 
 * Gestiona la vista principal del sistema mostrando:
 * - Estadísticas de documentos por estado
 * - Gráficos de distribución
 * - Últimos trámites registrados
 * - Actividad reciente
 * 
 * @package App\Http\Controllers
 */
class HomeController extends Controller
{
    /**
     * Constructor - Aplica middleware de autenticación
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Muestra el dashboard principal
     * 
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        // Obtener estadísticas según rol del usuario
        $estadisticas = $this->obtenerEstadisticas($user);

        // Obtener datos para gráficos
        $datosGrafico = $this->obtenerDatosGrafico($user);

        // Obtener últimos documentos
        $ultimosDocumentos = $this->obtenerUltimosDocumentos($user);

        // Obtener documentos por prioridad
        $documentosPrioridad = $this->obtenerDocumentosPorPrioridad($user);

        // Obtener conteo de usuarios (solo para Admin)
        $estadisticasUsuarios = $user->hasRole('Admin') 
            ? $this->obtenerEstadisticasUsuarios() 
            : null;

        return view('dashboard', compact(
            'estadisticas',
            'datosGrafico',
            'ultimosDocumentos',
            'documentosPrioridad',
            'estadisticasUsuarios'
        ));
    }

    /**
     * Obtiene estadísticas de documentos por estado
     * 
     * @param User $user
     * @return array
     */
    private function obtenerEstadisticas(User $user): array
    {
        $queryBase = Documento::query();

        // Filtrar según rol
        if ($user->hasRole('Ciudadano')) {
            $queryBase->where('user_id', $user->id);
        }

        return [
            'total' => (clone $queryBase)->count(),
            'pendientes' => (clone $queryBase)->porEstado('Pendiente')->count(),
            'en_proceso' => (clone $queryBase)->porEstado('En proceso')->count(),
            'resueltos' => (clone $queryBase)->porEstado('Resuelto')->count(),
            'archivados' => (clone $queryBase)->porEstado('Archivado')->count(),
            'derivados' => (clone $queryBase)->porEstado('Derivado')->count(),
        ];
    }

    /**
     * Obtiene datos para el gráfico de distribución
     * 
     * @param User $user
     * @return array
     */
    private function obtenerDatosGrafico(User $user): array
    {
        $queryBase = Documento::query();

        // Filtrar según rol
        if ($user->hasRole('Ciudadano')) {
            $queryBase->where('user_id', $user->id);
        }

        // Datos para gráfico de estados
        $porEstado = [
            'Pendiente' => (clone $queryBase)->porEstado('Pendiente')->count(),
            'En proceso' => (clone $queryBase)->porEstado('En proceso')->count(),
            'Resuelto' => (clone $queryBase)->porEstado('Resuelto')->count(),
            'Archivado' => (clone $queryBase)->porEstado('Archivado')->count(),
            'Derivado' => (clone $queryBase)->porEstado('Derivado')->count(),
        ];

        // Datos para gráfico de tipos
        $porTipo = [];
        foreach (Documento::TIPOS as $tipo => $label) {
            $porTipo[$label] = (clone $queryBase)->porTipo($tipo)->count();
        }

        // Datos para gráfico mensual (últimos 6 meses)
        $porMes = $this->obtenerDocumentosPorMes($user);

        return [
            'por_estado' => $porEstado,
            'por_tipo' => $porTipo,
            'por_mes' => $porMes,
        ];
    }

    /**
     * Obtiene documentos agrupados por mes
     * 
     * @param User $user
     * @return array
     */
    private function obtenerDocumentosPorMes(User $user): array
    {
        $meses = [];
        $conteos = [];

        for ($i = 5; $i >= 0; $i--) {
            $fecha = now()->subMonths($i);
            $meses[] = $fecha->format('M Y');

            $query = Documento::whereYear('fecha_registro', $fecha->year)
                ->whereMonth('fecha_registro', $fecha->month);

            if ($user->hasRole('Ciudadano')) {
                $query->where('user_id', $user->id);
            }

            $conteos[] = $query->count();
        }

        return [
            'meses' => $meses,
            'conteos' => $conteos,
        ];
    }

    /**
     * Obtiene los últimos documentos registrados
     * 
     * @param User $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function obtenerUltimosDocumentos(User $user)
    {
        $query = Documento::with(['usuario', 'fiscalAsignado'])
            ->orderBy('created_at', 'desc')
            ->limit(10);

        // Filtrar según rol
        if ($user->hasRole('Ciudadano')) {
            $query->where('user_id', $user->id);
        }

        return $query->get();
    }

    /**
     * Obtiene documentos agrupados por prioridad
     * 
     * @param User $user
     * @return array
     */
    private function obtenerDocumentosPorPrioridad(User $user): array
    {
        $queryBase = Documento::query();

        if ($user->hasRole('Ciudadano')) {
            $queryBase->where('user_id', $user->id);
        }

        return [
            'urgentes' => (clone $queryBase)->where('prioridad', 'Urgente')->count(),
            'altas' => (clone $queryBase)->where('prioridad', 'Alta')->count(),
            'medias' => (clone $queryBase)->where('prioridad', 'Media')->count(),
            'bajas' => (clone $queryBase)->where('prioridad', 'Baja')->count(),
        ];
    }

    /**
     * Obtiene estadísticas de usuarios (solo Admin)
     * 
     * @return array
     */
    private function obtenerEstadisticasUsuarios(): array
    {
        return [
            'total' => User::count(),
            'activos' => User::where('activo', true)->count(),
            'inactivos' => User::where('activo', false)->count(),
            'por_rol' => [
                'Admin' => User::role('Admin')->count(),
                'Fiscal' => User::role('Fiscal')->count(),
                'Asistente' => User::role('Asistente')->count(),
                'Ciudadano' => User::role('Ciudadano')->count(),
            ],
        ];
    }

    /**
     * API: Obtiene datos para actualización AJAX del dashboard
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function datosActualizados(Request $request)
    {
        $user = auth()->user();

        return response()->json([
            'estadisticas' => $this->obtenerEstadisticas($user),
            'datosGrafico' => $this->obtenerDatosGrafico($user),
            'ultimosDocumentos' => $this->obtenerUltimosDocumentos($user)->map(function ($doc) {
                return [
                    'id' => $doc->id,
                    'titulo' => $doc->titulo,
                    'estado' => $doc->estado,
                    'estado_color' => $doc->estado_color,
                    'fecha' => $doc->fecha_registro->format('d/m/Y'),
                    'usuario' => $doc->usuario->name,
                ];
            }),
        ]);
    }
}
