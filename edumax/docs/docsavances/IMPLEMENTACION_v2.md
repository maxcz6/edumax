# IMPLEMENTACIÓN v2 — Multiinstitución y Roles

Resumen de cambios realizados (fase 2 - paso inicial): Multiinstitución + Roles (preparación)

Archivos añadidos (backend):

- `database/migrations/2026_05_13_000001_create_instituciones_table.php` — tabla `instituciones`.
- `database/migrations/2026_05_13_000002_create_institucion_user_table.php` — tabla pivote `institucion_user`.
- `app/Models/Institucion.php` — modelo `Institucion`.
- `app/Traits/HasInstitucionScope.php` — trait para aplicar scope global por institución.
- `app/Http/Middleware/SetInstitucion.php` — middleware para determinar la `institucion_actual`.
- `database/seeders/InstitucionSeeder.php` — seeder para crear institución por defecto.
- `database/seeders/RoleSeeder.php` — seeder para crear roles base usando Spatie (si está instalado).

Cambios en modelos existentes:

- `app/Models/User.php`: se añadió el trait `HasInstitucionScope` para que las consultas sobre `User` respeten la institución actual cuando esté disponible.

Qué hace esto ahora:

- Crea la entidad `Institucion` y la tabla pivote `institucion_user` (usuario ↔ institución).
- Añade un trait `HasInstitucionScope` que aplica un filtro global por `institucion_id` cuando exista `institucion_actual` en el contenedor de la aplicación.
- Añade un `SetInstitucion` middleware que intenta resolver la institución actual por: header `X-Institucion-Id`, parámetro de ruta `institucion_id`, o la institución primaria del usuario autenticado.
- Seeders disponibles para crear una institución por defecto y roles base (si Spatie está instalado).

Instrucciones rápidas para aplicar localmente:

1) Ejecutar migraciones:

```bash
php artisan migrate
```

2) Ejecutar seeders:

```bash
php artisan db:seed --class=InstitucionSeeder
php artisan db:seed --class=RoleSeeder
```

3) Registrar middleware (si no está): añadir en `app/Http/Kernel.php` de `api` o `web` según convenga:

```php
protected $middlewareGroups = [
    'api' => [
        // ...
        \App\Http\Middleware\SetInstitucion::class,
    ],
];
```

Notas y siguientes pasos (recomendado):

- Registrar `HasInstitucionScope` en los modelos que contengan `institucion_id` (ej: `Curso`, `Grado`, `Estudiante`, `Matricula`, etc.).
- Convertir las rutas públicas a prefijo `/api/v1/` (siguiente tarea).
- Instalar y configurar `spatie/laravel-permission` si aún no está instalado: composer require spatie/laravel-permission
  - publicar migraciones y ejecutar `php artisan migrate`
  - ejecutar `RoleSeeder` para crear roles base
- Crear `role` y `permission` middlewares (Spatie provee `role` y `permission` middleware listos para usar tras publicar config).
- Actualizar `AuthService` y `AuthController` para incluir `institucion_id` al crear users (register) y asignar rol inicial.

Prioridad inmediata (recomendado):

1. Asegurar que el campo `institucion_id` o la relación pivote está presente en todos los modelos que contienen datos sensibles a la institución.
2. Añadir el trait `HasInstitucionScope` a esos modelos.
3. Registrar y probar `SetInstitucion` middleware.
4. Instalar `spatie/laravel-permission` y ejecutar `RoleSeeder`.

Futuras iteraciones (siguiendo la lista de la Fase 2):

- Estructura académica (años, grados, secciones, cursos, aulas).
- Separar perfiles (teachers/students/parents) con tablas específicas.
- Matrículas, asistencias, notas, notificaciones, auditoría, reportes.

Si quieres, procedo ahora a:

- (A) Registrar el middleware en `app/Http/Kernel.php` y añadir el trait `HasInstitucionScope` a modelos seleccionados (indica cuáles).
- (B) Implementar scaffolding de `instituciones` API (`/api/v1/instituciones`) con controladores y recursos.
