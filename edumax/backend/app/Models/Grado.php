<?php

namespace App\Models;

use App\Traits\HasInstitucionScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Grado extends Model
{
    use HasFactory, SoftDeletes, HasInstitucionScope;

    protected $table = 'grados';

    protected $fillable = [
        'institucion_id',
        'nombre',
        'nivel',
    ];

    /**
     * Relación: Grado pertenece a una Institución
     */
    public function institucion(): BelongsTo
    {
        return $this->belongsTo(Institucion::class);
    }

    /**
     * Relación: Grado tiene muchas Secciones
     */
    public function secciones(): HasMany
    {
        return $this->hasMany(Seccion::class);
    }

    /**
     * Relación: Grado tiene muchos Cursos
     */
    public function cursos(): HasMany
    {
        return $this->hasMany(Curso::class);
    }
}
