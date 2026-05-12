# Base de Datos - EduMax SaaS

## Información General

**Motor:** MySQL 8+

**Nombre de la Base de Datos:** `edumax`

**Arquitectura:**

* Multiinstitución (multi-tenant)
* Compatible con Laravel
* Preparada para PWA
* Integración futura con WhatsApp API y SMS

---

# Convenciones

Todas las tablas incluyen:

* `id`
* `created_at`
* `updated_at`
* `deleted_at` (Soft Delete opcional)

Claves primarias:

* `id BIGINT UNSIGNED AUTO_INCREMENT`

Claves foráneas:

* Formato: `tabla_id`

---

# Tablas Principales

## roles

Define los tipos de usuario del sistema.

| Campo       | Tipo         | Descripción         |
| ----------- | ------------ | ------------------- |
| id          | BIGINT       | PK                  |
| nombre      | VARCHAR(50)  | Nombre del rol      |
| descripcion | VARCHAR(255) | Descripción del rol |

### Roles sugeridos

* Administrador
* Director
* Docente
* Estudiante
* Padre de familia

---

## instituciones

Instituciones educativas registradas en la plataforma.

| Campo          | Tipo                      |
| -------------- | ------------------------- |
| id             | BIGINT                    |
| nombre         | VARCHAR(150)              |
| codigo_modular | VARCHAR(50)               |
| direccion      | VARCHAR(255)              |
| telefono       | VARCHAR(20)               |
| correo         | VARCHAR(120)              |
| logo           | VARCHAR(255)              |
| estado         | ENUM('activo','inactivo') |

---

## usuarios

Usuarios principales del sistema.

| Campo          | Tipo                                   |
| -------------- | -------------------------------------- |
| id             | BIGINT                                 |
| institucion_id | BIGINT                                 |
| rol_id         | BIGINT                                 |
| nombres        | VARCHAR(100)                           |
| apellidos      | VARCHAR(100)                           |
| correo         | VARCHAR(120) UNIQUE                    |
| password       | VARCHAR(255)                           |
| telefono       | VARCHAR(20)                            |
| foto_perfil    | VARCHAR(255)                           |
| ultimo_acceso  | DATETIME                               |
| estado         | ENUM('activo','inactivo','suspendido') |

---

## docentes

Información específica de docentes.

| Campo              | Tipo          |
| ------------------ | ------------- |
| id                 | BIGINT        |
| usuario_id         | BIGINT UNIQUE |
| especialidad       | VARCHAR(100)  |
| grado_academico    | VARCHAR(100)  |
| fecha_contratacion | DATE          |

---

## padres

Información específica de padres de familia.

| Campo      | Tipo          |
| ---------- | ------------- |
| id         | BIGINT        |
| usuario_id | BIGINT UNIQUE |
| ocupacion  | VARCHAR(100)  |
| parentesco | VARCHAR(50)   |

---

## estudiantes

Información académica de estudiantes.

| Campo             | Tipo                                 |
| ----------------- | ------------------------------------ |
| id                | BIGINT                               |
| usuario_id        | BIGINT UNIQUE                        |
| codigo_estudiante | VARCHAR(50) UNIQUE                   |
| dni               | VARCHAR(20)                          |
| fecha_nacimiento  | DATE                                 |
| genero            | ENUM('M','F','Otro')                 |
| direccion         | VARCHAR(255)                         |
| padre_id          | BIGINT                               |
| estado            | ENUM('activo','retirado','egresado') |

---

## grados

Niveles o grados académicos.

| Campo          | Tipo                                               |
| -------------- | -------------------------------------------------- |
| id             | BIGINT                                             |
| institucion_id | BIGINT                                             |
| nombre         | VARCHAR(50)                                        |
| nivel          | ENUM('Inicial','Primaria','Secundaria','Superior') |

---

## secciones

Secciones por grado.

| Campo    | Tipo        |
| -------- | ----------- |
| id       | BIGINT      |
| grado_id | BIGINT      |
| nombre   | VARCHAR(20) |
| aula     | VARCHAR(50) |

---

## cursos

Cursos o asignaturas.

| Campo          | Tipo                      |
| -------------- | ------------------------- |
| id             | BIGINT                    |
| institucion_id | BIGINT                    |
| docente_id     | BIGINT                    |
| grado_id       | BIGINT                    |
| nombre         | VARCHAR(100)              |
| descripcion    | TEXT                      |
| estado         | ENUM('activo','inactivo') |

---

## matriculas

Relación entre estudiantes y cursos.

| Campo           | Tipo                                   |
| --------------- | -------------------------------------- |
| id              | BIGINT                                 |
| estudiante_id   | BIGINT                                 |
| curso_id        | BIGINT                                 |
| seccion_id      | BIGINT                                 |
| anio_escolar    | YEAR                                   |
| fecha_matricula | DATE                                   |
| estado          | ENUM('activo','retirado','finalizado') |

---

## asistencias

Registro de asistencia.

