<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migración para crear la tabla de usuarios
 * Incluye campos básicos de autenticación y timestamps
 */
return new class extends Migration
{
    /**
     * Ejecuta la migración - crea las tablas
     */
    public function up(): void
    {
        // Tabla principal de usuarios
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');                           // Nombre completo del usuario
            $table->string('email')->unique();                // Correo electrónico (único)
            $table->timestamp('email_verified_at')->nullable(); // Verificación de email
            $table->string('password');                       // Contraseña hasheada
            $table->string('dni', 8)->nullable()->unique();   // DNI peruano (opcional)
            $table->string('telefono', 15)->nullable();       // Teléfono de contacto
            $table->boolean('activo')->default(true);         // Estado activo/inactivo
            $table->rememberToken();                          // Token para "recordarme"
            $table->timestamps();                             // created_at y updated_at
            $table->softDeletes();                            // deleted_at para eliminación lógica
        });

        // Tabla para restablecimiento de contraseñas
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // Tabla para sesiones de usuario
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Revierte la migración - elimina las tablas
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
