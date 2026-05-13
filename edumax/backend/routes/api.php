<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\DocenteController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes (v1)
|--------------------------------------------------------------------------
|
| Base URL: /api/v1
*/

// ============================================================
// RUTAS PÚBLICAS (Sin autenticación)
// ============================================================

Route::prefix('v1')->group(function () {
    // Health check
    Route::get('/test', function () {
        return response()->json([
            'success' => true,
            'message' => 'Laravel API v1 funcionando'
        ]);
    });

    // Autenticación pública
    Route::prefix('auth')->group(function () {
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/register', [AuthController::class, 'register']);
    });

    // ============================================================
    // RUTAS PROTEGIDAS (Requieren autenticación con Sanctum)
    // ============================================================

    Route::middleware('auth:sanctum')->group(function () {
        // Autenticación protegida
        Route::prefix('auth')->group(function () {
            Route::post('/logout', [AuthController::class, 'logout']);
            Route::post('/refresh', [AuthController::class, 'refresh']);
            Route::get('/me', [AuthController::class, 'obtenerMe']);
        });

        // Dashboard
        Route::prefix('dashboard')->group(function () {
            Route::get('/director', [DashboardController::class, 'director']);
            Route::get('/docente', [DashboardController::class, 'docente']);
            Route::get('/padre', [DashboardController::class, 'padre']);
        });

        // CRUD Estudiantes
        Route::apiResource('estudiantes', EstudianteController::class);

        // CRUD Docentes
        Route::apiResource('docentes', DocenteController::class);

        // CRUD Asistencia
        Route::apiResource('attendances', AttendanceController::class);
        Route::get('/attendances/stats/{courseId}', [AttendanceController::class, 'stats']);

        // CRUD Notas y Evaluaciones
        Route::prefix('evaluations')->group(function () {
            Route::get('/', [GradeController::class, 'indexEvaluations']);
            Route::post('/', [GradeController::class, 'storeEvaluation']);
            Route::get('/{id}', [GradeController::class, 'showEvaluation']);
            Route::put('/{id}', [GradeController::class, 'updateEvaluation']);
            Route::delete('/{id}', [GradeController::class, 'destroyEvaluation']);
            Route::get('/course/{courseId}', [GradeController::class, 'evaluationsByCourse']);
            Route::get('/{evaluationId}/stats', [GradeController::class, 'evaluationStats']);
        });

        Route::prefix('grades')->group(function () {
            Route::post('/', [GradeController::class, 'storeGrade']);
            Route::put('/{id}', [GradeController::class, 'updateGrade']);
            Route::get('/student/{studentId}/course/{courseId}', [GradeController::class, 'studentGradesByCourse']);
            Route::get('/student/{studentId}/course/{courseId}/average', [GradeController::class, 'studentAverageInCourse']);
            Route::get('/course/{courseId}/bimestre/{bimestre}', [GradeController::class, 'bimestreReport']);
        });

        // Rutas de ejemplo protegidas
        Route::get('/protected-test', function () {
            return response()->json([
                'success' => true,
                'message' => 'Ruta protegida accedida correctamente',
                'user' => auth()->user(),
            ]);
        });
    });
});