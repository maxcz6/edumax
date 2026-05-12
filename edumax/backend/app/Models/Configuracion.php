<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Configuracion extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'configuraciones';

    protected $fillable = [
        'institucion_id',
        'clave',
        'valor',
    ];

    /**
     * Relación: Configuración pertenece a una Institución
     */
    public function institucion(): BelongsTo
    {
        return $this->belongsTo(Institucion::class);
    }

    /**
     * Scope: Filtrar por clave
     */
    public function scopePorClave($query, $clave)
    {
        return $query->where('clave', $clave);
    }

    /**
     * Obtener valor de configuración
     */
    public static function obtener($institucionId, $clave, $default = null)
    {
        $config = self::where('institucion_id', $institucionId)
            ->where('clave', $clave)
            ->first();

        return $config?->valor ?? $default;
    }

    /**
     * Establecer valor de configuración
     */
    public static function establecer($institucionId, $clave, $valor)
    {
        return self::updateOrCreate(
            ['institucion_id' => $institucionId, 'clave' => $clave],
            ['valor' => $valor]
        );
    }
}
