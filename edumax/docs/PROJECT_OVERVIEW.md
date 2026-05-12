# EduMax

EduMax es una plataforma SaaS de gestión educativa orientada a colegios, institutos y centros educativos públicos y privados.

El sistema busca digitalizar procesos académicos y administrativos mediante una aplicación web moderna compatible con dispositivos móviles y computadoras.

---

# Objetivo

Crear un sistema:
- simple,
- moderno,
- accesible,
- responsive,
- escalable,
- PWA,
- usable en zonas con internet limitado.

---

# Stack Tecnológico

## Frontend

- Vue 3
- TypeScript
- TailwindCSS
- Vue Router
- Pinia
- Axios
- PWA

## Backend

- Laravel 13 API
- Sanctum
- Spatie Permission

## Database

- MySQL

---

# Arquitectura

Frontend y backend separados.

frontend/
backend/

Comunicación mediante REST API.

Vue → Axios → Laravel API → MySQL

---

# Roles

- Admin
- Director
- Teacher
- Student
- Parent

---

# Funcionalidades

- Login
- Gestión estudiantes
- Gestión docentes
- Cursos
- Asistencia
- Notas
- Reportes
- Notificaciones
- Dashboard
- Roles y permisos

---

# Objetivo MVP

Primera versión:
- Login
- Dashboard
- CRUD estudiantes
- Asistencia
- Notas
- Responsive móvil
- PWA instalable
