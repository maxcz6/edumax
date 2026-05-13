# IMPLEMENTACIÓN v2.4 — Sistema de Notas y Evaluaciones

Fecha: 2026-05-13

## Resumen breve

Esta versión (v2.4) implementa el **Sistema de Notas y Evaluaciones** completo. Sigue la arquitectura profesional de Repository + Service pattern, permitiendo una gestión robusta de calificaciones, promedios ponderados y reportes por bimestre.

## Qué se implementó (v2.3 → v2.4)

### Migraciones

- `database/migrations/2026_05_13_000005_create_evaluations_table.php` — tabla de evaluaciones por curso/bimestre.
- `database/migrations/2026_05_13_000006_create_student_grades_table.php` — tabla de calificaciones por estudiante.

### Modelos

- `app/Models/Evaluation.php` — relaciones con curso y notas, cálculo de promedios.
- `app/Models/StudentGrade.php` — relaciones con evaluación y estudiante.

### Arquitectura (Repository Pattern)

- `app/Repositories/GradeRepository.php` — Abstracción de acceso a datos de notas.
  - CRUD de evaluaciones.
  - Registro y actualización de calificaciones.
  - Cálculo de **Promedio Ponderado** (Weighted Average).
  - Reportes de calificaciones por curso/evaluación.

- `app/Services/GradeService.php` — Lógica de negocio avanzada.
  - Gestión de evaluaciones.
  - Registro de notas con validación de rango (0-20).
  - Cálculo de estadísticas de evaluación (promedio, aprobados, tasa de aprobación).
  - Generación de reportes de bimestre.

### HTTP Layer

- `app/Http/Resources/EvaluationResource.php` — Transformación de datos de evaluaciones.
- `app/Http/Resources/StudentGradeResource.php` — Transformación de datos de calificaciones.
- `app/Http/Requests/StoreEvaluationRequest.php` — Validación de creación de evaluación.
- `app/Http/Requests/UpdateEvaluationRequest.php` — Validación de actualización de evaluación.
- `app/Http/Requests/StoreStudentGradeRequest.php` — Validación de registro de nota.
- `app/Http/Requests/UpdateStudentGradeRequest.php` — Validación de actualización de nota.
- `app/Http/Controllers/GradeController.php` — 12 endpoints para notas y evaluaciones.

### Rutas API v1

```
GET    /api/v1/evaluations              → Listar evaluaciones
POST   /api/v1/evaluations              → Crear evaluación
GET    /api/v1/evaluations/{id}         → Ver detalle evaluación
PUT    /api/v1/evaluations/{id}         → Actualizar evaluación
DELETE /api/v1/evaluations/{id}         → Eliminar evaluación
GET    /api/v1/evaluations/course/{id}  → Evaluaciones por curso
GET    /api/v1/evaluations/{id}/stats   → Estadísticas (promedio, aprobados)

POST   /api/v1/grades                   → Registrar nota
PUT    /api/v1/grades/{id}              → Actualizar nota
GET    /api/v1/grades/student/{sId}/course/{cId} → Notas del alumno en curso
GET    /api/v1/grades/student/{sId}/course/{cId}/average → Promedio ponderado
GET    /api/v1/grades/course/{cId}/bimestre/{b} → Reporte de bimestre
```

## Características Core

✅ **Soporte Multibimestre** (1, 2, 3, 4).
✅ **Pesos (Weights)**: Cada evaluación puede tener un peso (ej. Examen 40%, Tarea 20%).
✅ **Promedio Ponderado Automático**: El sistema calcula la nota final basada en los pesos.
✅ **Validación Escala 0-20**: Siguiendo el estándar nacional.
✅ **Estadísticas en Tiempo Real**: Dashboards de aprobación/desaprobación.

## Ejemplo de uso

### Crear Evaluación
```bash
POST /api/v1/evaluations
{
  "institucion_id": 1,
  "course_id": 1,
  "nombre": "Examen Parcial",
  "tipo": "examen",
  "peso": 30,
  "bimestre": 1,
  "fecha": "2026-05-20"
}
```

### Registrar Nota
```bash
POST /api/v1/grades
{
  "evaluation_id": 1,
  "student_id": 1,
  "score": 15,
  "comentarios": "Buen desempeño"
}
```

## Estado actual

✅ **Asistencia (v2.3)** — Completa
✅ **Sistema de Notas (v2.4)** — Completo
✅ **Arquitectura Service-Repository** — Consistente en todo el proyecto
✅ **Validación y Resources** — Implementados

## Próximos pasos (Fase 3: Dashboard y Notificaciones)

- Implementar `DashboardService` para métricas consolidadas.
- Crear endpoints de Dashboard para Director, Docente y Padre.
- Integración con Servicio de Notificaciones (WhatsApp/Email).
