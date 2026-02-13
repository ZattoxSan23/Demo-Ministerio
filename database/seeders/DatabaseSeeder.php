<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * DatabaseSeeder - Seeder principal del sistema
 * 
 * Ejecuta todos los seeders necesarios para poblar la base de datos
 * con datos iniciales de prueba.
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Ejecuta los seeders en el orden correcto
     */
    public function run(): void
    {
        $this->call([
            // 1. Primero crear roles y permisos
            RolesAndPermissionsSeeder::class,
            
            // 2. Luego crear usuarios con sus roles
            UserSeeder::class,
            
            // 3. Finalmente crear documentos de ejemplo
            DocumentoSeeder::class,
        ]);
    }
}
