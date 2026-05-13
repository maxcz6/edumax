<?php

namespace App\Models;

use App\Traits\HasInstitucionScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Evaluation extends Model
{
    use HasFactory, SoftDeletes, HasInstitucionScope;

    protected $table = 'evaluations';

    protected $fillable = [
        'institucion_id',
        'course_id',
        'nombre',
        'tipo',
        'peso',
        'fecha',
        'bimestre',
        'descripcion',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function institucion(): BelongsTo
    {
        return $this->belongsTo(Institucion::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Curso::class);
    }

    public function grades(): HasMany
    {
        return $this->hasMany(StudentGrade::class);
    }

    public function scopeByType($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    public function scopeByBimestre($query, $bimestre)
    {
        return $query->where('bimestre', $bimestre);
    }

    public function scopeByCourse($query, $courseId)
    {
        return $query->where('course_id', $courseId);
    }

    /**
     * Obtener promedio de evaluación
     */
    public function getAverageScore(): ?float
    {
        return $this->grades()->whereNotNull('score')->avg('score');
    }
}
