<?php

namespace App\Listeners;

use App\Events\AttendanceRegistered;
use App\Jobs\SendNotificationJob;
use App\Models\AttendanceDetail;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyParentsOfAbsence implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(AttendanceRegistered $event): void
    {
        $absentDetails = $event->attendance->details()->where('status', 'absent')->get();

        foreach ($absentDetails as $detail) {
            // Lógica para obtener el teléfono del padre
            $student = $detail->student;
            $parentPhone = $student->padre->telefono ?? null;

            if ($parentPhone) {
                SendNotificationJob::dispatch([
                    'channels' => ['whatsapp', 'sms'],
                    'to_phone' => $parentPhone,
                    'message' => "EduMax: El estudiante {$student->nombres} faltó a la clase de {$event->attendance->course->nombre} hoy.",
                ]);
            }
        }
    }
}
