<?php

namespace App\Models;

use App\Traits\HasInstitucionScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Docente extends Model
{
    use HasFactory, SoftDeletes, HasInstitucionScope;

    protected $table = 'docentes';

    protected $fillable = [
        'institucion_id',
        'user_id',
        'especialidad',
        'grado_academico',
        'fecha_contratacion',
    ];

    protected $casts = [
        'fecha_contratacion' => 'date',
    ];

    /**
     * Relación: Docente pertenece a un User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación: Docente tiene muchos Cursos
     */
    public function cursos(): HasMany
    {
        return $this->hasMany(Curso::class);
    }
}
