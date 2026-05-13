<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDocenteRequest;
use App\Http\Requests\UpdateDocenteRequest;
use App\Http\Resources\DocenteResource;
use App\Models\Docente;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class DocenteController extends Controller
{
    /**
     * GET /api/v1/docentes
     */
    public function index(): AnonymousResourceCollection
    {
        $docentes = Docente::with('user')->paginate(15);

        return DocenteResource::collection($docentes);
    }

    /**
     * POST /api/v1/docentes
     */
    public function store(StoreDocenteRequest $request): JsonResponse
    {
        try {
            $docente = Docente::create($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Docente creado exitosamente',
                'data' => new DocenteResource($docente->load('user')),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear docente',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * GET /api/v1/docentes/{docente}
     */
    public function show(Docente $docente): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new DocenteResource($docente->load('user')),
        ]);
    }

    /**
     * PUT /api/v1/docentes/{docente}
     */
    public function update(UpdateDocenteRequest $request, Docente $docente): JsonResponse
    {
        try {
            $docente->update($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Docente actualizado exitosamente',
                'data' => new DocenteResource($docente->load('user')),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar docente',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * DELETE /api/v1/docentes/{docente}
     */
    public function destroy(Docente $docente): JsonResponse
    {
        try {
            $docente->delete();

            return response()->json([
                'success' => true,
                'message' => 'Docente eliminado exitosamente',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar docente',
                'error' => $e->getMessage(),
            ], 400);
        }
    }
}
