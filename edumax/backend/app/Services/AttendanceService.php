<?php

namespace App\Services;

use App\Repositories\AttendanceRepository;
use App\Models\Attendance;
use App\Events\AttendanceRegistered;
use Illuminate\Pagination\Paginator;

class AttendanceService
{
    protected AttendanceRepository $repository;

    public function __construct(AttendanceRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Obtener todas las asistencias
     */
    public function getAllAttendances(int $perPage = 15): Paginator
    {
        return $this->repository->getAll($perPage);
    }

    /**
     * Obtener asistencias por institución
     */
    public function getByInstitution(int $institucionId, int $perPage = 15): Paginator
    {
        return $this->repository->getByInstitution($institucionId, $perPage);
    }

    /**
     * Obtener asistencias por fecha
     */
    public function getByDate(string $date, int $perPage = 15): Paginator
    {
        return $this->repository->getByDate($date, $perPage);
    }

    /**
     * Obtener asistencia por ID
     */
    public function getAttendanceById(int $id): ?Attendance
    {
        return $this->repository->getById($id);
    }

    /**
     * Crear asistencia con detalles
     */
    public function createAttendance(array $data, array $details = []): ?Attendance
    {
        // Crear registro principal de asistencia
        $attendance = $this->repository->create($data);

        if (!$attendance) {
            return null;
        }

        // Agregar detalles de asistencia por estudiante
        foreach ($details as $detail) {
            $this->repository->addDetail($attendance->id, $detail);
        }

        $attendance->load('details.student', 'course');

        // Despachar evento para notificaciones, etc.
        event(new AttendanceRegistered($attendance));

        return $attendance;
    }

    /**
     * Actualizar asistencia
     */
    public function updateAttendance(int $id, array $data): bool
    {
        return $this->repository->update($id, $data);
    }

    /**
     * Eliminar asistencia
     */
    public function deleteAttendance(int $id): bool
    {
        return $this->repository->delete($id);
    }

    /**
     * Registrar asistencia de estudiante
     */
    public function registerStudentAttendance(int $attendanceId, int $studentId, string $status, ?string $remarks = null): bool
    {
        try {
            $this->repository->addDetail($attendanceId, [
                'student_id' => $studentId,
                'status' => $status,
                'remarks' => $remarks,
            ]);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Obtener estadísticas de asistencia
     */
    public function getAttendanceStats(int $courseId, int $studentId = null)
    {
        $query = Attendance::where('course_id', $courseId)
            ->with('details');

        if ($studentId) {
            $query->whereHas('details', function ($q) use ($studentId) {
                $q->where('student_id', $studentId);
            });
        }

        $attendances = $query->get();

        $stats = [
            'total' => 0,
            'present' => 0,
            'absent' => 0,
            'late' => 0,
            'justified' => 0,
            'attendance_rate' => 0,
        ];

        foreach ($attendances as $attendance) {
            foreach ($attendance->details as $detail) {
                $stats['total']++;

                match ($detail->status) {
                    'present' => $stats['present']++,
                    'absent' => $stats['absent']++,
                    'late' => $stats['late']++,
                    'justified' => $stats['justified']++,
                    default => null,
                };
            }
        }

        if ($stats['total'] > 0) {
            $stats['attendance_rate'] = ($stats['present'] / $stats['total']) * 100;
        }

        return $stats;
    }
}
