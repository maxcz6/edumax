<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Seccion extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'secciones';

    protected $fillable = [
        'grado_id',
        'nombre',
        'aula',
    ];

    /**
     * Relación: Seccion pertenece a un Grado
     */
    public function grado(): BelongsTo
    {
        return $this->belongsTo(Grado::class);
    }

    /**
     * Relación: Seccion tiene muchas Matrículas
     */
    public function matriculas(): HasMany
    {
        return $this->hasMany(Matricula::class);
    }
}
