<?php

namespace App\Traits;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\App;

trait HasInstitucionScope
{
    public static function bootHasInstitucionScope(): void
    {
        static::addGlobalScope('institucion', function (Builder $builder) {
            $institucion = null;

            // Prefer current application instance value if set
            if (App::has('institucion_actual')) {
                $institucion = App::get('institucion_actual');
            }

            // If an object provided, extract id
            if (is_object($institucion) && isset($institucion->id)) {
                $institucion = $institucion->id;
            }

            if ($institucion) {
                $builder->where(function ($q) use ($institucion) {
                    $q->where('institucion_id', $institucion);
                });
            }
        });
    }
}
