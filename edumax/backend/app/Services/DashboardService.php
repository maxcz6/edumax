<?php

namespace App\Services;

use App\Models\Estudiante;
use App\Models\Docente;
use App\Models\Attendance;
use App\Models\StudentGrade;
use App\Models\User;
use App\Models\Curso;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    /**
     * Obtener estadísticas para el Dashboard del Director
     */
    public function getDirectorStats(int $institucionId): array
    {
        $today = Carbon::today()->toDateString();

        // 1. Estudiantes activos
        $totalEstudiantes = Estudiante::where('institucion_id', $institucionId)
            ->where('estado', 'activo')
            ->count();

        // 2. Docentes activos
        $totalDocentes = Docente::where('institucion_id', $institucionId)
            ->where('estado', 'activo')
            ->count();

        // 3. Asistencia de hoy (porcentaje)
        $totalAsistenciasHoy = DB::table('attendances as a')
            ->join('attendance_details as ad', 'a.id', '=', 'ad.attendance_id')
            ->where('a.institucion_id', $institucionId)
            ->where('a.date', $today)
            ->count();
        
        $presentesHoy = DB::table('attendances as a')
            ->join('attendance_details as ad', 'a.id', '=', 'ad.attendance_id')
            ->where('a.institucion_id', $institucionId)
            ->where('a.date', $today)
            ->where('ad.status', 'present')
            ->count();

        $asistenciaRate = $totalAsistenciasHoy > 0 ? ($presentesHoy / $totalAsistenciasHoy) * 100 : 0;

        // 4. Promedio general de la institución
        $promedioGeneral = DB::table('student_grades as sg')
            ->join('evaluations as e', 'sg.evaluation_id', '=', 'e.id')
            ->where('e.institucion_id', $institucionId)
            ->avg('sg.score');

        // 5. Alertas (ej: alumnos con muchas inasistencias)
        $alertas = $this->getAlertas($institucionId);

        return [
            'total_estudiantes' => $totalEstudiantes,
            'total_docentes' => $totalDocentes,
            'asistencia_hoy_rate' => round($asistenciaRate, 2),
            'promedio_general' => round($promedioGeneral ?? 0, 2),
            'alertas' => $alertas,
            'matriculas_recientes' => $this->getMatriculasRecientes($institucionId),
        ];
    }

    /**
     * Obtener estadísticas para el Dashboard del Docente
     */
    public function getDocenteStats(int $docenteId): array
    {
        $today = Carbon::today()->toDateString();

        // 1. Cursos asignados
        $cursos = Docente::find($docenteId)->cursos ?? collect([]);
        $totalCursos = $cursos->count();

        // 2. Asistencia pendiente hoy
        // (Cursos que el docente tiene hoy pero no ha registrado asistencia)
        // Por ahora simplificado a cursos asignados que no tienen registro hoy
        $asistenciasRegistradasHoy = Attendance::where('teacher_id', $docenteId)
            ->where('date', $today)
            ->pluck('course_id')
            ->toArray();
        
        $pendientesAsistencia = $cursos->whereNotIn('id', $asistenciasRegistradasHoy)->count();

        // 3. Evaluaciones próximas o recientes
        $evaluaciones = DB::table('evaluations')
            ->where('course_id', '>', 0) // Placeholder logic for teacher's courses
            // En una implementación real, filtrar por cursos del docente
            ->where('fecha', '>=', Carbon::now()->subDays(7))
            ->orderBy('fecha', 'asc')
            ->limit(5)
            ->get();

        return [
            'total_cursos' => $totalCursos,
            'pendientes_asistencia' => $pendientesAsistencia,
            'evaluaciones_recientes' => $evaluaciones,
            'proximas_clases' => [], // Placeholder para integración con Horarios
        ];
    }

    /**
     * Obtener estadísticas para el Dashboard del Padre
     */
    public function getPadreStats(int $padreId): array
    {
        // Encontrar hijos (estudiantes relacionados al padre)
        // Por ahora asumimos una tabla student_parent o similar
        $hijos = Estudiante::where('padre_id', $padreId)->get();

        $resumenHijos = $hijos->map(function($hijo) {
            // Últimas notas
            $ultimasNotas = StudentGrade::where('student_id', $hijo->id)
                ->with('evaluation.course')
                ->latest()
                ->limit(3)
                ->get();

            // Asistencia reciente
            $asistenciaReciente = DB::table('attendance_details as ad')
                ->join('attendances as a', 'ad.attendance_id', '=', 'a.id')
                ->where('ad.student_id', $hijo->id)
                ->orderBy('a.date', 'desc')
                ->limit(5)
                ->get(['a.date', 'ad.status']);

            return [
                'id' => $hijo->id,
                'nombre' => $hijo->nombres . ' ' . $hijo->apellidos,
                'ultimas_notas' => $ultimasNotas,
                'asistencia_reciente' => $asistenciaReciente,
            ];
        });

        return [
            'hijos' => $resumenHijos,
            'comunicados' => [], // Placeholder para anuncios
        ];
    }

    private function getAlertas(int $institucionId): array
    {
        // Alumnos con más de 3 inasistencias este mes
        $alertasAsistencia = DB::table('attendance_details as ad')
            ->join('attendances as a', 'ad.attendance_id', '=', 'a.id')
            ->join('estudiantes as e', 'ad.student_id', '=', 'e.id')
            ->where('a.institucion_id', $institucionId)
            ->where('ad.status', 'absent')
            ->where('a.date', '>=', Carbon::now()->startOfMonth())
            ->select('e.nombres', 'e.apellidos', DB::raw('count(*) as total_faltas'))
            ->groupBy('e.id', 'e.nombres', 'e.apellidos')
            ->having('total_faltas', '>', 3)
            ->get();

        return [
            'asistencia' => $alertasAsistencia,
            'rendimiento' => [], // Placeholder para notas bajas
        ];
    }

    private function getMatriculasRecientes(int $institucionId): array
    {
        return DB::table('matriculas as m')
            ->join('estudiantes as e', 'm.estudiante_id', '=', 'e.id')
            ->where('m.institucion_id', $institucionId)
            ->orderBy('m.created_at', 'desc')
            ->limit(5)
            ->get(['e.nombres', 'e.apellidos', 'm.fecha_matricula', 'm.estado']);
    }
}
