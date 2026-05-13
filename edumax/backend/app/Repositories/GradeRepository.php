<?php

namespace App\Repositories;

use App\Models\Evaluation;
use App\Models\StudentGrade;
use Illuminate\Pagination\Paginator;

class GradeRepository
{
    /**
     * Obtener todas las evaluaciones paginadas
     */
    public function getAllEvaluations(int $perPage = 15): Paginator
    {
        return Evaluation::with(['course', 'grades.student'])
            ->paginate($perPage);
    }

    /**
     * Obtener evaluaciones por curso
     */
    public function getEvaluationsByCourse(int $courseId, int $perPage = 15): Paginator
    {
        return Evaluation::where('course_id', $courseId)
            ->with(['grades.student'])
            ->paginate($perPage);
    }

    /**
     * Obtener una evaluación por ID
     */
    public function getEvaluationById(int $id): ?Evaluation
    {
        return Evaluation::with(['grades.student'])->find($id);
    }

    /**
     * Crear evaluación
     */
    public function createEvaluation(array $data): Evaluation
    {
        return Evaluation::create($data);
    }

    /**
     * Actualizar evaluación
     */
    public function updateEvaluation(int $id, array $data): bool
    {
        $evaluation = Evaluation::find($id);
        if (!$evaluation) {
            return false;
        }

        return $evaluation->update($data);
    }

    /**
     * Eliminar evaluación
     */
    public function deleteEvaluation(int $id): bool
    {
        $evaluation = Evaluation::find($id);
        if (!$evaluation) {
            return false;
        }

        return $evaluation->delete();
    }

    /**
     * Registrar calificación de estudiante
     */
    public function recordGrade(array $data): StudentGrade
    {
        return StudentGrade::create($data);
    }

    /**
     * Actualizar calificación
     */
    public function updateGrade(int $gradeId, array $data): bool
    {
        $grade = StudentGrade::find($gradeId);
        if (!$grade) {
            return false;
        }

        return $grade->update($data);
    }

    /**
     * Obtener calificaciones de estudiante en un curso
     */
    public function getStudentGradesByCourse(int $studentId, int $courseId)
    {
        return StudentGrade::whereHas('evaluation', function ($q) use ($courseId) {
            $q->where('course_id', $courseId);
        })
        ->where('student_id', $studentId)
        ->with('evaluation')
        ->get();
    }

    /**
     * Calcular promedio ponderado
     */
    public function calculateWeightedAverage(int $studentId, int $courseId): ?float
    {
        $grades = $this->getStudentGradesByCourse($studentId, $courseId);

        if ($grades->isEmpty()) {
            return null;
        }

        $totalWeight = 0;
        $totalScore = 0;

        foreach ($grades as $grade) {
            if ($grade->score !== null) {
                $weight = $grade->evaluation->peso;
                $totalWeight += $weight;
                $totalScore += ($grade->score * $weight);
            }
        }

        return $totalWeight > 0 ? ($totalScore / $totalWeight) : null;
    }

    /**
     * Obtener calificaciones de todos los estudiantes en una evaluación
     */
    public function getGradesByEvaluation(int $evaluationId)
    {
        return StudentGrade::where('evaluation_id', $evaluationId)
            ->with('student')
            ->get();
    }
}
