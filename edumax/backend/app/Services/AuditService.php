<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Request;

class AuditService
{
    /**
     * Registrar una acción en los logs de auditoría
     */
    public function log(string $action, ?string $modelType = null, ?int $modelId = null, string $description = '', array $old = [], array $new = []): void
    {
        ActivityLog::create([
            'user_id' => auth()->id(),
            'institucion_id' => auth()->user()->institucion_id ?? null,
            'action' => $action,
            'model_type' => $modelType,
            'model_id' => $modelId,
            'description' => $description,
            'old_values' => $old,
            'new_values' => $new,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }
}
