<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migración para crear la tabla de documentos/trámites
 * Almacena toda la información de los documentos gestionados en el sistema
 */
return new class extends Migration
{
    /**
     * Ejecuta la migración - crea la tabla de documentos
     */
    public function up(): void
    {
        Schema::create('documentos', function (Blueprint $table) {
            $table->id();
            
            // Información principal del documento
            $table->string('titulo', 255);                    // Título del documento
            $table->text('descripcion')->nullable();          // Descripción detallada
            $table->string('numero_expediente', 50)->nullable()->unique(); // Número de expediente único
            
            // Clasificación del documento
            $table->enum('tipo', [
                'Denuncia',
                'Oficio',
                'Solicitud',
                'Memorandum',
                'Carta',
                'Informe',
                'Otro'
            ])->default('Otro');
            
            // Estado del trámite
            $table->enum('estado', [
                'Pendiente',
                'En proceso',
                'Resuelto',
                'Archivado',
                'Derivado'
            ])->default('Pendiente');
            
            // Fechas importantes
            $table->date('fecha_registro');                   // Fecha de registro del documento
            $table->date('fecha_resolucion')->nullable();     // Fecha de resolución (si aplica)
            
            // Relación con el usuario que registra
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade');                    // Si se elimina el usuario, se eliminan sus documentos
            
            // Asignación a fiscal (opcional)
            $table->foreignId('asignado_a')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');                   // Si se elimina el fiscal, queda sin asignar
            
            // Archivo adjunto (PDF)
            $table->string('archivo_ruta')->nullable();       // Ruta del archivo en storage
            $table->string('archivo_nombre_original')->nullable(); // Nombre original del archivo
            $table->integer('archivo_tamano')->nullable();    // Tamaño en bytes
            
            // Observaciones adicionales
            $table->text('observaciones')->nullable();
            
            // Prioridad del trámite
            $table->enum('prioridad', ['Baja', 'Media', 'Alta', 'Urgente'])->default('Media');
            
            // Timestamps y soft deletes
            $table->timestamps();
            $table->softDeletes();
            
            // Índices para optimizar búsquedas
            $table->index('estado');
            $table->index('tipo');
            $table->index('fecha_registro');
            $table->index(['estado', 'tipo']);
            $table->index(['user_id', 'estado']);
        });
    }

    /**
     * Revierte la migración - elimina la tabla
     */
    public function down(): void
    {
        Schema::dropIfExists('documentos');
    }
};
