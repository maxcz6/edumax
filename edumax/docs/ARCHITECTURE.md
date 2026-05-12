# ARCHITECTURE.md

# Arquitectura General - EduMax SaaS

## Objetivo

Definir la arquitectura técnica oficial del sistema EduMax SaaS.

La arquitectura debe garantizar:

* Escalabilidad
* Modularidad
* Seguridad
* Separación de responsabilidades
* Soporte multiinstitución
* Compatibilidad con PWA
* Mantenibilidad a largo plazo

---

# Tipo de Arquitectura

## Arquitectura General

El sistema utiliza una arquitectura desacoplada:

```txt
Frontend (Vue.js)
        ↓
 REST API (Laravel)
        ↓
     MySQL
```

---

## Patrón arquitectónico

* SPA (Single Page Application)
* API RESTful
* Backend desacoplado
* SaaS Multi-Tenant
* Arquitectura modular

---

# Separación Frontend y Backend

## Regla principal

Frontend y backend deben estar completamente separados.

❌ No mezclar:

* Blade
* Livewire
* Vue dentro de Laravel

✅ Arquitectura correcta:

```txt
/frontend
/backend
```

Esto permite:

* Escalabilidad independiente
* Apps móviles futuras
* Mejor mantenimiento
* Desarrollo paralelo
* APIs reutilizables

---

# Arquitectura Frontend

## Ubicación

```txt
frontend/
```

---

## Tecnologías Oficiales

* Vue 3
* TypeScript
* Vite
* Tailwind CSS
* Pinia
* Vue Router
* Axios
* PWA

---

## Responsabilidades del Frontend

El frontend es responsable de:

* Interfaz de usuario
* Navegación
* Formularios
* Estado global
* Manejo offline
* Consumo API
* Validaciones visuales
* Experiencia de usuario
* Caché local

---

## Arquitectura Frontend

```txt
src/
├── api/
├── assets/
├── components/
├── composables/
├── layouts/
├── modules/
├── pages/
├── router/
├── services/
├── stores/
├── types/
├── utils/
└── views/
```

---

# Explicación de Carpetas Frontend

## api/

Configuración Axios y clientes API.

---

## components/

Componentes reutilizables.

Ejemplos:

```txt
Button.vue
Input.vue
Modal.vue
```

---

## composables/

Lógica reutilizable con Composition API.

Ejemplos:

```txt
useAuth.ts
usePagination.ts
```

---

## layouts/

Layouts principales del sistema.

Ejemplos:

```txt
AdminLayout.vue
AuthLayout.vue
```

---

## modules/

Arquitectura modular por dominio.

Ejemplos:

```txt
students/
courses/
attendance/
```

---

## stores/

Estado global con Pinia.

Ejemplos:

```txt
authStore.ts
userStore.ts
```

---

## services/

Servicios frontend.

Ejemplos:

```txt
AuthService.ts
CourseService.ts
```

---

# Reglas Frontend

## Composition API obligatorio

Usar:

```ts
<script setup lang="ts">
```

---

## TypeScript obligatorio

No usar JavaScript puro.

---

## Componentes pequeños

Cada componente debe tener:

* una responsabilidad
* reutilización
* bajo acoplamiento

---

## Estado global

Usar únicamente:

```txt
Pinia
```

No usar Vuex.

---

## Manejo API

Toda llamada HTTP debe centralizarse.

❌ Incorrecto:

```ts
axios.get()
```

Dentro de componentes.

✅ Correcto:

```txt
services/
api/
```

---

## Diseño UI

Usar:

* Tailwind CSS
* Diseño responsive
* Mobile First
* Accesibilidad básica

---

# Arquitectura Backend

## Ubicación

```txt
backend/
```

---

## Tecnologías Oficiales

* Laravel 13
* PHP 8.3+
* MySQL 8+
* Sanctum
* Redis
* Laravel Queue
* Laravel Scheduler

---

## Responsabilidades del Backend

El backend es responsable de:

* API REST
* Seguridad
* Validaciones
* Autenticación
* Autorización
* Lógica de negocio
* Persistencia de datos
* Integraciones externas
* Notificaciones
* Auditoría

---

# Arquitectura Backend

