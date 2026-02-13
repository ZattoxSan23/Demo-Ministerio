<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDocumentoRequest;
use App\Http\Requests\UpdateDocumentoRequest;
use App\Models\Documento;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * DocumentoController - Controlador de Documentos y Trámites
 * 
 * Gestiona todas las operaciones CRUD de documentos incluyendo:
 * - Listado con filtros y búsqueda
 * - Creación con upload de archivos PDF
 * - Edición y actualización
 * - Eliminación lógica
 * - Generación de reportes PDF
 * 
 * @package App\Http\Controllers
 */
class DocumentoController extends Controller
{
    /**
     * Constructor - Aplica middleware de autenticación
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Muestra el listado paginado de documentos con filtros
     * 
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Query base según permisos del usuario
        $query = Documento::with(['usuario', 'fiscalAsignado']);
        
        // Filtrar según rol
        if ($user->hasRole('Ciudadano')) {
            // Ciudadano solo ve sus propios documentos
            $query->where('user_id', $user->id);
        }
        // Admin y Fiscal ven todos los documentos
        // Asistente ve todos los documentos

        // Aplicar filtros de búsqueda
        $this->aplicarFiltros($query, $request);

        // Ordenar por fecha de registro descendente
        $query->orderBy('fecha_registro', 'desc');

        // Paginación con 10 registros por página
        $documentos = $query->paginate(10)->withQueryString();

        // Obtener datos para filtros
        $tipos = Documento::TIPOS;
        $estados = Documento::ESTADOS;
        $fiscales = User::role('Fiscal')->activos()->get();

        // Contadores para cards
        $contadores = $this->obtenerContadores($user);

        return view('documentos.index', compact(
            'documentos',
            'tipos',
            'estados',
            'fiscales',
            'contadores'
        ));
    }

    /**
     * Aplica filtros de búsqueda al query
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param Request $request
     * @return void
     */
    private function aplicarFiltros($query, Request $request): void
    {
        // Filtro por término de búsqueda (título, descripción, expediente)
        if ($request->filled('buscar')) {
            $query->buscar($request->input('buscar'));
        }

        // Filtro por estado
        if ($request->filled('estado')) {
            $query->porEstado($request->input('estado'));
        }

        // Filtro por tipo
        if ($request->filled('tipo')) {
            $query->porTipo($request->input('tipo'));
        }

        // Filtro por fecha inicio
        if ($request->filled('fecha_inicio')) {
            $query->whereDate('fecha_registro', '>=', $request->input('fecha_inicio'));
        }

        // Filtro por fecha fin
        if ($request->filled('fecha_fin')) {
            $query->whereDate('fecha_registro', '<=', $request->input('fecha_fin'));
        }

        // Filtro por fiscal asignado (solo Admin y Fiscal)
        if ($request->filled('fiscal_id') && auth()->user()->can('ver-todos-documentos')) {
            $query->where('asignado_a', $request->input('fiscal_id'));
        }
    }

