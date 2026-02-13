<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

/**
 * RolesAndPermissionsSeeder - Crea roles y permisos del sistema
 * 
 * Define la matriz de permisos para cada rol del sistema:
 * - Admin: Control total
 * - Fiscal: Visualización y edición de trámites
 * - Asistente: Registro y actualización
 * - Ciudadano: Solo sus propios documentos
 */
class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Ejecuta el seeder creando roles y permisos
     */
    public function run(): void
    {
        // Limpiar cache de permisos
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ============================================
        // CREAR PERMISOS
        // ============================================
        
        // Permisos de usuarios
        Permission::create(['name' => 'ver-usuarios']);
        Permission::create(['name' => 'crear-usuarios']);
        Permission::create(['name' => 'editar-usuarios']);
        Permission::create(['name' => 'eliminar-usuarios']);

        // Permisos de documentos
        Permission::create(['name' => 'ver-todos-documentos']);
        Permission::create(['name' => 'ver-propio-documento']);
        Permission::create(['name' => 'crear-documentos']);
        Permission::create(['name' => 'editar-todos-documentos']);
        Permission::create(['name' => 'editar-propio-documento']);
        Permission::create(['name' => 'eliminar-documentos']);
        Permission::create(['name' => 'asignar-documentos']);
        Permission::create(['name' => 'cambiar-estado-documento']);

        // Permisos de reportes
        Permission::create(['name' => 'ver-reportes']);
        Permission::create(['name' => 'generar-pdf']);

        // Permisos de dashboard
        Permission::create(['name' => 'ver-dashboard-completo']);
        Permission::create(['name' => 'ver-dashboard-basico']);

        // ============================================
        // CREAR ROLES Y ASIGNAR PERMISOS
        // ============================================

        // ROL: ADMIN - Control total del sistema
        $roleAdmin = Role::create(['name' => 'Admin']);
        $roleAdmin->givePermissionTo([
            'ver-usuarios',
            'crear-usuarios',
            'editar-usuarios',
            'eliminar-usuarios',
            'ver-todos-documentos',
            'crear-documentos',
            'editar-todos-documentos',
            'eliminar-documentos',
            'asignar-documentos',
            'cambiar-estado-documento',
            'ver-reportes',
            'generar-pdf',
            'ver-dashboard-completo',
        ]);

        // ROL: FISCAL - Visualiza y gestiona trámites asignados
        $roleFiscal = Role::create(['name' => 'Fiscal']);
        $roleFiscal->givePermissionTo([
            'ver-todos-documentos',
            'crear-documentos',
            'editar-todos-documentos',
            'cambiar-estado-documento',
            'ver-reportes',
            'generar-pdf',
            'ver-dashboard-completo',
        ]);

        // ROL: ASISTENTE - Registra y actualiza documentos
        $roleAsistente = Role::create(['name' => 'Asistente']);
        $roleAsistente->givePermissionTo([
            'ver-todos-documentos',
            'crear-documentos',
            'editar-propio-documento',
            'cambiar-estado-documento',
            'ver-dashboard-basico',
        ]);

        // ROL: CIUDADANO - Solo ve sus propios documentos
        $roleCiudadano = Role::create(['name' => 'Ciudadano']);
        $roleCiudadano->givePermissionTo([
            'ver-propio-documento',
            'crear-documentos',
            'editar-propio-documento',
            'ver-dashboard-basico',
        ]);

        $this->command->info('Roles y permisos creados exitosamente:');
        $this->command->info('- Admin: Control total');
        $this->command->info('- Fiscal: Gestión de trámites');
        $this->command->info('- Asistente: Registro y actualización');
        $this->command->info('- Ciudadano: Consulta propia');
    }
}