```txt
app/
├── Actions/
├── Console/
├── Events/
├── Helpers/
├── Http/
│   ├── Controllers/
│   ├── Middleware/
│   ├── Requests/
│   └── Resources/
├── Jobs/
├── Models/
├── Notifications/
├── Policies/
├── Repositories/
├── Services/
├── Traits/
└── Providers/
```

---

# Explicación de Carpetas Backend

## Controllers/

Reciben requests y retornan responses.

NO deben contener lógica compleja.

---

## Requests/

Validaciones del sistema.

Ejemplos:

```txt
StoreStudentRequest
UpdateCourseRequest
```

---

## Services/

Contienen lógica de negocio.

Ejemplos:

```txt
AttendanceService
NotificationService
```

---

## Repositories/

Consultas complejas a base de datos.

---

## Jobs/

Procesos asincrónicos.

Ejemplos:

* envío WhatsApp
* envío SMS
* exportaciones
* correos

---

## Policies/

Control de permisos.

---

# Comunicación Frontend ↔ Backend

## Flujo principal

```txt
Vue → Axios → Laravel API → MySQL
```

---

## Formato de comunicación

```txt
JSON
```

---

## Respuesta estándar

### Success

```json
{
  "success": true,
  "message": "Operación exitosa",
  "data": {}
}
```

---

### Error

```json
{
  "success": false,
  "message": "Error de validación",
  "errors": {}
}
```

---

# Arquitectura API REST

## Versionado obligatorio

```txt
/api/v1/
```

Ejemplos:

```txt
/api/v1/auth/login
/api/v1/students
/api/v1/courses
```

---

## Métodos HTTP

```txt
GET
POST
PUT
PATCH
DELETE
```

---

# Arquitectura de Base de Datos

## Motor Oficial

```txt
MySQL 8+
```

---

## Características

* Multiinstitución
* Relaciones normalizadas
* Soft Deletes
* Índices optimizados
* Escalable

---

# Arquitectura de Seguridad

## Sistema de autenticación

Usar:

```txt
Laravel Sanctum
```

---

## Protección API

```php
Route::middleware('auth:sanctum')
```

---

## Seguridad obligatoria

* Hash passwords
* Validaciones backend
* Policies
* Middleware
* Rate limiting
* CORS controlado

---

# Arquitectura Multiinstitución

## Regla principal

Cada institución debe estar aislada.

---

## Restricciones

Un usuario NO puede:

* ver datos de otra institución
* modificar registros externos
* acceder fuera de su tenant

---

## Validación

Debe controlarse mediante:

* Middleware
* Policies
* Global scopes
* Tokens

---

# Arquitectura PWA

## Objetivo

Permitir funcionamiento parcial offline.

---

## Características

* Service Workers
* Caché local
* Instalación móvil
* Baja conectividad
* Sincronización futura

---

# Arquitectura de Escalabilidad

## Backend preparado para:

* múltiples colegios
* miles de estudiantes
* múltiples docentes
* alta concurrencia

---

## Tecnologías recomendadas

* Redis
* Docker
* Queue Workers
* CDN
* Load Balancer

---

# Arquitectura de Integraciones

## APIs externas futuras

* WhatsApp API
* SMS API
* SMTP
* Firebase
* SUNAT (futuro)

---

# Reglas de Desarrollo

## Convenciones generales

* Código modular
* Services Pattern
* Repository Pattern
* REST API
* JSON responses
* Componentes reutilizables
* Clean Code

---

# Reglas de Rendimiento

## Frontend

* Lazy loading
* Code splitting
* Componentes ligeros
* Optimización imágenes

---

## Backend

* Cache queries
* Queue jobs
* Eager loading
* Índices SQL
* Paginación obligatoria

---

# Infraestructura Recomendada

## Producción

```txt
Nginx
PHP-FPM
Redis
MySQL
Supervisor
Docker
```

---

# Objetivos de Arquitectura

La arquitectura debe:

* ser mantenible
* ser escalable
* soportar crecimiento futuro
* permitir aplicaciones móviles
* soportar colegios rurales
* funcionar con conexiones lentas
* ser segura
* permitir integraciones externas

---

# Resultado Esperado

EduMax debe funcionar como:

* SaaS educativo profesional
* Plataforma multiinstitución
* Sistema académico moderno
* Moodle simplificado
* Plataforma compatible con PWA
