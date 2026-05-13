<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckInstitution
{
    /**
     * Handle an incoming request.
     *
     * Verifica que el usuario pertenezca a la institución correcta
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado.',
            ], 401);
        }

        // Verificar que el usuario tenga estado activo
        if ($user->estado !== 'activo') {
            return response()->json([
                'success' => false,
                'message' => 'La cuenta está inactiva o suspendida.',
            ], 403);
        }

        return $next($request);
    }
}