| Campo         | Tipo                                              |
| ------------- | ------------------------------------------------- |
| id            | BIGINT                                            |
| estudiante_id | BIGINT                                            |
| curso_id      | BIGINT                                            |
| fecha         | DATE                                              |
| estado        | ENUM('presente','tardanza','falta','justificado') |
| observacion   | TEXT                                              |

---

## tareas

Actividades asignadas por docentes.

| Campo             | Tipo                     |
| ----------------- | ------------------------ |
| id                | BIGINT                   |
| curso_id          | BIGINT                   |
| titulo            | VARCHAR(150)             |
| descripcion       | TEXT                     |
| archivo           | VARCHAR(255)             |
| fecha_publicacion | DATETIME                 |
| fecha_entrega     | DATETIME                 |
| estado            | ENUM('activo','cerrado') |

---

## entregas_tareas

Entrega de tareas realizadas por estudiantes.

| Campo         | Tipo         |
| ------------- | ------------ |
| id            | BIGINT       |
| tarea_id      | BIGINT       |
| estudiante_id | BIGINT       |
| archivo       | VARCHAR(255) |
| comentario    | TEXT         |
| fecha_entrega | DATETIME     |
| calificado    | BOOLEAN      |

---

## notas

Calificaciones de estudiantes.

| Campo          | Tipo         |
| -------------- | ------------ |
| id             | BIGINT       |
| estudiante_id  | BIGINT       |
| curso_id       | BIGINT       |
| tarea_id       | BIGINT       |
| nota           | DECIMAL(5,2) |
| observacion    | TEXT         |
| fecha_registro | DATETIME     |

---

## anuncios

Comunicados generales.

| Campo          | Tipo         |
| -------------- | ------------ |
| id             | BIGINT       |
| institucion_id | BIGINT       |
| usuario_id     | BIGINT       |
| titulo         | VARCHAR(150) |
| contenido      | TEXT         |
| publicado      | BOOLEAN      |

---

## notificaciones

Notificaciones del sistema.

| Campo       | Tipo                                      |
| ----------- | ----------------------------------------- |
| id          | BIGINT                                    |
| usuario_id  | BIGINT                                    |
| titulo      | VARCHAR(150)                              |
| mensaje     | TEXT                                      |
| tipo        | ENUM('sistema','whatsapp','sms','correo') |
| leido       | BOOLEAN                                   |
| fecha_envio | DATETIME                                  |

---

## configuraciones

Configuraciones generales del SaaS.

| Campo          | Tipo         |
| -------------- | ------------ |
| id             | BIGINT       |
| institucion_id | BIGINT       |
| clave          | VARCHAR(100) |
| valor          | TEXT         |

---

# Relaciones

## Relaciones principales

* roles 1:N usuarios
* instituciones 1:N usuarios
* usuarios 1:1 docentes
* usuarios 1:1 estudiantes
* usuarios 1:1 padres
* padres 1:N estudiantes
* instituciones 1:N grados
* grados 1:N secciones
* grados 1:N cursos
* docentes 1:N cursos
* estudiantes N:N cursos mediante matriculas
* cursos 1:N tareas
* tareas 1:N entregas_tareas
* estudiantes 1:N notas
* usuarios 1:N notificaciones
* instituciones 1:N anuncios

---

# Índices Recomendados

## Índices importantes

### usuarios

* INDEX(correo)
* INDEX(rol_id)
* INDEX(institucion_id)

### estudiantes

* INDEX(codigo_estudiante)
* INDEX(padre_id)

### matriculas

* INDEX(estudiante_id)
* INDEX(curso_id)
* INDEX(anio_escolar)

### asistencias

* INDEX(fecha)
* INDEX(estudiante_id)

### notas

* INDEX(estudiante_id)
* INDEX(curso_id)

---

# Recomendaciones Técnicas

## Seguridad

* Contraseñas cifradas con bcrypt
* Uso de Laravel Sanctum o JWT
* Validación de roles y permisos
* Soft Deletes para evitar pérdida de información

## Escalabilidad

* Arquitectura multiinstitución
* Preparado para almacenamiento en la nube
* Compatible con colas y jobs de Laravel
* Caché con Redis opcional

## Integraciones Futuras

* API de WhatsApp
* Envío de SMS
* Correos automáticos
* Generación de PDF
* Exportación Excel
* Aplicación móvil

---

# Posibles Módulos del Sistema

1. Autenticación y usuarios
2. Gestión académica
3. Matrículas
4. Asistencia
5. Tareas virtuales
6. Calificaciones
7. Comunicación con padres
8. Reportes y estadísticas
9. Notificaciones WhatsApp/SMS
10. Configuración institucional

---

# Tecnologías Compatibles

## Backend

* Laravel 12
* PHP 8+
* MySQL

## Frontend

* Blade o Vue.js
* Tailwind CSS
* PWA

## Infraestructura

* Docker
* Nginx
* AWS / DigitalOcean

---

# Observaciones

El modelo está diseñado para:

* Colegios públicos
* Colegios privados
* Instituciones rurales
* Institutos
* Escuelas con baja conectividad

El sistema puede funcionar como:

* SaaS multiinstitución
* Sistema individual por colegio
* Plataforma educativa tipo Moodle simplificada
