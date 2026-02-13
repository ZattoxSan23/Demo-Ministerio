<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

/**
 * Modelo User - Usuario del sistema
 * 
 * Este modelo representa a los usuarios del sistema de gestión documental.
 * Incluye autenticación, roles/permisos (Spatie) y eliminación lógica.
 * 
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $dni
 * @property string|null $telefono
 * @property bool $activo
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes, HasRoles;

    /**
     * Nombre de la tabla asociada
     */
    protected $table = 'users';

    /**
     * Nombre de la guardia para Spatie Permission
     */
    protected $guard_name = 'web';

    /**
     * Atributos que se pueden asignar masivamente
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'dni',
        'telefono',
        'activo',
    ];

    /**
     * Atributos que deben ocultarse en serialización
     *
     * @var array<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Atributos que deben convertirse a tipos nativos
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed', // Laravel 12 usa hashing automático
        'activo' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relación: Un usuario tiene muchos documentos (como registrante)
     */
    public function documentosRegistrados()
    {
        return $this->hasMany(Documento::class, 'user_id');
    }

    /**
     * Relación: Un usuario puede tener muchos documentos asignados (como fiscal)
     */
    public function documentosAsignados()
    {
        return $this->hasMany(Documento::class, 'asignado_a');
    }

    /**
     * Scope: Usuarios activos
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Verifica si el usuario es administrador
     */
    public function esAdmin(): bool
    {
        return $this->hasRole('Admin');
    }

    /**
     * Verifica si el usuario es fiscal
     */
    public function esFiscal(): bool
    {
        return $this->hasRole('Fiscal');
    }

    /**
     * Verifica si el usuario es asistente
     */
    public function esAsistente(): bool
    {
        return $this->hasRole('Asistente');
    }

    /**
     * Verifica si el usuario es ciudadano
     */
    public function esCiudadano(): bool
    {
        return $this->hasRole('Ciudadano');
    }

    /**
     * Obtiene el nombre del rol principal del usuario
     */
    public function rolPrincipal(): string
    {
        $rol = $this->roles->first();
        return $rol ? $rol->name : 'Sin rol';
    }

    /**
     * Obtiene el color del badge según el rol (para vistas)
     */
    public function rolBadgeColor(): string
    {
        return match($this->rolPrincipal()) {
            'Admin' => 'danger',
            'Fiscal' => 'primary',
            'Asistente' => 'warning',
            'Ciudadano' => 'success',
            default => 'secondary'
        };
    }
}
