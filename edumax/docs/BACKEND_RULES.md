# BACKEND_RULES.md

# Reglas de Backend - EduMax SaaS

## Objetivo

Definir estándares, reglas técnicas y arquitectura backend para el sistema EduMax SaaS.

El backend debe ser:

* Escalable
* Seguro
* Modular
* Multiinstitución
* Compatible con PWA
* Optimizado para colegios con baja conectividad
* Preparado para integraciones externas

---

# Stack Tecnológico Oficial

## Backend

* Laravel 12
* PHP 8.3+
* MySQL 8+
* Redis (cache y queues)
* Sanctum (autenticación)
* Laravel Queues
* Laravel Scheduler

## Infraestructura

* Docker
* Nginx
* Supervisor
* AWS / DigitalOcean

## APIs externas

* WhatsApp API
* SMS API
* SMTP Mail
* Firebase Notifications (futuro)

---

# Arquitectura del Sistema

## Tipo de arquitectura

* SaaS Multi-Tenant
* API RESTful
* Backend desacoplado
* Modular por dominio

---

# Estructura de Carpetas

```txt
app/
 ├── Http/
 │    ├── Controllers/
 │    ├── Middleware/
 │    ├── Requests/
 │    └── Resources/
 │
 ├── Models/
 ├── Services/
 ├── Repositories/
 ├── Actions/
 ├── Jobs/
 ├── Events/
 ├── Notifications/
 ├── Policies/
 └── Helpers/
```

---

# Reglas Generales de Desarrollo

## Convenciones

### Variables

```php
$nombreCompleto
$fechaEntrega
```

### Métodos

```php
obtenerUsuarios()
registrarAsistencia()
```

### Clases

```php
UsuarioController
CursoService
MatriculaRepository
```

---

# Reglas para Base de Datos

## Todas las tablas deben incluir

```sql
id
created_at
updated_at
deleted_at
```

---

## Llaves primarias

```sql
BIGINT UNSIGNED AUTO_INCREMENT
```

---

## Llaves foráneas

Formato obligatorio:

```sql
tabla_id
```

Ejemplos:

```sql
usuario_id
curso_id
institucion_id
```

---

# Reglas de Multiinstitución

## Todas las entidades principales deben pertenecer a una institución

Ejemplo:

```sql
institucion_id
```

Tablas obligatorias:

* usuarios
* cursos
* grados
* configuraciones
* anuncios
* docentes

---

## Restricción crítica

Un usuario NO puede acceder a datos de otra institución.

Debe validarse mediante:

* Middleware
* Policies
* Scopes globales
* Tokens autenticados

---

# Reglas de Seguridad

## Contraseñas

Obligatorio:

```php
Hash::make()
```

Nunca almacenar contraseñas en texto plano.

---

## Autenticación

Sistema oficial:

* Laravel Sanctum

Opcional futuro:

* JWT
* OAuth2

---

## Roles y permisos

Los permisos deben validarse mediante:

* Middleware
* Policies
* Gates

Roles principales:

* Administrador
* Director
* Docente
* Estudiante
* Padre

---

## Protección de rutas

Todas las rutas privadas deben usar:

```php
auth:sanctum
```

Ejemplo:

```php
Route::middleware('auth:sanctum')->group(function () {
    // rutas privadas
});
```

---

# Reglas API REST

## Convenciones REST

### Ejemplos

```txt
GET    /api/cursos
POST   /api/cursos
GET    /api/cursos/{id}
PUT    /api/cursos/{id}
DELETE /api/cursos/{id}
```

---

## Respuestas JSON

Formato estándar:

```json
{
  "success": true,
  "message": "Operación exitosa",
  "data": []
}
```

Errores:

```json
{
  "success": false,
  "message": "Error de validación",
  "errors": {}
}
```

---

# Validaciones

## Todas las entradas deben validarse

Usar:

```php
FormRequest
```

Ejemplo:

```php
StoreUsuarioRequest
UpdateCursoRequest
```

---

## Validaciones obligatorias

* required
* string
* email
* unique
* exists
* max
* min

---

# Reglas de Modelos

## Cada tabla debe tener:

* Model
* Migration
* Factory
* Seeder
* Policy

