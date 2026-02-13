<?php

namespace Database\Seeders;

use App\Models\Documento;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

/**
 * DocumentoSeeder - Crea documentos de prueba
 * 
 * Genera documentos de ejemplo con diferentes estados, tipos y usuarios
 * para demostrar las funcionalidades del sistema.
 */
class DocumentoSeeder extends Seeder
{
    /**
     * Ejecuta el seeder creando documentos de prueba
     */
    public function run(): void
    {
        // Obtener usuarios por rol
        $admin = User::role('Admin')->first();
        $fiscales = User::role('Fiscal')->get();
        $asistentes = User::role('Asistente')->get();
        $ciudadanos = User::role('Ciudadano')->get();

        // ============================================
        // DOCUMENTOS DE DENUNCIA
        // ============================================
        $denuncias = [
            [
                'titulo' => 'Denuncia por Robo Agravado',
                'descripcion' => 'Se denuncia el robo de pertenencias en la vía pública con arma de fuego. Ocurrió el 15/01/2026 en el distrito de Miraflores.',
                'tipo' => 'Denuncia',
                'estado' => 'En proceso',
                'prioridad' => 'Alta',
                'user_id' => $ciudadanos[0]->id,
                'asignado_a' => $fiscales[0]->id,
            ],
            [
                'titulo' => 'Denuncia por Estafa',
                'descripcion' => 'Denuncia contra empresa X por incumplimiento de contrato y estafa de S/ 50,000.',
                'tipo' => 'Denuncia',
                'estado' => 'Pendiente',
                'prioridad' => 'Media',
                'user_id' => $ciudadanos[1]->id,
                'asignado_a' => null,
            ],
            [
                'titulo' => 'Denuncia por Violencia Familiar',
                'descripcion' => 'Denuncia de violencia física y psicológica en el ámbito familiar. Se solicita medida de protección.',
                'tipo' => 'Denuncia',
                'estado' => 'Resuelto',
                'prioridad' => 'Urgente',
                'user_id' => $ciudadanos[2]->id,
                'asignado_a' => $fiscales[1]->id,
            ],
        ];

        foreach ($denuncias as $data) {
            Documento::create(array_merge($data, [
                'fecha_registro' => Carbon::now()->subDays(rand(1, 30)),
                'observaciones' => 'Documento registrado automáticamente por el sistema.',
            ]));
        }

        // ============================================
        // DOCUMENTOS DE OFICIO
        // ============================================
        $oficios = [
            [
                'titulo' => 'Oficio N° 045-2026-MP-FN: Solicitud de Informes',
                'descripcion' => 'Se solicita informe detallado sobre el estado de investigaciones preliminares del mes de enero.',
                'tipo' => 'Oficio',
                'estado' => 'En proceso',
                'prioridad' => 'Alta',
                'user_id' => $asistentes[0]->id,
                'asignado_a' => $fiscales[0]->id,
            ],
            [
                'titulo' => 'Oficio de Coordinación Interinstitucional',
                'descripcion' => 'Oficio dirigido a la PNP para coordinar operativo conjunto de interdicción.',
                'tipo' => 'Oficio',
                'estado' => 'Derivado',
                'prioridad' => 'Urgente',
                'user_id' => $fiscales[0]->id,
                'asignado_a' => $fiscales[1]->id,
            ],
            [
                'titulo' => 'Oficio de Respuesta - Requerimiento N° 123',
                'descripcion' => 'Respuesta a requerimiento de información solicitada por la Defensoría del Pueblo.',
                'tipo' => 'Oficio',
                'estado' => 'Archivado',
                'prioridad' => 'Baja',
                'user_id' => $asistentes[1]->id,
                'asignado_a' => null,
            ],
        ];

        foreach ($oficios as $data) {
            Documento::create(array_merge($data, [
                'fecha_registro' => Carbon::now()->subDays(rand(5, 45)),
                'observaciones' => 'Oficio registrado en mesa de partes.',
            ]));
        }

        // ============================================
        // DOCUMENTOS DE SOLICITUD
        // ============================================
        $solicitudes = [
            [
                'titulo' => 'Solicitud de Copias Certificadas',
                'descripcion' => 'Solicito se me entreguen copias certificadas del expediente N° 2025-1234.',
                'tipo' => 'Solicitud',
                'estado' => 'Pendiente',
                'prioridad' => 'Media',
                'user_id' => $ciudadanos[0]->id,
                'asignado_a' => $asistentes[0]->id,
            ],
            [
                'titulo' => 'Solicitud de Subsidio por Gastos de Sepelio',
                'descripcion' => 'Solicitud de subsidio para familiares de víctima de delito contra la vida.',
                'tipo' => 'Solicitud',
                'estado' => 'En proceso',
                'prioridad' => 'Alta',
                'user_id' => $ciudadanos[1]->id,
                'asignado_a' => $fiscales[0]->id,
            ],
            [
                'titulo' => 'Solicitud de Audiencia con el Fiscal',
                'descripcion' => 'Ciudadano solicita audiencia para exponer su caso personalmente.',
                'tipo' => 'Solicitud',
                'estado' => 'Resuelto',
                'prioridad' => 'Baja',
                'user_id' => $ciudadanos[2]->id,
                'asignado_a' => $fiscales[1]->id,
            ],
        ];

        foreach ($solicitudes as $data) {
            Documento::create(array_merge($data, [
                'fecha_registro' => Carbon::now()->subDays(rand(1, 20)),
                'observaciones' => 'Solicitud recepcionada en mesa de partes.',
            ]));
        }

        // ============================================
        // DOCUMENTOS VARIOS (MEMORANDUM, CARTA, INFORME)
        // ============================================
        $varios = [
            [
                'titulo' => 'Memorandum Circular N° 05-2026',
                'descripcion' => 'Directrices para la atención de denuncias durante feriados.',
                'tipo' => 'Memorandum',
                'estado' => 'En proceso',
                'prioridad' => 'Media',
                'user_id' => $admin->id,
                'asignado_a' => null,
            ],
            [
                'titulo' => 'Carta de Agradecimiento - Colaboración Eficaz',
                'descripcion' => 'Carta de ciudadano agradeciendo la pronta atención de su denuncia.',
                'tipo' => 'Carta',
                'estado' => 'Archivado',
                'prioridad' => 'Baja',
                'user_id' => $ciudadanos[0]->id,
                'asignado_a' => null,
            ],
            [
                'titulo' => 'Informe de Gestión Mensual - Enero 2026',
                'descripcion' => 'Informe estadístico de denuncias atendidas, resueltas y pendientes.',
                'tipo' => 'Informe',
                'estado' => 'Resuelto',
                'prioridad' => 'Alta',
                'user_id' => $asistentes[0]->id,
                'asignado_a' => $admin->id,
            ],
            [
                'titulo' => 'Informe de Avance de Investigación',
                'descripcion' => 'Informe preliminar sobre avances en investigación por delito de corrupción.',
                'tipo' => 'Informe',
                'estado' => 'En proceso',
                'prioridad' => 'Urgente',
                'user_id' => $fiscales[0]->id,
                'asignado_a' => $admin->id,
            ],
            [
                'titulo' => 'Otros - Documento de Referencia',
                'descripcion' => 'Documento misceláneo para clasificación posterior.',
                'tipo' => 'Otro',
                'estado' => 'Pendiente',
                'prioridad' => 'Baja',
                'user_id' => $asistentes[1]->id,
                'asignado_a' => null,
            ],
        ];

        foreach ($varios as $data) {
            Documento::create(array_merge($data, [
                'fecha_registro' => Carbon::now()->subDays(rand(1, 60)),
                'observaciones' => 'Documento registrado en sistema.',
            ]));
        }

        // ============================================
        // DOCUMENTOS ADICIONALES CON FACTORY
        // ============================================
        Documento::factory()->count(10)->create();

        $this->command->info('Documentos de prueba creados exitosamente:');
        $this->command->info('- 3 Denuncias');
        $this->command->info('- 3 Oficios');
        $this->command->info('- 3 Solicitudes');
        $this->command->info('- 5 Documentos varios');
        $this->command->info('- 10 Documentos aleatorios adicionales');
    }
}
