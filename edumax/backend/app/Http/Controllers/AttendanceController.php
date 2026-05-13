<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAttendanceRequest;
use App\Http\Requests\UpdateAttendanceRequest;
use App\Http\Resources\AttendanceResource;
use App\Services\AttendanceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AttendanceController extends Controller
{
    protected AttendanceService $service;

    public function __construct(AttendanceService $service)
    {
        $this->service = $service;
    }

    /**
     * GET /api/v1/attendances
     */
    public function index(): AnonymousResourceCollection
    {
        $attendances = $this->service->getAllAttendances();

        return AttendanceResource::collection($attendances);
    }

    /**
     * POST /api/v1/attendances
     */
    public function store(StoreAttendanceRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $details = $data['details'] ?? [];
            unset($data['details']);

            $attendance = $this->service->createAttendance($data, $details);

            if (!$attendance) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al crear asistencia',
                ], 400);
            }

            return response()->json([
                'success' => true,
                'message' => 'Asistencia creada exitosamente',
                'data' => new AttendanceResource($attendance),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear asistencia',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * GET /api/v1/attendances/{attendance}
     */
    public function show(int $id): JsonResponse
    {
        $attendance = $this->service->getAttendanceById($id);

        if (!$attendance) {
            return response()->json([
                'success' => false,
                'message' => 'Asistencia no encontrada',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new AttendanceResource($attendance),
        ]);
    }

    /**
     * PUT /api/v1/attendances/{attendance}
     */
    public function update(UpdateAttendanceRequest $request, int $id): JsonResponse
    {
        try {
            $data = $request->validated();
            $details = $data['details'] ?? [];
            unset($data['details']);

            $updated = $this->service->updateAttendance($id, $data);

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Asistencia no encontrada',
                ], 404);
            }

            // Actualizar detalles si se proporcionan
            foreach ($details as $detail) {
                if (isset($detail['id'])) {
                    // Actualizar detalle existente
                    // $this->service->updateDetail($detail['id'], $detail);
                } else {
                    // Crear nuevo detalle
                    // $this->service->registerStudentAttendance($id, $detail['student_id'], $detail['status']);
                }
            }

            $attendance = $this->service->getAttendanceById($id);

            return response()->json([
                'success' => true,
                'message' => 'Asistencia actualizada exitosamente',
                'data' => new AttendanceResource($attendance),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar asistencia',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * DELETE /api/v1/attendances/{attendance}
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $deleted = $this->service->deleteAttendance($id);

            if (!$deleted) {
                return response()->json([
                    'success' => false,
                    'message' => 'Asistencia no encontrada',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Asistencia eliminada exitosamente',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar asistencia',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * GET /api/v1/attendances/stats/{courseId}
     */
    public function stats(int $courseId, ?int $studentId = null): JsonResponse
    {
        try {
            $stats = $this->service->getAttendanceStats($courseId, $studentId);

            return response()->json([
                'success' => true,
                'data' => $stats,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener estadísticas',
                'error' => $e->getMessage(),
            ], 400);
        }
    }
}