---

## Uso obligatorio de relaciones Eloquent

Ejemplo:

```php
public function cursos()
{
    return $this->hasMany(Curso::class);
}
```

---

# Reglas para Controladores

## Los controladores NO deben contener lógica compleja

La lógica debe ir en:

* Services
* Actions
* Repositories

---

## Responsabilidad del Controller

Solo:

* recibir request
* validar
* llamar servicios
* devolver response

---

# Reglas para Services

## Los Services contienen lógica de negocio

Ejemplos:

```txt
AsistenciaService
NotificacionService
MatriculaService
```

---

# Reglas para Repositories

## Manejan consultas complejas

Ejemplos:

```txt
CursoRepository
UsuarioRepository
```

---

# Reglas para Jobs y Colas

## Todo proceso pesado debe ejecutarse en Queue

Ejemplos:

* envío WhatsApp
* envío SMS
* envío correos
* generación PDF
* exportación Excel

---

## Sistema recomendado

```txt
Redis + Laravel Queue
```

---

# Reglas para Notificaciones

## Tipos soportados

* Sistema
* WhatsApp
* SMS
* Correo

---

## Notificaciones deben ser asincrónicas

Usar:

```php
ShouldQueue
```

---

# Reglas de Soft Delete

## No eliminar información importante físicamente

Usar:

```php
SoftDeletes
```

Tablas importantes:

* usuarios
* estudiantes
* cursos
* matriculas

---

# Reglas de Auditoría

Registrar:

* inicio de sesión
* cambios importantes
* eliminación de registros
* acciones administrativas

Futuro:

```txt
activity_logs
```

---

# Reglas para Archivos

## Archivos permitidos

* PDF
* DOCX
* XLSX
* JPG
* PNG

---

## Almacenamiento

Usar:

```txt
storage/app/public
```

Nunca guardar archivos directamente en public/.

---

# Reglas de Rendimiento

## Evitar N+1 Queries

Usar:

```php
with()
```

Ejemplo:

```php
Curso::with('docente')->get();
```

---

## Usar índices en columnas críticas

Ejemplos:

```sql
correo
institucion_id
curso_id
estudiante_id
```

---

# Reglas para PWA y Baja Conectividad

## Backend optimizado para conexiones lentas

* respuestas JSON ligeras
* paginación obligatoria
* compresión gzip
* caché de consultas frecuentes

---

## Paginación

Nunca retornar listas masivas.

Usar:

```php
paginate(20)
```

---

# Reglas para Versionado API

## Estructura obligatoria

```txt
/api/v1/
```

Ejemplo:

```txt
/api/v1/cursos
```

---

# Reglas para Testing

## Tipos de pruebas

* Unit Tests
* Feature Tests
* API Tests

---

## Herramientas

* PHPUnit
* Pest PHP

---

# Reglas Git

## Branch principal

```txt
main
```

---

## Branch desarrollo

```txt
develop
```

---

## Convención commits

```txt
feat:
fix:
refactor:
chore:
```

Ejemplos:

```txt
feat: agregar módulo de asistencias
fix: corregir validación de matrícula
```

---

# Reglas para Logs

## Registrar errores importantes

Usar:

```php
Log::error()
Log::info()
```

---

# Módulos Oficiales del Sistema

1. Autenticación
2. Usuarios
3. Instituciones
4. Estudiantes
5. Docentes
6. Padres
7. Cursos
8. Matrículas
9. Asistencias
10. Tareas
11. Calificaciones
12. Notificaciones
13. Reportes
14. Configuración

---

# Objetivos Técnicos

El backend debe:

* soportar múltiples colegios
* ser mantenible
* escalar horizontalmente
* funcionar en zonas rurales
* soportar miles de estudiantes
* permitir futuras apps móviles
* integrarse con APIs externas

---

# Principios del Proyecto

## Simplicidad

El sistema debe ser simple de usar.

---

## Escalabilidad

El backend debe soportar crecimiento futuro.

---

## Seguridad

Toda acción sensible debe validarse.

---

## Modularidad

Cada módulo debe ser independiente.

---

## Mantenibilidad

El código debe ser limpio y documentado.
