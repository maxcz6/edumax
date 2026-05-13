# IMPLEMENTACIÓN v2.1 — Estado y pasos (Multiinstitución + Roles)

Fecha: 2026-05-13

Resumen breve
- Esta versión (v2.1) documenta los cambios iniciales para Multiinstitución y el inicio de Roles/Permisos.
- Cambios aplicados en código y ubicación de archivos listada más abajo.

Qué se implementó (v2 → v2.1)

- Migraciones:
  - database/migrations/2026_05_13_000001_create_instituciones_table.php
  - database/migrations/2026_05_13_000002_create_institucion_user_table.php
- Modelo:
  - app/Models/Institucion.php
- Trait y scope global:
  - app/Traits/HasInstitucionScope.php — aplica filtro por institucion_actual cuando esté definido.
- Middleware:
  - app/Http/Middleware/SetInstitucion.php — resuelve y registra institucion_actual (header, ruta o usuario autenticado).
- User:
  - app/Models/User.php — añadido HasInstitucionScope para aplicar filtrado por institución.
- Seeders:
  - database/seeders/InstitucionSeeder.php — crea institución por defecto.
  - database/seeders/RoleSeeder.php — crea roles base si spatie/laravel-permission está instalado.

Estado actual
- Multiinstitución: implementada en migraciones y modelo; trait y middleware añadidos. (ESTADO: completado)
- Roles/Permisos: seeders añadidos; integración completa pendiente (instalación/configuración de Spatie). (ESTADO: en progreso)
- Resto de Fase 2 (estructura académica, perfiles, matrículas, asistencia, notas, notificaciones, auditoría, API v1): pendientes.

Comandos recomendados (aplicar localmente)

```
php artisan migrate
php artisan db:seed --class=InstitucionSeeder
php artisan db:seed --class=RoleSeeder
```

Registrar middleware en Kernel (si no lo haces manualmente)

Abrir app/Http/Kernel.php y en el grupo api añadir:

```
\App\Http\Middleware\SetInstitucion::class,
```

Notas importantes
- Para que el scope global funcione correctamente, los modelos con datos por institución deben tener columna institucion_id o relacionarse a instituciones y usar HasInstitucionScope.
- RoleSeeder solo crea roles si la clase Spatie\\Permission\\Models\\Role existe; debes instalar/configurar Spatie antes de usar roles/permissions plenamente.
- Recomiendo usar prefijo de rutas /api/v1/ en la próxima iteración para versionado.

Próximos pasos recomendados (prioridad)
1. Instalar y configurar spatie/laravel-permission (composer, publicar config/migrations, migrar).
2. Registrar middlewares role/permission (Spatie los provee) y crear políticas básicas (StudentPolicy, TeacherPolicy).
3. Añadir institucion_id y HasInstitucionScope a los modelos académicos: AcademicYear, Grade, Section, Course, Classroom, Student, Enrollment.
4. Scaffolding de migraciones y recursos para students, teachers, parents (API v1).

Opciones (elige una para que proceda):
- A: Registrar SetInstitucion en Kernel.php y aplicar HasInstitucionScope a modelos base (Grade, Section, Course, Student).
- B: Instalar/configurar spatie/laravel-permission y ejecutar migraciones + asignar roles iniciales.
- C: Crear migraciones y modelos para la Estructura Académica (academic_years, grades, sections, courses, classrooms).

Archivos modificados/añadidos (ubicación dentro del repo)

- backend/database/migrations/2026_05_13_000001_create_instituciones_table.php
- backend/database/migrations/2026_05_13_000002_create_institucion_user_table.php
- backend/app/Models/Institucion.php
- backend/app/Traits/HasInstitucionScope.php
- backend/app/Http/Middleware/SetInstitucion.php
- backend/app/Models/User.php (trait añadido)
- backend/database/seeders/InstitucionSeeder.php
- backend/database/seeders/RoleSeeder.php

Documento creado automáticamente para v2.1

Fin del documento v2.1
