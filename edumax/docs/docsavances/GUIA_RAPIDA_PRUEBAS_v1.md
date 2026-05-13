# 🚀 GUÍA RÁPIDA - Prueba de Autenticación

**Versión:** 1.0.0  
**Actualizado:** 13/05/2026  

---

## ⚡ Quick Start

### Backend

```bash
# Terminal 1: Navegar al backend
cd backend

# Iniciar servidor
php artisan serve

# API disponible en: http://127.0.0.1:8000/api
```

### Frontend

```bash
# Terminal 2: Navegar al frontend
cd frontend

# Instalar dependencias (primera vez)
npm install

# Iniciar servidor de desarrollo
npm run dev

# Frontend disponible en: http://127.0.0.1:5173
```

---

## 🧪 Pruebas Manuales

### 1. Test Básico (API activa)

```bash
curl http://127.0.0.1:8000/api/test

# Respuesta esperada:
{
  "success": true,
  "message": "Laravel API funcionando"
}
```

### 2. Login (Obtener Token)

```bash
curl -X POST http://127.0.0.1:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "admin@example.com",
    "password": "password123"
  }'

# Respuesta esperada (Status 200):
{
  "success": true,
  "message": "Login exitoso.",
  "data": {
    "token": "1|abcdef123456...",
    "user": {
      "id": 1,
      "nombres": "Admin",
      "apellidos": "User",
      "email": "admin@example.com",
      "roles": []
    }
  }
}
```

### 3. Acceder a Ruta Protegida

```bash
# Usar el token obtenido del login
TOKEN="1|abcdef123456..."

curl http://127.0.0.1:8000/api/protected-test \
  -H "Authorization: Bearer $TOKEN"

# Respuesta esperada (Status 200):
{
  "success": true,
  "message": "Ruta protegida accedida correctamente",
  "user": { /* datos del usuario */ }
}
```

### 4. Obtener Usuario Actual

```bash
TOKEN="1|abcdef123456..."

curl http://127.0.0.1:8000/api/auth/me \
  -H "Authorization: Bearer $TOKEN"

# Respuesta esperada (Status 200):
{
  "success": true,
  "data": { /* datos del usuario */ }
}
```

### 5. Renovar Token

```bash
TOKEN="1|abcdef123456..."

curl -X POST http://127.0.0.1:8000/api/auth/refresh \
  -H "Authorization: Bearer $TOKEN"

# Respuesta esperada (Status 200):
{
  "success": true,
  "message": "Token renovado exitosamente.",
  "data": {
    "token": "1|newtokenvalue..."
  }
}
```

### 6. Logout (Revocar Tokens)

```bash
TOKEN="1|abcdef123456..."

curl -X POST http://127.0.0.1:8000/api/auth/logout \
  -H "Authorization: Bearer $TOKEN"

# Respuesta esperada (Status 200):
{
  "success": true,
  "message": "Logout exitoso."
}

# Intenta acceder a ruta protegida nuevamente:
# Resultado: Status 401 Unauthorized
```

---

## 🖥️ Pruebas en Frontend

### 1. Abrir DevTools Console

```javascript
// En la consola del navegador (F12 → Console)

// Ver si hay token
console.log(localStorage.getItem('auth_token'))

// Ver datos del usuario
console.log(JSON.parse(localStorage.getItem('user_data')))

// Verificar store
import { useAuthStore } from '@/stores/authStore'
const auth = useAuthStore()
console.log(auth.estaAutenticado)
console.log(auth.usuario)
```

### 2. Probar Login desde Frontend

```javascript
// Abrir http://127.0.0.1:5173/login en el navegador
// Llenar formulario con:
// Email: admin@example.com (o crear primero un usuario)
// Password: password123

// Si login es exitoso:
// 1. Token se guardará en localStorage
// 2. Usuario se redirigirá a /dashboard
// 3. Ver estado en DevTools: localStorage y Pinia store
```

### 3. Verificar Peticiones HTTP

```javascript
// En DevTools → Network
// Hacer click en botón de login
// Ver petición POST /api/auth/login
// Verificar:
// - Status: 200
// - Response contiene token
// - Headers: Authorization: Bearer {token}
```

