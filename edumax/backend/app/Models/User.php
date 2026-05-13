<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use App\Traits\HasInstitucionScope;

#[Fillable([
    'institucion_id',
    'nombres',
    'apellidos',
    'email',
    'password',
    'telefono',
    'foto_perfil',
    'estado'
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, HasInstitucionScope, SoftDeletes;

    protected $table = 'users';

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'ultimo_acceso' => 'datetime',
        ];
    }

    /**
     * Relación: User pertenece a una Institución
     */
    public function institucion(): BelongsTo
    {
        return $this->belongsTo(Institucion::class);
    }

    /**
     * Relación: User tiene un Docente
     */
    public function docente(): HasOne
    {
        return $this->hasOne(Docente::class);
    }

    /**
     * Relación: User tiene un Estudiante
     */
    public function estudiante(): HasOne
    {
        return $this->hasOne(Estudiante::class);
    }

    /**
     * Relación: User tiene un Padre
     */
    public function padre(): HasOne
    {
        return $this->hasOne(Padre::class);
    }

    /**
     * Relación: User tiene muchas Notificaciones
     */
    public function notificaciones()
    {
        return $this->hasMany(Notificacion::class);
    }

    /**
     * Relación: User tiene muchos Anuncios
     */
    public function anuncios()
    {
        return $this->hasMany(Anuncio::class);
    }

    /**
     * Scope: Filtrar usuarios activos
     */
    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo');
    }

    /**
     * Scope: Filtrar por institución
     */
    public function scopePorInstitucion($query, $institucionId)
    {
        return $query->where('institucion_id', $institucionId);
    }

    /**
     * Obter nombre completo del usuario
     */
    public function getNombreCompletoAttribute(): string
    {
        return "{$this->nombres} {$this->apellidos}";
    }
}

