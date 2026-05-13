<?php

namespace App\Services;

use App\Repositories\GradeRepository;
use App\Models\Evaluation;
use App\Models\StudentGrade;
use Illuminate\Pagination\Paginator;

class GradeService
{
    protected GradeRepository $repository;

    public function __construct(GradeRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Obtener todas las evaluaciones
     */
    public function getAllEvaluations(int $perPage = 15): Paginator
    {
        return $this->repository->getAllEvaluations($perPage);
    }

    /**
     * Obtener evaluaciones por curso
     */
    public function getEvaluationsByCourse(int $courseId, int $perPage = 15): Paginator
    {
        return $this->repository->getEvaluationsByCourse($courseId, $perPage);
    }

    /**
     * Obtener evaluación por ID
     */
    public function getEvaluationById(int $id): ?Evaluation
    {
        return $this->repository->getEvaluationById($id);
    }

    /**
     * Crear evaluación
     */
    public function createEvaluation(array $data): ?Evaluation
    {
        try {
            return $this->repository->createEvaluation($data);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Actualizar evaluación
     */
    public function updateEvaluation(int $id, array $data): bool
    {
        return $this->repository->updateEvaluation($id, $data);
    }

    /**
     * Eliminar evaluación
     */
    public function deleteEvaluation(int $id): bool
    {
        return $this->repository->deleteEvaluation($id);
    }

    /**
     * Registrar calificación de estudiante
     */
    public function recordStudentGrade(int $evaluationId, int $studentId, float $score, ?string $comentarios = null): ?StudentGrade
    {
        // Validar rango de nota (0-20)
        if ($score < 0 || $score > 20) {
            return null;
        }

        try {
            return $this->repository->recordGrade([
                'evaluation_id' => $evaluationId,
                'student_id' => $studentId,
                'score' => $score,
                'comentarios' => $comentarios,
            ]);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Actualizar calificación
     */
    public function updateStudentGrade(int $gradeId, float $score, ?string $comentarios = null): bool
    {
        if ($score < 0 || $score > 20) {
            return false;
        }

        return $this->repository->updateGrade($gradeId, [
            'score' => $score,
            'comentarios' => $comentarios,
        ]);
    }

    /**
     * Obtener promedio ponderado del estudiante en un curso
     */
    public function getStudentAverageInCourse(int $studentId, int $courseId): ?float
    {
        return $this->repository->calculateWeightedAverage($studentId, $courseId);
    }

    /**
     * Obtener calificaciones del estudiante en un curso
     */
    public function getStudentGradesByCourse(int $studentId, int $courseId)
    {
        return $this->repository->getStudentGradesByCourse($studentId, $courseId);
    }

    /**
     * Obtener estadísticas de evaluación
     */
    public function getEvaluationStats(int $evaluationId): array
    {
        $grades = $this->repository->getGradesByEvaluation($evaluationId);

        $scores = $grades->filter(fn($g) => $g->score !== null)->pluck('score');

        if ($scores->isEmpty()) {
            return [
                'total_students' => 0,
                'graded' => 0,
                'average' => null,
                'highest' => null,
                'lowest' => null,
                'passed' => 0,
                'failed' => 0,
            ];
        }

        $passed = $scores->filter(fn($s) => $s >= 13)->count();

        return [
            'total_students' => $grades->count(),
            'graded' => $scores->count(),
            'average' => $scores->avg(),
            'highest' => $scores->max(),
            'lowest' => $scores->min(),
            'passed' => $passed,
            'failed' => $scores->count() - $passed,
            'pass_rate' => ($passed / $scores->count()) * 100,
        ];
    }

    /**
     * Obtener reporte de calificaciones por bimestre
     */
    public function getBimestreReport(int $courseId, int $bimestre): array
    {
        $evaluations = Evaluation::where('course_id', $courseId)
            ->where('bimestre', $bimestre)
            ->with('grades.student')
            ->get();

        return [
            'bimestre' => $bimestre,
            'evaluations' => $evaluations->map(function ($eval) {
                return [
                    'id' => $eval->id,
                    'nombre' => $eval->nombre,
                    'tipo' => $eval->tipo,
                    'peso' => $eval->peso,
                    'promedio' => $eval->getAverageScore(),
                    'grades_count' => $eval->grades->count(),
                ];
            }),
        ];
    }
}
