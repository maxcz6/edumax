<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notificacion extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'notificaciones';

    protected $fillable = [
        'user_id',
        'titulo',
        'mensaje',
        'tipo',
        'leido',
        'fecha_envio',
    ];

    protected $casts = [
        'fecha_envio' => 'datetime',
        'leido' => 'boolean',
    ];

    /**
     * Relación: Notificación pertenece a un User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope: Filtrar no leídas
     */
    public function scopeNoLeidas($query)
    {
        return $query->where('leido', false);
    }

    /**
     * Scope: Filtrar leídas
     */
    public function scopeLeidas($query)
    {
        return $query->where('leido', true);
    }

    /**
     * Scope: Filtrar por tipo
     */
    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    /**
     * Marcar como leída
     */
    public function marcarLeida()
    {
        $this->update(['leido' => true]);
        return $this;
    }
}
