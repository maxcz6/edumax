<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Estudiante extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'estudiantes';

    protected $fillable = [
        'user_id',
        'padre_id',
        'codigo_estudiante',
        'dni',
        'fecha_nacimiento',
        'genero',
        'direccion',
        'estado',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
    ];

    /**
     * Relación: Estudiante pertenece a un User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación: Estudiante pertenece a un Padre
     */
    public function padre(): BelongsTo
    {
        return $this->belongsTo(Padre::class);
    }

    /**
     * Relación: Estudiante tiene muchas Matrículas
     */
    public function matriculas(): HasMany
    {
        return $this->hasMany(Matricula::class);
    }

    /**
     * Relación: Estudiante tiene muchas Asistencias
     */
    public function asistencias(): HasMany
    {
        return $this->hasMany(Asistencia::class);
    }

    /**
     * Relación: Estudiante tiene muchas Notas
     */
    public function notas(): HasMany
    {
        return $this->hasMany(Nota::class);
    }

    /**
     * Relación: Estudiante tiene muchas Entregas de Tareas
     */
    public function entregas_tareas(): HasMany
    {
        return $this->hasMany(EntregaTarea::class);
    }

    /**
     * Scope: Filtrar activos
     */
    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo');
    }
}
