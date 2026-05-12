<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class EntregaTarea extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'entregas_tareas';

    protected $fillable = [
        'tarea_id',
        'estudiante_id',
        'archivo',
        'comentario',
        'fecha_entrega',
        'calificado',
    ];

    protected $casts = [
        'fecha_entrega' => 'datetime',
        'calificado' => 'boolean',
    ];

    /**
     * Relación: Entrega pertenece a una Tarea
     */
    public function tarea(): BelongsTo
    {
        return $this->belongsTo(Tarea::class);
    }

    /**
     * Relación: Entrega pertenece a un Estudiante
     */
    public function estudiante(): BelongsTo
    {
        return $this->belongsTo(Estudiante::class);
    }

    /**
     * Scope: Filtrar calificadas
     */
    public function scopeCalificadas($query)
    {
        return $query->where('calificado', true);
    }

    /**
     * Scope: Filtrar pendientes de calificación
     */
    public function scopePendientesCalicar($query)
    {
        return $query->where('calificado', false);
    }
}
