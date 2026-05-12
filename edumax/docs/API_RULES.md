# API Rules

## Tipo

REST API

---

# Formato

Todas las respuestas:
JSON

---

# Base URL

/api

---

# Reglas

- No usar Blade
- No retornar vistas HTML
- Usar Controllers
- Usar FormRequest
- Usar Resources
- Usar Services
- Controllers delgados

---

# Status Codes

200 OK
201 Created
401 Unauthorized
403 Forbidden
404 Not Found
422 Validation Error
500 Server Error

---

# Estructura JSON

## Success

{
  "success": true,
  "data": {}
}

## Error

{
  "success": false,
  "message": ""
}

---

# Auth

Laravel Sanctum.

---

# Versionado

Preparado para:

/api/v1/
