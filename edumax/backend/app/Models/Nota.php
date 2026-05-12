<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Nota extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'notas';

    protected $fillable = [
        'estudiante_id',
        'curso_id',
        'tarea_id',
        'nota',
        'observacion',
        'fecha_registro',
    ];

    protected $casts = [
        'nota' => 'decimal:2',
        'fecha_registro' => 'datetime',
    ];

    /**
     * Relación: Nota pertenece a un Estudiante
     */
    public function estudiante(): BelongsTo
    {
        return $this->belongsTo(Estudiante::class);
    }

    /**
     * Relación: Nota pertenece a un Curso
     */
    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class);
    }

    /**
     * Relación: Nota pertenece a una Tarea (opcional)
     */
    public function tarea(): BelongsTo
    {
        return $this->belongsTo(Tarea::class);
    }

    /**
     * Scope: Filtrar notas aprobadas (>=11)
     */
    public function scopeAprobadas($query)
    {
        return $query->where('nota', '>=', 11);
    }

    /**
     * Scope: Filtrar notas desaprobadas (<11)
     */
    public function scopeDesaprobadas($query)
    {
        return $query->where('nota', '<', 11);
    }
}
