<?php

namespace App\Models;

use App\Traits\HasInstitucionScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attendance extends Model
{
    use HasFactory, SoftDeletes, HasInstitucionScope;

    protected $table = 'attendances';

    protected $fillable = [
        'institucion_id',
        'course_id',
        'teacher_id',
        'section_id',
        'date',
        'observations',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function institucion(): BelongsTo
    {
        return $this->belongsTo(Institucion::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Curso::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Docente::class);
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(Seccion::class);
    }

    public function details(): HasMany
    {
        return $this->hasMany(AttendanceDetail::class);
    }

    public function scopeByDate($query, $date)
    {
        return $query->whereDate('date', $date);
    }

    public function scopeByInstitution($query, $institucionId)
    {
        return $query->where('institucion_id', $institucionId);
    }
}
