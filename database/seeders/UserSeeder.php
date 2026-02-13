<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * UserSeeder - Crea usuarios de prueba para el sistema
 * 
 * Genera usuarios con diferentes roles para demostración:
 * - Admin: Control total
 * - Fiscal: Gestión de trámites
 * - Asistente: Registro de documentos
 * - Ciudadanos: Usuarios finales
 */
class UserSeeder extends Seeder
{
    /**
     * Ejecuta el seeder creando usuarios de prueba
     */
    public function run(): void
    {
        // ============================================
        // USUARIO ADMINISTRADOR PRINCIPAL
        // ============================================
        $admin = User::create([
            'name' => 'Administrador del Sistema',
            'email' => 'admin@demo.com',
            'password' => Hash::make('12345678'),
            'dni' => '12345678',
            'telefono' => '999888777',
            'activo' => true,
        ]);
        $admin->assignRole('Admin');

        // ============================================
        // USUARIOS FISCALES
        // ============================================
        $fiscal1 = User::create([
            'name' => 'Fiscal María González',
            'email' => 'fiscal1@demo.com',
            'password' => Hash::make('12345678'),
            'dni' => '87654321',
            'telefono' => '987654321',
            'activo' => true,
        ]);
        $fiscal1->assignRole('Fiscal');

        $fiscal2 = User::create([
            'name' => 'Fiscal Carlos Rodríguez',
            'email' => 'fiscal2@demo.com',
            'password' => Hash::make('12345678'),
            'dni' => '45678912',
            'telefono' => '956321478',
            'activo' => true,
        ]);
        $fiscal2->assignRole('Fiscal');

        // ============================================
        // USUARIOS ASISTENTES
        // ============================================
        $asistente1 = User::create([
            'name' => 'Asistente Laura Pérez',
            'email' => 'asistente1@demo.com',
            'password' => Hash::make('12345678'),
            'dni' => '36985214',
            'telefono' => '941236587',
            'activo' => true,
        ]);
        $asistente1->assignRole('Asistente');

        $asistente2 = User::create([
            'name' => 'Asistente Pedro Sánchez',
            'email' => 'asistente2@demo.com',
            'password' => Hash::make('12345678'),
            'dni' => '74125896',
            'telefono' => '923654178',
            'activo' => true,
        ]);
        $asistente2->assignRole('Asistente');

        // ============================================
        // USUARIOS CIUDADANOS
        // ============================================
        $ciudadano1 = User::create([
            'name' => 'Juan Pérez García',
            'email' => 'ciudadano1@demo.com',
            'password' => Hash::make('12345678'),
            'dni' => '14785236',
            'telefono' => '912345678',
            'activo' => true,
        ]);
        $ciudadano1->assignRole('Ciudadano');

        $ciudadano2 = User::create([
            'name' => 'Ana María López',
            'email' => 'ciudadano2@demo.com',
            'password' => Hash::make('12345678'),
            'dni' => '96325874',
            'telefono' => '934567891',
            'activo' => true,
        ]);
        $ciudadano2->assignRole('Ciudadano');

        $ciudadano3 = User::create([
            'name' => 'Roberto Díaz Torres',
            'email' => 'ciudadano3@demo.com',
            'password' => Hash::make('12345678'),
            'dni' => '85236974',
            'telefono' => '978945612',
            'activo' => true,
        ]);
        $ciudadano3->assignRole('Ciudadano');

        // ============================================
        // USUARIOS ADICIONALES ALEATORIOS
        // ============================================
        // Crear 5 ciudadanos adicionales con factory
        User::factory()->count(5)->create()->each(function ($user) {
            $user->assignRole('Ciudadano');
        });

        $this->command->info('Usuarios de prueba creados exitosamente:');
        $this->command->info('- admin@demo.com (Admin)');
        $this->command->info('- fiscal1@demo.com, fiscal2@demo.com (Fiscal)');
        $this->command->info('- asistente1@demo.com, asistente2@demo.com (Asistente)');
        $this->command->info('- ciudadano1@demo.com, ciudadano2@demo.com, ciudadano3@demo.com (Ciudadano)');
        $this->command->info('Contraseña para todos: 12345678');
    }
}
