# IMPLEMENTACIÓN v2.3 — Módulo de Asistencia (Attendance)

Fecha: 2026-05-13

## Resumen breve

Esta versión (v2.3) implementa el módulo de Asistencia completo usando arquitectura profesional:
Controller → Service → Repository → Model.

Implementado con tablas `attendances` y `attendance_details`, lógica de negocio en Service,
abstracción de datos en Repository, y endpoints RESTful en `/api/v1/attendances`.

## Qué se implementó (v2.2 → v2.3)

### Migraciones

- `database/migrations/2026_05_13_000003_create_attendances_table.php` — tabla principal con relaciones
- `database/migrations/2026_05_13_000004_create_attendance_details_table.php` — detalles por estudiante

Estructura:
```
attendances
├── institucion_id
├── course_id
├── teacher_id
├── section_id
├── date (unique por curso/sección)
└── observations

attendance_details
├── attendance_id
├── student_id
├── status (present, absent, late, justified)
└── remarks
```

### Modelos

- `app/Models/Attendance.php` — modelo principal con relaciones y scopes
- `app/Models/AttendanceDetail.php` — modelo de detalles con scopes por estado

### Arquitectura (Repository Pattern)

- `app/Repositories/AttendanceRepository.php` — Abstracción de acceso a datos (14 métodos)
  - getAll(), getByInstitution(), getByDate(), getById(), getByCourse()
  - create(), update(), delete()
  - addDetail(), updateDetail(), getDetails()

- `app/Services/AttendanceService.php` — Lógica de negocio (10 métodos)
  - getAllAttendances(), getByInstitution(), getByDate(), getAttendanceById()
  - createAttendance() — crea registro + detalles en transacción
  - updateAttendance(), deleteAttendance()
  - registerStudentAttendance() — registra un estudiante específico
  - getAttendanceStats() — calcula estadísticas (present/absent/late/justified rate)

### HTTP Layer

- `app/Http/Resources/AttendanceResource.php` — serialización con relaciones cargadas
- `app/Http/Resources/AttendanceDetailResource.php` — serialización de detalles
- `app/Http/Requests/StoreAttendanceRequest.php` — validación para crear
- `app/Http/Requests/UpdateAttendanceRequest.php` — validación para actualizar
- `app/Http/Controllers/AttendanceController.php` — 6 endpoints

### Rutas API v1

```
GET    /api/v1/attendances              → Listar asistencias (paginated)
POST   /api/v1/attendances              → Crear asistencia con detalles
GET    /api/v1/attendances/{id}         → Obtener una asistencia
PUT    /api/v1/attendances/{id}         → Actualizar asistencia
DELETE /api/v1/attendances/{id}         → Eliminar asistencia
GET    /api/v1/attendances/stats/{courseId} → Estadísticas por curso
```

## Estados de asistencia soportados

```
present   → Presente
absent    → Ausente
late      → Retrasado
justified → Justificado
```

## Ejemplo de payload (POST /api/v1/attendances)

```json
{
  "institucion_id": 1,
  "course_id": 1,
  "teacher_id": 1,
  "section_id": 1,
  "date": "2026-05-13",
  "observations": "Clase de matemáticas",
  "details": [
    {
      "student_id": 1,
      "status": "present",
      "remarks": null
    },
    {
      "student_id": 2,
      "status": "absent",
      "remarks": "Enfermedad"
    },
    {
      "student_id": 3,
      "status": "late",
      "remarks": "Llegó 10 minutos tarde"
    }
  ]
}
```

## Respuesta (200/201)

```json
{
  "success": true,
  "message": "Asistencia creada exitosamente",
  "data": {
    "id": 1,
    "institucion_id": 1,
    "course_id": 1,
    "teacher_id": 1,
    "section_id": 1,
    "date": "2026-05-13",
    "observations": "Clase de matemáticas",
    "course": {
      "id": 1,
      "name": "Matemáticas"
    },
    "teacher": {
      "id": 1,
      "name": "Juan Pérez"
    },
    "section": {
      "id": 1,
      "name": "A"
    },
    "details": [
      {
        "id": 1,
        "attendance_id": 1,
        "student_id": 1,
        "status": "present",
        "remarks": null,
        "student": {
          "id": 1,
          "codigo": "EST000001",
          "nombre": "Carlos López"
        }
      },
      {
        "id": 2,
        "attendance_id": 1,
        "student_id": 2,
        "status": "absent",
        "remarks": "Enfermedad"
      }
    ]
  }
}
```