### 4. Probar Rutas Protegidas

```javascript
// En DevTools → Console
import api from '@/api/axios'

// Con token (debe funcionar)
api.get('/protected-test').then(res => console.log(res.data))

// Sin token (debe ser 401)
localStorage.removeItem('auth_token')
api.get('/protected-test').catch(err => console.log(err.response.status))
```

---

## 🔍 Troubleshooting

### Error: CORS Policy - No Access-Control-Allow-Origin

**Causa:** Frontend no está en la lista permitida de CORS

**Solución:**
```php
// backend/config/cors.php
'allowed_origins' => [
    'http://localhost:5173',  // ← Agregar tu puerto
],
```

**Luego:** Reiniciar backend

### Error: 401 Unauthorized en Ruta Protegida

**Causa:** Token no enviado o inválido

**Verificar:**
1. ¿Token en localStorage? `localStorage.getItem('auth_token')`
2. ¿Token en Header? DevTools → Network → Headers → Authorization
3. ¿Token válido? Copiar y probar con curl

**Solución:** Hacer login nuevamente

### Error: Token Not Found en Login

**Causa:** User no existe o contraseña incorrecta

**Verificar:**
1. ¿Existe la institución? `SELECT * FROM instituciones;`
2. ¿Existe el usuario? `SELECT * FROM users WHERE email='...';`
3. ¿Contraseña correcta? Probar reset en base de datos

### Error: "The given data was invalid" en Register

**Causa:** Validación fallida

**Verificar:** Todos los campos requeridos:
```json
{
  "institucion_id": 1,    // ← Requerido
  "nombres": "Juan",       // ← Requerido
  "apellidos": "Pérez",    // ← Requerido
  "email": "juan@test.com",// ← Único, requerido
  "password": "password123",// ← Mínimo 8 caracteres
  "password_confirmation": "password123"  // ← Debe coincidir
}
```

---

## 📊 Verificación Final

Antes de considerar terminado, verificar:

- [ ] Backend levantado en http://127.0.0.1:8000
- [ ] Frontend levantado en http://127.0.0.1:5173
- [ ] Endpoint `/api/test` responde
- [ ] Login con usuario válido funciona
- [ ] Token se guarda en localStorage
- [ ] Ruta protegida accesible con token
- [ ] Ruta protegida rechaza sin token (401)
- [ ] Token se renueva correctamente
- [ ] Logout revoca tokens
- [ ] Redirect a login si no autenticado
- [ ] Redirect a dashboard si ya autenticado

---

## 📝 Datos de Prueba

Necesitas crear un usuario en la base de datos:

```sql
-- 1. Crear institución (si no existe)
INSERT INTO instituciones (nombre, codigo, created_at, updated_at)
VALUES ('Institución Test', 'TEST', NOW(), NOW());

-- 2. Crear usuario
INSERT INTO users (
  institucion_id,
  nombres,
  apellidos,
  email,
  password,
  estado,
  created_at,
  updated_at
) VALUES (
  1,
  'Admin',
  'Test',
  'admin@example.com',
  'password_hash',  -- Usar: Hash::make('password123')
  'activo',
  NOW(),
  NOW()
);

-- Alternativa: Usar Tinker
php artisan tinker
>>> $i = App\Models\Institucion::create(['nombre' => 'Test', 'codigo' => 'TEST']);
>>> App\Models\User::create([
  'institucion_id' => $i->id,
  'nombres' => 'Admin',
  'apellidos' => 'User',
  'email' => 'admin@example.com',
  'password' => Hash::make('password123'),
  'estado' => 'activo'
]);
```

---

## 🎯 Checklist Final

```
✅ Backend ejecutándose
✅ Frontend ejecutándose
✅ CORS configurado
✅ Usuario de prueba creado
✅ Login funciona
✅ Token se genera
✅ Token se almacena
✅ Rutas protegidas funcionan
✅ Logout revoca tokens
✅ Redirecciones correctas
✅ Documentación completada
```

---

**¡Sistema de autenticación completamente funcional! 🎉**

