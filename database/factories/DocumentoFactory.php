<?php

namespace Database\Factories;

use App\Models\Documento;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * DocumentoFactory - Factory para generar documentos de prueba
 * 
 * Genera datos aleatorios realistas para documentos y trámites.
 */
class DocumentoFactory extends Factory
{
    /**
     * Nombre del modelo asociado
     */
    protected $model = Documento::class;

    /**
     * Títulos de ejemplo para documentos
     */
    protected array $titulosEjemplo = [
        'Denuncia' => [
            'Denuncia por Hurto Simple',
            'Denuncia por Daños a la Propiedad',
            'Denuncia por Amenazas',
            'Denuncia por Lesiones Leves',
            'Denuncia por Fraude',
            'Denuncia por Abandono de Familia',
            'Denuncia por Incumplimiento de Pensiones',
        ],
        'Oficio' => [
            'Oficio de Requerimiento de Información',
            'Oficio de Derivación de Expediente',
            'Oficio de Notificación',
            'Oficio de Solicitud de Apoyo',
            'Oficio de Coordinación',
        ],
        'Solicitud' => [
            'Solicitud de Información Pública',
            'Solicitud de Rectificación de Datos',
            'Solicitud de Audiencia',
            'Solicitud de Medidas de Protección',
            'Solicitud de Copias Simples',
        ],
        'Memorandum' => [
            'Memorandum de Instrucciones',
            'Memorandum de Recordatorio',
            'Memorandum de Felicitación',
            'Memorandum de Apercibimiento',
        ],
        'Carta' => [
            'Carta de Presentación',
            'Carta de Agradecimiento',
            'Carta de Reclamo',
            'Carta de Sugerencia',
        ],
        'Informe' => [
            'Informe de Investigación Preliminar',
            'Informe de Actuados',
            'Informe de Fiscalización',
            'Informe de Verificación',
        ],
        'Otro' => [
            'Documento de Referencia',
            'Nota de Prensa',
            'Acta de Constancia',
            'Resolución Administrativa',
        ],
    ];

    /**
     * Define el estado por defecto del modelo
     */
    public function definition(): array
    {
        $tipo = $this->faker->randomElement(array_keys(Documento::TIPOS));
        $estado = $this->faker->randomElement(array_keys(Documento::ESTADOS));
        $prioridad = $this->faker->randomElement(array_keys(Documento::PRIORIDADES));
        
        // Seleccionar título según el tipo
        $titulo = $this->faker->randomElement($this->titulosEjemplo[$tipo]);
        
        // Fecha de registro aleatoria en los últimos 90 días
        $fechaRegistro = $this->faker->dateTimeBetween('-90 days', 'now');
        
        // Fecha de resolución solo si está resuelto o archivado
        $fechaResolucion = null;
        if (in_array($estado, ['Resuelto', 'Archivado'])) {
            $fechaResolucion = $this->faker->dateTimeBetween($fechaRegistro, 'now');
        }

        // Asignar a fiscal aleatorio (50% de probabilidad)
        $asignadoA = null;
        if ($this->faker->boolean(50)) {
            $fiscal = User::role('Fiscal')->inRandomOrder()->first();
            $asignadoA = $fiscal?->id;
        }

        return [
            'titulo' => $titulo,
            'descripcion' => $this->faker->paragraph(3),
            'tipo' => $tipo,
            'estado' => $estado,
            'prioridad' => $prioridad,
            'fecha_registro' => $fechaRegistro,
            'fecha_resolucion' => $fechaResolucion,
            'user_id' => User::inRandomOrder()->first()?->id ?? 1,
            'asignado_a' => $asignadoA,
            'observaciones' => $this->faker->boolean(70) ? $this->faker->sentence() : null,
        ];
    }

    /**
     * Estado: Documento pendiente
     */
    public function pendiente(): static
    {
        return $this->state(fn (array $attributes) => [
            'estado' => 'Pendiente',
            'fecha_resolucion' => null,
        ]);
    }

    /**
     * Estado: Documento resuelto
     */
    public function resuelto(): static
    {
        return $this->state(fn (array $attributes) => [
            'estado' => 'Resuelto',
            'fecha_resolucion' => $this->faker->dateTimeBetween($attributes['fecha_registro'], 'now'),
        ]);
    }

    /**
     * Prioridad: Alta
     */
    public function altaPrioridad(): static
    {
        return $this->state(fn (array $attributes) => [
            'prioridad' => 'Alta',
        ]);
    }

    /**
     * Tipo: Denuncia
     */
    public function denuncia(): static
    {
        return $this->state(fn (array $attributes) => [
            'tipo' => 'Denuncia',
            'titulo' => $this->faker->randomElement($this->titulosEjemplo['Denuncia']),
        ]);
    }
}
