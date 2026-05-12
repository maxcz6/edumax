<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Padre extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'padres';

    protected $fillable = [
        'user_id',
        'ocupacion',
        'parentesco',
    ];

    /**
     * Relación: Padre pertenece a un User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación: Padre tiene muchos Estudiantes
     */
    public function estudiantes(): HasMany
    {
        return $this->hasMany(Estudiante::class);
    }
}
