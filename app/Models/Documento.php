<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

/**
 * Modelo Documento - Documentos y trámites del sistema
 * 
 * Este modelo gestiona todos los documentos ingresados al sistema,
 * incluyendo denuncias, oficios, solicitudes, etc.
 * 
 * @property int $id
 * @property string $titulo
 * @property string|null $descripcion
 * @property string|null $numero_expediente
 * @property string $tipo
 * @property string $estado
 * @property \Carbon\Carbon $fecha_registro
 * @property \Carbon\Carbon|null $fecha_resolucion
 * @property int $user_id
 * @property int|null $asignado_a
 * @property string|null $archivo_ruta
 * @property string|null $archivo_nombre_original
 * @property int|null $archivo_tamano
 * @property string|null $observaciones
 * @property string $prioridad
 */
class Documento extends Model
{
    /** @use HasFactory<\Database\Factories\DocumentoFactory> */
    use HasFactory, SoftDeletes;

    /**
     * Nombre de la tabla asociada
     */
    protected $table = 'documentos';

    /**
     * Atributos que se pueden asignar masivamente
     *
     * @var array<string>
     */
    protected $fillable = [
        'titulo',
        'descripcion',
        'numero_expediente',
        'tipo',
        'estado',
        'fecha_registro',
        'fecha_resolucion',
        'user_id',
        'asignado_a',
        'archivo_ruta',
        'archivo_nombre_original',
        'archivo_tamano',
        'observaciones',
        'prioridad',
    ];

    /**
     * Atributos que deben convertirse a tipos nativos
     *
     * @var array<string, string>
     */
    protected $casts = [
        'fecha_registro' => 'date',
        'fecha_resolucion' => 'date',
        'archivo_tamano' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Tipos de documentos disponibles
     */
    const TIPOS = [
        'Denuncia' => 'Denuncia',
        'Oficio' => 'Oficio',
        'Solicitud' => 'Solicitud',
        'Memorandum' => 'Memorandum',
        'Carta' => 'Carta',
        'Informe' => 'Informe',
        'Otro' => 'Otro',
    ];

    /**
     * Estados de trámite disponibles
     */
    const ESTADOS = [
        'Pendiente' => 'Pendiente',
        'En proceso' => 'En proceso',
        'Resuelto' => 'Resuelto',
        'Archivado' => 'Archivado',
        'Derivado' => 'Derivado',
    ];

    /**
     * Niveles de prioridad
     */
    const PRIORIDADES = [
        'Baja' => 'Baja',
        'Media' => 'Media',
        'Alta' => 'Alta',
        'Urgente' => 'Urgente',
    ];

    /**
     * Colores para badges de estado (Bootstrap)
     */
    const ESTADO_COLORES = [
        'Pendiente' => 'warning',
        'En proceso' => 'info',
        'Resuelto' => 'success',
        'Archivado' => 'secondary',
        'Derivado' => 'primary',
    ];

    /**
     * Colores para badges de prioridad (Bootstrap)
     */
    const PRIORIDAD_COLORES = [
        'Baja' => 'success',
        'Media' => 'info',
        'Alta' => 'warning',
        'Urgente' => 'danger',
    ];

    /**
     * Relación: El documento pertenece a un usuario (registrante)
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relación: El documento puede estar asignado a un fiscal
     */
    public function fiscalAsignado()
    {
        return $this->belongsTo(User::class, 'asignado_a');
    }

    /**
     * Scope: Documentos por estado
     */
    public function scopePorEstado($query, $estado)
    {
        return $query->where('estado', $estado);
    }

    /**
     * Scope: Documentos por tipo
     */
    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    /**
     * Scope: Documentos por usuario registrante
     */
    public function scopePorUsuario($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope: Documentos asignados a un fiscal
     */
    public function scopeAsignadosA($query, $fiscalId)
    {
        return $query->where('asignado_a', $fiscalId);
    }

    /**
     * Scope: Búsqueda por título o descripción
     */
    public function scopeBuscar($query, $termino)
    {
        return $query->where(function ($q) use ($termino) {
            $q->where('titulo', 'LIKE', "%{$termino}%")
              ->orWhere('descripcion', 'LIKE', "%{$termino}%")
              ->orWhere('numero_expediente', 'LIKE', "%{$termino}%");
        });
    }

    /**
     * Scope: Filtrar por rango de fechas
     */
    public function scopeEntreFechas($query, $fechaInicio, $fechaFin)
    {
        return $query->whereBetween('fecha_registro', [$fechaInicio, $fechaFin]);
    }

    /**
     * Scope: Documentos pendientes
     */
    public function scopePendientes($query)
    {
        return $query->where('estado', 'Pendiente');
    }

    /**
     * Scope: Documentos en proceso
     */
    public function scopeEnProceso($query)
    {
        return $query->where('estado', 'En proceso');
    }

    /**
     * Scope: Documentos resueltos
     */
    public function scopeResueltos($query)
    {
        return $query->where('estado', 'Resuelto');
    }

    /**
     * Scope: Documentos archivados
     */
    public function scopeArchivados($query)
    {
        return $query->where('estado', 'Archivado');
    }

    /**
     * Scope: Documentos de alta prioridad
     */
    public function scopeAltaPrioridad($query)
    {
        return $query->whereIn('prioridad', ['Alta', 'Urgente']);
    }

    /**
     * Accessor: Obtiene el color del badge para el estado
     */
    public function getEstadoColorAttribute(): string
    {
        return self::ESTADO_COLORES[$this->estado] ?? 'secondary';
    }

    /**
     * Accessor: Obtiene el color del badge para la prioridad
     */
    public function getPrioridadColorAttribute(): string
    {
        return self::PRIORIDAD_COLORES[$this->prioridad] ?? 'secondary';
    }

    /**
     * Accessor: Verifica si tiene archivo adjunto
     */
    public function getTieneArchivoAttribute(): bool
    {
        return !is_null($this->archivo_ruta) && Storage::disk('public')->exists($this->archivo_ruta);
    }

    /**
     * Accessor: Obtiene la URL del archivo
     */
    public function getArchivoUrlAttribute(): ?string
    {
        return $this->tiene_archivo ? Storage::disk('public')->url($this->archivo_ruta) : null;
    }

    /**
     * Accessor: Formatea el tamaño del archivo legible
     */
    public function getArchivoTamanoFormateadoAttribute(): ?string
    {
        if (!$this->archivo_tamano) {
            return null;
        }

        $unidades = ['B', 'KB', 'MB', 'GB'];
        $tamano = $this->archivo_tamano;
        $unidadIndex = 0;

        while ($tamano >= 1024 && $unidadIndex < count($unidades) - 1) {
            $tamano /= 1024;
            $unidadIndex++;
        }

        return round($tamano, 2) . ' ' . $unidades[$unidadIndex];
    }

    /**
     * Genera un número de expediente único
     */
    public static function generarNumeroExpediente(): string
    {
        $anio = date('Y');
        $prefijo = 'EXP';
        $ultimo = self::withTrashed()
            ->whereYear('created_at', $anio)
            ->count() + 1;
        
        return sprintf('%s-%s-%05d', $prefijo, $anio, $ultimo);
    }

    /**
     * Boot del modelo - eventos
     */
    protected static function boot()
    {
        parent::boot();

        // Antes de crear, generar número de expediente si no tiene
        static::creating(function ($documento) {
            if (empty($documento->numero_expediente)) {
                $documento->numero_expediente = self::generarNumeroExpediente();
            }
        });
    }
}