    /**
     * Obtiene contadores de documentos por estado
     * 
     * @param User $user
     * @return array
     */
    private function obtenerContadores(User $user): array
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
        ];
    }

    /**
     * Muestra el formulario para crear un nuevo documento
     * 
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Verificar permiso
        $this->authorize('crear-documentos');

        $tipos = Documento::TIPOS;
        $estados = Documento::ESTADOS;
        $prioridades = Documento::PRIORIDADES;
        $fiscales = User::role('Fiscal')->activos()->get();

        return view('documentos.create', compact('tipos', 'estados', 'prioridades', 'fiscales'));
    }

    /**
     * Almacena un nuevo documento en la base de datos
     * 
     * @param StoreDocumentoRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreDocumentoRequest $request)
    {
        // La validación ya se realizó en StoreDocumentoRequest
        
        $data = $request->validated();
        
        // Asignar el usuario actual como registrante
        $data['user_id'] = auth()->id();

        // Manejar upload de archivo PDF
        if ($request->hasFile('archivo')) {
            $data = $this->procesarArchivo($request, $data);
        }

        // Crear el documento
        $documento = Documento::create($data);

        return redirect()
            ->route('documentos.show', $documento)
            ->with('success', 'Documento registrado exitosamente. N° Expediente: ' . $documento->numero_expediente);
    }

    /**
     * Procesa el archivo PDF subido
     * 
     * @param StoreDocumentoRequest|UpdateDocumentoRequest $request
     * @param array $data
     * @return array
     */
    private function procesarArchivo($request, array $data): array
    {
        $archivo = $request->file('archivo');
        
        // Validación de seguridad: verificar MIME type real
        $mimeType = $archivo->getMimeType();
        if ($mimeType !== 'application/pdf') {
            throw new \Exception('El archivo debe ser un PDF válido.');
        }

        // Generar nombre único para el archivo
        $nombreOriginal = $archivo->getClientOriginalName();
        $nombreUnico = uniqid('doc_') . '_' . time() . '.pdf';
        
        // Almacenar en storage/app/public/documentos
        $ruta = $archivo->storeAs('documentos', $nombreUnico, 'public');

        // Guardar información del archivo
        $data['archivo_ruta'] = $ruta;
        $data['archivo_nombre_original'] = $nombreOriginal;
        $data['archivo_tamano'] = $archivo->getSize();

        return $data;
    }

    /**
     * Muestra los detalles de un documento específico
     * 
     * @param Documento $documento
     * @return \Illuminate\View\View
     */
    public function show(Documento $documento)
    {
        // Verificar que el usuario pueda ver este documento
        $this->authorize('ver', $documento);

        $documento->load(['usuario', 'fiscalAsignado']);

        return view('documentos.show', compact('documento'));
    }

    /**
     * Muestra el formulario para editar un documento
     * 
     * @param Documento $documento
     * @return \Illuminate\View\View
     */
    public function edit(Documento $documento)
    {
        // Verificar permiso de edición
        $this->authorize('editar', $documento);

        $tipos = Documento::TIPOS;
        $estados = Documento::ESTADOS;
        $prioridades = Documento::PRIORIDADES;
        $fiscales = User::role('Fiscal')->activos()->get();

        return view('documentos.edit', compact('documento', 'tipos', 'estados', 'prioridades', 'fiscales'));
    }

    /**
     * Actualiza un documento en la base de datos
     * 
     * @param UpdateDocumentoRequest $request
     * @param Documento $documento
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateDocumentoRequest $request, Documento $documento)
    {
        $data = $request->validated();

        // Manejar eliminación de archivo existente
        if ($request->boolean('eliminar_archivo') && $documento->tiene_archivo) {
            $this->eliminarArchivo($documento);
            $data['archivo_ruta'] = null;
            $data['archivo_nombre_original'] = null;
            $data['archivo_tamano'] = null;
        }

        // Manejar nuevo archivo PDF
        if ($request->hasFile('archivo')) {
            // Eliminar archivo anterior si existe
            if ($documento->tiene_archivo) {
                $this->eliminarArchivo($documento);
            }
            $data = $this->procesarArchivo($request, $data);
        }

        // Actualizar fecha de resolución si cambia a estado final
        if (in_array($data['estado'] ?? $documento->estado, ['Resuelto', 'Archivado']) && 
            empty($data['fecha_resolucion'])) {
            $data['fecha_resolucion'] = now()->format('Y-m-d');
        }

        $documento->update($data);

        return redirect()
            ->route('documentos.show', $documento)
            ->with('success', 'Documento actualizado exitosamente.');
    }

    /**
     * Elimina un archivo del storage
     * 
     * @param Documento $documento
     * @return void
     */
    private function eliminarArchivo(Documento $documento): void
    {
        if ($documento->archivo_ruta && Storage::disk('public')->exists($documento->archivo_ruta)) {
            Storage::disk('public')->delete($documento->archivo_ruta);
        }
    }

    /**
     * Elimina un documento (soft delete)
     * 
     * @param Documento $documento
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Documento $documento)
    {
        // Verificar permiso de eliminación
        $this->authorize('eliminar', $documento);

        // Eliminar archivo adjunto si existe
        if ($documento->tiene_archivo) {
            $this->eliminarArchivo($documento);
        }

        $numeroExpediente = $documento->numero_expediente;
        $documento->delete();

        return redirect()
            ->route('documentos.index')
            ->with('success', "Documento {$numeroExpediente} eliminado exitosamente.");
    }

    /**
     * Descarga el archivo PDF adjunto
     * 
     * @param Documento $documento
     * @return \Illuminate\Http\Response
     */
    public function descargarArchivo(Documento $documento)
    {
        // Verificar que el usuario pueda ver el documento
        $this->authorize('ver', $documento);

        if (!$documento->tiene_archivo) {
            return redirect()->back()->with('error', 'El documento no tiene archivo adjunto.');
        }

        $ruta = storage_path('app/public/' . $documento->archivo_ruta);
        
        return response()->download(
            $ruta, 
            $documento->archivo_nombre_original ?? 'documento.pdf',
            ['Content-Type' => 'application/pdf']
        );
    }

    /**
     * Genera reporte PDF de documentos
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function generarPdf(Request $request)
    {
        // Verificar permiso
        $this->authorize('generar-pdf');

        $user = auth()->user();
        
        // Query base
        $query = Documento::with(['usuario', 'fiscalAsignado']);
        
        // Filtrar según rol
        if ($user->hasRole('Ciudadano')) {
            $query->where('user_id', $user->id);
        }

        // Aplicar mismos filtros que en index
        $this->aplicarFiltros($query, $request);

        $documentos = $query->orderBy('fecha_registro', 'desc')->get();

        // Datos para el PDF
        $data = [
            'titulo' => 'Reporte de Documentos y Trámites',
            'fecha' => now()->format('d/m/Y H:i:s'),
            'usuario' => $user->name,
            'rol' => $user->rolPrincipal(),
            'documentos' => $documentos,
            'total' => $documentos->count(),
            'filtros' => [
                'buscar' => $request->input('buscar'),
                'estado' => $request->input('estado'),
                'tipo' => $request->input('tipo'),
                'fecha_inicio' => $request->input('fecha_inicio'),
                'fecha_fin' => $request->input('fecha_fin'),
            ],
        ];

        // Generar PDF con Dompdf
        $pdf = Pdf::loadView('pdf.reporte-documentos', $data);
        
        // Configurar papel y orientación
        $pdf->setPaper('A4', 'portrait');
        
        // Nombre del archivo
        $nombreArchivo = 'reporte-documentos-' . now()->format('Y-m-d-His') . '.pdf';

        return $pdf->download($nombreArchivo);
    }

    /**
     * Cambia el estado de un documento (AJAX)
     * 
     * @param Request $request
     * @param Documento $documento
     * @return \Illuminate\Http\JsonResponse
     */
    public function cambiarEstado(Request $request, Documento $documento)
    {
        // Verificar permiso
        $this->authorize('cambiar-estado-documento');

        $request->validate([
            'estado' => 'required|in:' . implode(',', array_keys(Documento::ESTADOS)),
        ]);

        $nuevoEstado = $request->input('estado');
        $fechaResolucion = null;

        // Si es estado final, agregar fecha de resolución
        if (in_array($nuevoEstado, ['Resuelto', 'Archivado'])) {
            $fechaResolucion = now()->format('Y-m-d');
        }

        $documento->update([
            'estado' => $nuevoEstado,
            'fecha_resolucion' => $fechaResolucion,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Estado actualizado exitosamente.',
            'estado' => $nuevoEstado,
            'estado_color' => $documento->estado_color,
        ]);
    }
}
