<?php

namespace App\Repositories;

use App\Models\Attendance;
use App\Models\AttendanceDetail;
use Illuminate\Pagination\Paginator;

class AttendanceRepository
{
    /**
     * Obtener asistencias paginadas
     */
    public function getAll(int $perPage = 15): Paginator
    {
        return Attendance::with(['course', 'teacher', 'section', 'details.student'])
            ->paginate($perPage);
    }

    /**
     * Obtener asistencias por institución
     */
    public function getByInstitution(int $institucionId, int $perPage = 15): Paginator
    {
        return Attendance::byInstitution($institucionId)
            ->with(['course', 'teacher', 'section', 'details.student'])
            ->paginate($perPage);
    }

    /**
     * Obtener asistencias por fecha
     */
    public function getByDate(string $date, int $perPage = 15): Paginator
    {
        return Attendance::byDate($date)
            ->with(['course', 'teacher', 'section', 'details.student'])
            ->paginate($perPage);
    }

    /**
     * Obtener una asistencia por ID
     */
    public function getById(int $id): ?Attendance
    {
        return Attendance::with(['course', 'teacher', 'section', 'details.student'])
            ->find($id);
    }

    /**
     * Obtener asistencias por curso
     */
    public function getByCourse(int $courseId, int $perPage = 15): Paginator
    {
        return Attendance::where('course_id', $courseId)
            ->with(['teacher', 'section', 'details.student'])
            ->paginate($perPage);
    }

    /**
     * Crear una asistencia
     */
    public function create(array $data): Attendance
    {
        return Attendance::create($data);
    }

    /**
     * Actualizar una asistencia
     */
    public function update(int $id, array $data): bool
    {
        $attendance = Attendance::find($id);
        if (!$attendance) {
            return false;
        }

        return $attendance->update($data);
    }

    /**
     * Eliminar una asistencia
     */
    public function delete(int $id): bool
    {
        $attendance = Attendance::find($id);
        if (!$attendance) {
            return false;
        }

        return $attendance->delete();
    }

    /**
     * Agregar detalles de asistencia
     */
    public function addDetail(int $attendanceId, array $detailData): AttendanceDetail
    {
        return AttendanceDetail::create([
            'attendance_id' => $attendanceId,
            ...$detailData,
        ]);
    }

    /**
     * Actualizar detalle de asistencia
     */
    public function updateDetail(int $detailId, array $data): bool
    {
        $detail = AttendanceDetail::find($detailId);
        if (!$detail) {
            return false;
        }

        return $detail->update($data);
    }

    /**
     * Obtener detalles de asistencia
     */
    public function getDetails(int $attendanceId)
    {
        return AttendanceDetail::where('attendance_id', $attendanceId)
            ->with('student')
            ->get();
    }
}
