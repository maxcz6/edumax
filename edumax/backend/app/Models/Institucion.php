<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Institucion extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'instituciones';

    protected $fillable = [
        'nombre',
        'codigo_modular',
        'direccion',
        'telefono',
        'correo',
        'logo',
        'estado',
    ];

    /**
     * Relación: Institucion tiene muchos Users
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Relación: Institucion tiene muchos Grados
     */
    public function grados(): HasMany
    {
        return $this->hasMany(Grado::class);
    }

    /**
     * Relación: Institucion tiene muchos Cursos
     */
    public function cursos(): HasMany
    {
        return $this->hasMany(Curso::class);
    }

    /**
     * Relación: Institucion tiene muchos Anuncios
     */
    public function anuncios(): HasMany
    {
        return $this->hasMany(Anuncio::class);
    }

    /**
     * Relación: Institucion tiene muchas Configuraciones
     */
    public function configuraciones(): HasMany
    {
        return $this->hasMany(Configuracion::class);
    }

    /**
     * Scope: Filtrar activos
     */
    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo');
    }
}
