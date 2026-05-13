<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEstudianteRequest;
use App\Http\Requests\UpdateEstudianteRequest;
use App\Http\Resources\EstudianteResource;
use App\Models\Estudiante;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class EstudianteController extends Controller
{
    /**
     * GET /api/v1/estudiantes
     */
    public function index(): AnonymousResourceCollection
    {
        $estudiantes = Estudiante::with('user')->paginate(15);

        return EstudianteResource::collection($estudiantes);
    }

    /**
     * POST /api/v1/estudiantes
     */
    public function store(StoreEstudianteRequest $request): JsonResponse
    {
        try {
            $estudiante = Estudiante::create($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Estudiante creado exitosamente',
                'data' => new EstudianteResource($estudiante->load('user')),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear estudiante',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * GET /api/v1/estudiantes/{estudiante}
     */
    public function show(Estudiante $estudiante): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new EstudianteResource($estudiante->load('user')),
        ]);
    }

    /**
     * PUT /api/v1/estudiantes/{estudiante}
     */
    public function update(UpdateEstudianteRequest $request, Estudiante $estudiante): JsonResponse
    {
        try {
            $estudiante->update($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Estudiante actualizado exitosamente',
                'data' => new EstudianteResource($estudiante->load('user')),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar estudiante',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * DELETE /api/v1/estudiantes/{estudiante}
     */
    public function destroy(Estudiante $estudiante): JsonResponse
    {
        try {
            $estudiante->delete();

            return response()->json([
                'success' => true,
                'message' => 'Estudiante eliminado exitosamente',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar estudiante',
                'error' => $e->getMessage(),
            ], 400);
        }
    }
}
