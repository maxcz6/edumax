<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected DashboardService $service;

    public function __construct(DashboardService $service)
    {
        $this->service = $service;
    }

    /**
     * Dashboard para Directores (Métricas Institucionales)
     */
    public function director(Request $request): JsonResponse
    {
        $institucionId = $request->header('X-Institucion-ID') ?? $request->user()->institucion_id;

        if (!$institucionId) {
            return response()->json([
                'success' => false,
                'message' => 'ID de institución no proporcionado',
            ], 400);
        }

        $stats = $this->service->getDirectorStats($institucionId);

        return response()->json([
            'success' => true,
            'data' => $stats,
        ]);
    }

    /**
     * Dashboard para Docentes (Métricas de Aula)
     */
    public function docente(Request $request): JsonResponse
    {
        $user = $request->user();
        
        // Asumiendo que el usuario tiene una relación con Docente o el ID está en el modelo User
        $docenteId = $user->docente_id ?? $user->id; 

        $stats = $this->service->getDocenteStats($docenteId);

        return response()->json([
            'success' => true,
            'data' => $stats,
        ]);
    }

    /**
     * Dashboard para Padres (Seguimiento de Hijos)
     */
    public function padre(Request $request): JsonResponse
    {
        $user = $request->user();
        
        // Asumiendo que el usuario tiene una relación con Padre o el ID está en el modelo User
        $padreId = $user->padre_id ?? $user->id;

        $stats = $this->service->getPadreStats($padreId);

        return response()->json([
            'success' => true,
            'data' => $stats,
        ]);
    }
}