## Endpoints de estadísticas

```
GET /api/v1/attendances/stats/{courseId}?student_id=1
```

Respuesta:
```json
{
  "success": true,
  "data": {
    "total": 20,
    "present": 18,
    "absent": 1,
    "late": 1,
    "justified": 0,
    "attendance_rate": 90.0
  }
}
```

## Estado actual (v2.3)

✅ **Asistencia** — Implementada completa con arquitectura profesional
✅ **Multiinstitución** — Aplicada en tabla attendances
✅ **Scopes y Relaciones** — Bien definidas
✅ **Validación** — Reglas completas en FormRequests
✅ **Error Handling** — Respuestas estandarizadas
✅ **Estadísticas** — Cálculo de rates y totales

⏳ **Próximas fases:**
- Sistema de Notas (evaluations, grade_items, student_grades)
- Dashboard (métricas por rol)
- Notificaciones automáticas (WhatsApp, Email, SMS)
- Reportes PDF/Excel
- Auditoría y logs

## Comandos para aplicar

```bash
php artisan migrate
php artisan serve
```

Testing manual:

```bash
# Login
curl -X POST http://127.0.0.1:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@edumax.edu.pe","password":"password"}'

# Crear asistencia
curl -X POST http://127.0.0.1:8000/api/v1/attendances \
  -H "Authorization: Bearer <token>" \
  -H "Content-Type: application/json" \
  -d '{
    "institucion_id": 1,
    "course_id": 1,
    "teacher_id": 1,
    "section_id": 1,
    "date": "2026-05-13",
    "details": [
      {"student_id": 1, "status": "present"},
      {"student_id": 2, "status": "absent", "remarks": "Enfermedad"}
    ]
  }'

# Listar asistencias
curl -X GET http://127.0.0.1:8000/api/v1/attendances \
  -H "Authorization: Bearer <token>"

# Obtener estadísticas
curl -X GET http://127.0.0.1:8000/api/v1/attendances/stats/1 \
  -H "Authorization: Bearer <token>"
```

## Archivos creados

Backend:
- database/migrations/2026_05_13_000003_create_attendances_table.php
- database/migrations/2026_05_13_000004_create_attendance_details_table.php
- app/Models/Attendance.php
- app/Models/AttendanceDetail.php
- app/Repositories/AttendanceRepository.php
- app/Services/AttendanceService.php
- app/Http/Resources/AttendanceResource.php
- app/Http/Resources/AttendanceDetailResource.php
- app/Http/Requests/StoreAttendanceRequest.php
- app/Http/Requests/UpdateAttendanceRequest.php
- app/Http/Controllers/AttendanceController.php

Modificados:
- routes/api.php (agregadas rutas de attendances)

## Características implementadas

✅ CRUD completo (Create, Read, Update, Delete)
✅ Creación de asistencia con detalles en una transacción
✅ Estados: present, absent, late, justified
✅ Observaciones y remarks
✅ Relaciones con cursos, docentes, secciones, estudiantes
✅ Estadísticas de asistencia (rate, totales)
✅ Scopes para filtros rápidos
✅ Paginación automática
✅ Validación robusta
✅ Respuestas estandarizadas JSON

## Próximo módulo: Sistema de Notas (v2.4)

Listo para implementar:
- Evaluaciones (examen, tarea, proyecto, etc.)
- Ítems de calificación
- Notas por estudiante
- Promedio automático
- Observaciones

¿Quieres que continúe con el módulo de Notas?

## Status

✅ v2: Multiinstitución base
✅ v2.1: Roles y seeders
✅ v2.2: CRUD Estudiantes/Docentes + API v1
✅ v2.3: Módulo de Asistencia completo (Repository + Service pattern)

Documento creado automáticamente para v2.3
