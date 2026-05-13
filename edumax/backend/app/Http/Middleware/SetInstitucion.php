<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use App\Models\Institucion;

class SetInstitucion
{
    public function handle(Request $request, Closure $next)
    {
        $institucion = null;

        // 1) Header X-Institucion-Id
        if ($request->header('X-Institucion-Id')) {
            $institucion = Institucion::find($request->header('X-Institucion-Id'));
        }

        // 2) Route parameter institucion_id
        if (!$institucion && $request->route('institucion_id')) {
            $institucion = Institucion::find($request->route('institucion_id'));
        }

        // 3) Authenticated user's primary institucion
        if (!$institucion && Auth::check()) {
            $user = Auth::user();
            if (method_exists($user, 'institucion') && $user->institucion) {
                $institucion = $user->institucion;
            }
        }

        if ($institucion) {
            App::instance('institucion_actual', $institucion);
        }

        return $next($request);
    }
}
