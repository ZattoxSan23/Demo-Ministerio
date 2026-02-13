<?php

namespace App\Http\Requests;

use App\Models\Documento;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * UpdateDocumentoRequest - Validación para actualizar documentos existentes
 * 
 * Similar a StoreDocumentoRequest pero permite campos opcionales
 * y agrega validaciones de permisos según el rol del usuario.
 */
class UpdateDocumentoRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para hacer esta solicitud
     */
    public function authorize(): bool
    {
        $user = auth()->user();
        $documento = $this->route('documento');

        // Si no hay documento en la ruta, no autorizar
        if (!$documento) {
            return false;
        }

        // Admin puede editar todo
        if ($user->hasRole('Admin')) {
            return true;
        }

        // Fiscal puede editar todos los documentos
        if ($user->hasRole('Fiscal')) {
            return true;
        }

        // Asistente puede editar documentos propios
        if ($user->hasRole('Asistente') && $documento->user_id === $user->id) {
            return true;
        }

        // Ciudadano solo puede editar sus propios documentos pendientes
        if ($user->hasRole('Ciudadano') && $documento->user_id === $user->id) {
            return in_array($documento->estado, ['Pendiente', 'En proceso']);
        }

        return false;
    }

    /**
     * Obtiene las reglas de validación que aplican a la solicitud
     */
    public function rules(): array
    {
        return [
            // Título: requerido, máximo 255 caracteres
            'titulo' => [
                'sometimes',
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
                'sometimes',
                'required',
                'string',
                Rule::in(array_keys(Documento::TIPOS)),
            ],

            // Estado: requerido, debe estar en la lista de estados válidos
            'estado' => [
                'sometimes',
                'required',
                'string',
                Rule::in(array_keys(Documento::ESTADOS)),
            ],

            // Fecha de registro: requerida, debe ser fecha válida
            'fecha_registro' => [
                'sometimes',
                'required',
                'date',
                'before_or_equal:today',
            ],

            // Fecha de resolución: opcional, debe ser fecha válida
            'fecha_resolucion' => [
                'nullable',
                'date',
                'after_or_equal:fecha_registro',
            ],

            // Prioridad: requerida, debe estar en la lista de prioridades
            'prioridad' => [
                'sometimes',
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

            // Eliminar archivo existente: booleano
            'eliminar_archivo' => [
                'nullable',
                'boolean',
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
            
            'fecha_resolucion.date' => 'La fecha de resolución no es válida.',
            'fecha_resolucion.after_or_equal' => 'La fecha de resolución debe ser posterior o igual a la fecha de registro.',
            
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
            'fecha_resolucion' => 'fecha de resolución',
            'prioridad' => 'prioridad',
            'asignado_a' => 'fiscal asignado',
            'observaciones' => 'observaciones',
            'archivo' => 'archivo adjunto',
            'eliminar_archivo' => 'eliminar archivo existente',
        ];
    }

    /**
     * Configura el validador después de que se define
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $documento = $this->route('documento');
            
            // Validación adicional: Ciudadanos no pueden cambiar el estado
            if (auth()->user()->hasRole('Ciudadano') && $this->has('estado')) {
                $validator->errors()->add('estado', 'No tiene permisos para cambiar el estado del trámite.');
            }

            // Validación: Si cambia a Resuelto o Archivado, debe tener fecha de resolución
            if (in_array($this->input('estado'), ['Resuelto', 'Archivado']) && 
                empty($this->input('fecha_resolucion')) && 
                empty($documento->fecha_resolucion)) {
                $validator->errors()->add('fecha_resolucion', 'Debe especificar la fecha de resolución para este estado.');
            }
        });
    }
}
