<?php

namespace App\Models;

use App\Traits\HasInstitucionScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentGrade extends Model
{
    use HasFactory, HasInstitucionScope;

    protected $table = 'student_grades';

    protected $fillable = [
        'institucion_id',
        'evaluation_id',
        'student_id',
        'score',
        'comentarios',
    ];

    protected $casts = [
        'score' => 'decimal:2',
    ];

    public function evaluation(): BelongsTo
    {
        return $this->belongsTo(Evaluation::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Estudiante::class);
    }

    /**
     * Scope: obtener estudiantes aprobados (nota >= 13)
     */
    public function scopePassed($query)
    {
        return $query->where('score', '>=', 13);
    }

    /**
     * Scope: obtener estudiantes desaprobados (nota < 13)
     */
    public function scopeFailed($query)
    {
        return $query->where('score', '<', 13);
    }
}
