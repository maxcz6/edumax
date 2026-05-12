<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tarea extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tareas';

    protected $fillable = [
        'curso_id',
        'titulo',
        'descripcion',
        'archivo',
        'fecha_publicacion',
        'fecha_entrega',
        'estado',
    ];

    protected $casts = [
        'fecha_publicacion' => 'datetime',
        'fecha_entrega' => 'datetime',
    ];

    /**
     * Relación: Tarea pertenece a un Curso
     */
    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class);
    }

    /**
     * Relación: Tarea tiene muchas Entregas
     */
    public function entregas(): HasMany
    {
        return $this->hasMany(EntregaTarea::class);
    }

    /**
     * Relación: Tarea tiene muchas Notas
     */
    public function notas(): HasMany
    {
        return $this->hasMany(Nota::class);
    }

    /**
     * Scope: Filtrar activas
     */
    public function scopeActivas($query)
    {
        return $query->where('estado', 'activo');
    }

    /**
     * Scope: Filtrar por fecha de entrega
     */
    public function scopePendientes($query)
    {
        return $query->where('fecha_entrega', '>', now());
    }
}
