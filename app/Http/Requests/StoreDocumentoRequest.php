<?php

namespace App\Http\Requests;

use App\Models\Documento;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * StoreDocumentoRequest - Validación para crear nuevos documentos
 * 
 * Esta clase maneja todas las reglas de validación al registrar
 * un nuevo documento o trámite en el sistema.
 */
class StoreDocumentoRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para hacer esta solicitud
     */
    public function authorize(): bool
    {
        // Solo usuarios autenticados pueden crear documentos
        return auth()->check();
    }

    /**
     * Obtiene las reglas de validación que aplican a la solicitud
     */
    public function rules(): array
    {
        return [
            // Título: requerido, máximo 255 caracteres
            'titulo' => [
                'required',
                'string',
                'max:255',
                'min:5',
            ],

            // Descripción: opcional, máximo 5000 caracteres
            'descripcion' => [
                'nullable',
                'string',
                'max:5000',
            ],

            // Tipo: requerido, debe estar en la lista de tipos válidos
            'tipo' => [
                'required',
                'string',
                Rule::in(array_keys(Documento::TIPOS)),
            ],

            // Estado: requerido, debe estar en la lista de estados válidos
            'estado' => [
                'required',
                'string',
                Rule::in(array_keys(Documento::ESTADOS)),
            ],

            // Fecha de registro: requerida, debe ser fecha válida
            'fecha_registro' => [
                'required',
                'date',
                'before_or_equal:today',
            ],

            // Prioridad: requerida, debe estar en la lista de prioridades
            'prioridad' => [
                'required',
                'string',
                Rule::in(array_keys(Documento::PRIORIDADES)),
            ],

            // Asignado a: opcional, debe existir en la tabla users
            'asignado_a' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],

            // Observaciones: opcional, máximo 2000 caracteres
            'observaciones' => [
                'nullable',
                'string',
                'max:2000',
            ],

            // Archivo PDF: opcional, debe ser PDF, máximo 5MB
            'archivo' => [
                'nullable',
                'file',
                'mimes:pdf',
                'mimetypes:application/pdf',
                'max:5120', // 5MB en KB
            ],
        ];
    }

    /**
     * Obtiene los mensajes de error personalizados
     */
    public function messages(): array
    {
        return [
            'titulo.required' => 'El título del documento es obligatorio.',
            'titulo.max' => 'El título no puede tener más de 255 caracteres.',
            'titulo.min' => 'El título debe tener al menos 5 caracteres.',
            
            'descripcion.max' => 'La descripción no puede tener más de 5000 caracteres.',
            
            'tipo.required' => 'Debe seleccionar el tipo de documento.',
            'tipo.in' => 'El tipo de documento seleccionado no es válido.',
            
            'estado.required' => 'Debe seleccionar el estado del trámite.',
            'estado.in' => 'El estado seleccionado no es válido.',
            
            'fecha_registro.required' => 'La fecha de registro es obligatoria.',
            'fecha_registro.date' => 'La fecha de registro no es válida.',
            'fecha_registro.before_or_equal' => 'La fecha de registro no puede ser futura.',
            
            'prioridad.required' => 'Debe seleccionar la prioridad del trámite.',
            'prioridad.in' => 'La prioridad seleccionada no es válida.',
            
            'asignado_a.exists' => 'El usuario asignado no existe en el sistema.',
            
            'observaciones.max' => 'Las observaciones no pueden tener más de 2000 caracteres.',
            
            'archivo.file' => 'El archivo debe ser un archivo válido.',
            'archivo.mimes' => 'El archivo debe ser de tipo PDF.',
            'archivo.mimetypes' => 'El archivo debe ser un PDF válido.',
            'archivo.max' => 'El archivo no puede pesar más de 5MB.',
        ];
    }

    /**
     * Obtiene los nombres personalizados de los atributos
     */
    public function attributes(): array
    {
        return [
            'titulo' => 'título',
            'descripcion' => 'descripción',
            'tipo' => 'tipo de documento',
            'estado' => 'estado del trámite',
            'fecha_registro' => 'fecha de registro',
            'prioridad' => 'prioridad',
            'asignado_a' => 'fiscal asignado',
            'observaciones' => 'observaciones',
            'archivo' => 'archivo adjunto',
        ];
    }

    /**
     * Prepara los datos para validación
     */
    protected function prepareForValidation(): void
    {
        // Si no se envía estado, establecer "Pendiente" por defecto
        if (!$this->has('estado')) {
            $this->merge(['estado' => 'Pendiente']);
        }

        // Si no se envía prioridad, establecer "Media" por defecto
        if (!$this->has('prioridad')) {
            $this->merge(['prioridad' => 'Media']);
        }

        // Si no se envía fecha, establecer la fecha actual
        if (!$this->has('fecha_registro')) {
            $this->merge(['fecha_registro' => now()->format('Y-m-d')]);
        }
    }
}
