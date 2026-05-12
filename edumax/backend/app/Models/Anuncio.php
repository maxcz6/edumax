<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Anuncio extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'anuncios';

    protected $fillable = [
        'institucion_id',
        'user_id',
        'titulo',
        'contenido',
        'publicado',
    ];

    protected $casts = [
        'publicado' => 'boolean',
    ];

    /**
     * Relación: Anuncio pertenece a una Institución
     */
    public function institucion(): BelongsTo
    {
        return $this->belongsTo(Institucion::class);
    }

    /**
     * Relación: Anuncio pertenece a un User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope: Filtrar publicados
     */
    public function scopePublicados($query)
    {
        return $query->where('publicado', true);
    }
}
