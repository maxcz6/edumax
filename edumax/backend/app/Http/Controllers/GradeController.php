<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEvaluationRequest;
use App\Http\Requests\UpdateEvaluationRequest;
use App\Http\Requests\StoreStudentGradeRequest;
use App\Http\Requests\UpdateStudentGradeRequest;
use App\Http\Resources\EvaluationResource;
use App\Http\Resources\StudentGradeResource;
use App\Services\GradeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GradeController extends Controller
{
    protected GradeService $service;

    public function __construct(GradeService $service)
    {
        $this->service = $service;
    }

    // ========================
    // EVALUATIONS ENDPOINTS
    // ========================

    /**
     * GET /api/v1/evaluations
     */
    public function indexEvaluations(): AnonymousResourceCollection
    {
        $evaluations = $this->service->getAllEvaluations();

        return EvaluationResource::collection($evaluations);
    }

    /**
     * POST /api/v1/evaluations
     */
    public function storeEvaluation(StoreEvaluationRequest $request): JsonResponse
    {
        try {
            $evaluation = $this->service->createEvaluation($request->validated());

            if (!$evaluation) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al crear evaluación',
                ], 400);
            }

            return response()->json([
                'success' => true,
                'message' => 'Evaluación creada exitosamente',
                'data' => new EvaluationResource($evaluation),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear evaluación',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * GET /api/v1/evaluations/{evaluation}
     */
    public function showEvaluation(int $id): JsonResponse
    {
        $evaluation = $this->service->getEvaluationById($id);

        if (!$evaluation) {
            return response()->json([
                'success' => false,
                'message' => 'Evaluación no encontrada',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new EvaluationResource($evaluation),
        ]);
    }

    /**
     * PUT /api/v1/evaluations/{evaluation}
     */
    public function updateEvaluation(UpdateEvaluationRequest $request, int $id): JsonResponse
    {
        $updated = $this->service->updateEvaluation($id, $request->validated());

        if (!$updated) {
            return response()->json([
                'success' => false,
                'message' => 'Evaluación no encontrada',
            ], 404);
        }

        $evaluation = $this->service->getEvaluationById($id);

        return response()->json([
            'success' => true,
            'message' => 'Evaluación actualizada exitosamente',
            'data' => new EvaluationResource($evaluation),
        ]);
    }

    /**
     * DELETE /api/v1/evaluations/{evaluation}
     */
    public function destroyEvaluation(int $id): JsonResponse
    {
        $deleted = $this->service->deleteEvaluation($id);

        if (!$deleted) {
            return response()->json([
                'success' => false,
                'message' => 'Evaluación no encontrada',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Evaluación eliminada exitosamente',
        ]);
    }

    /**
     * GET /api/v1/evaluations/course/{courseId}
     */
    public function evaluationsByCourse(int $courseId): AnonymousResourceCollection
    {
        $evaluations = $this->service->getEvaluationsByCourse($courseId);

        return EvaluationResource::collection($evaluations);
    }

    /**
     * GET /api/v1/evaluations/{evaluationId}/stats
     */
    public function evaluationStats(int $evaluationId): JsonResponse
    {
        try {
            $stats = $this->service->getEvaluationStats($evaluationId);

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

    // ========================
    // GRADES ENDPOINTS
    // ========================

    /**
     * POST /api/v1/grades
     */
    public function storeGrade(StoreStudentGradeRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $grade = $this->service->recordStudentGrade(
                $data['evaluation_id'],
                $data['student_id'],
                $data['score'],
                $data['comentarios'] ?? null
            );

            if (!$grade) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al registrar calificación',
                ], 400);
            }

            return response()->json([
                'success' => true,
                'message' => 'Calificación registrada exitosamente',
                'data' => new StudentGradeResource($grade->load('evaluation', 'student')),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar calificación',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * PUT /api/v1/grades/{gradeId}
     */
    public function updateGrade(UpdateStudentGradeRequest $request, int $id): JsonResponse
    {
        try {
            $data = $request->validated();
            $updated = $this->service->updateStudentGrade(
                $id,
                $data['score'],
                $data['comentarios'] ?? null
            );

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Calificación no encontrada',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Calificación actualizada exitosamente',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar calificación',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * GET /api/v1/grades/student/{studentId}/course/{courseId}
     */
    public function studentGradesByCourse(int $studentId, int $courseId): AnonymousResourceCollection
    {
        $grades = $this->service->getStudentGradesByCourse($studentId, $courseId);

        return StudentGradeResource::collection($grades);
    }

    /**
     * GET /api/v1/grades/student/{studentId}/course/{courseId}/average
     */
    public function studentAverageInCourse(int $studentId, int $courseId): JsonResponse
    {
        try {
            $average = $this->service->getStudentAverageInCourse($studentId, $courseId);

            return response()->json([
                'success' => true,
                'data' => [
                    'student_id' => $studentId,
                    'course_id' => $courseId,
                    'average' => $average,
                    'passed' => $average !== null && $average >= 13,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener promedio',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * GET /api/v1/grades/course/{courseId}/bimestre/{bimestre}
     */
    public function bimestreReport(int $courseId, int $bimestre): JsonResponse
    {
        try {
            $report = $this->service->getBimestreReport($courseId, $bimestre);

            return response()->json([
                'success' => true,
                'data' => $report,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener reporte de bimestre',
                'error' => $e->getMessage(),
            ], 400);
        }
    }
}
