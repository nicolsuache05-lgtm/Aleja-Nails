# Especificación Técnica — Aleja-Nails

**Documento:** Especificación Técnica del Sistema  
**Versión:** 1.0  
**Fecha:** Mayo 2026  

---

## 1. Casos de Uso

### CU-01: Registrar Cliente

| Campo | Detalle |
|-------|---------|
| **Actor** | Visitante |
| **Precondición** | No tener cuenta registrada |
| **Flujo principal** | 1. Accede a la página de inicio → 2. Clic en "Registrarse" → 3. Completa nombre, teléfono, correo, contraseña → 4. Sistema valida datos → 5. Crea cuenta → 6. Redirige a login |
| **Flujo alternativo** | Correo ya registrado → muestra error |
| **Postcondición** | Cliente creado en BD con contraseña hasheada |

---

### CU-02: Iniciar Sesión

| Campo | Detalle |
|-------|---------|
| **Actor** | Cliente / Administrador |
| **Precondición** | Tener cuenta activa |
| **Flujo principal** | 1. Ingresar correo y contraseña → 2. Sistema busca en `administrador` → 3. Si no, busca en `cliente` → 4. Verifica contraseña → 5. Crea sesión → 6. Redirige según rol |
| **Flujo alternativo** | Credenciales incorrectas → flash error. Cuenta inactiva → flash error |
| **Postcondición** | Sesión PHP activa con `usuario_id`, `nombre`, `rol` |

---

### CU-03: Agendar Cita con Múltiples Servicios

| Campo | Detalle |
|-------|---------|
| **Actor** | Cliente autenticado |
| **Precondición** | Sesión activa con rol `cliente` |
| **Flujo principal** | 1. Accede a "Agendar cita" → 2. Navega por categorías (tabs) → 3. Selecciona uno o varios servicios → 4. Sistema muestra total en tiempo real → 5. Elige fecha y hora → 6. Confirma → 7. Sistema crea reserva + detalles → 8. Redirige a pago |
| **Flujo alternativo** | Sin servicios seleccionados → mensaje de error. Fecha pasada → error. Horario ocupado → error |
| **Postcondición** | Registro en `reserva` + registros en `detalle_servicio` |

---

### CU-04: Realizar Pago

| Campo | Detalle |
|-------|---------|
| **Actor** | Cliente autenticado |
| **Precondición** | Reserva en estado `pendiente` perteneciente al cliente |
| **Flujo principal** | 1. Accede a pantalla de pago → 2. Ve resumen de servicios y total → 3. Selecciona método (efectivo / transferencia) → 4. Si transferencia: ve datos bancarios con botón copiar → 5. Confirma pago → 6. Sistema registra pago y actualiza estado a `confirmada` |
| **Flujo alternativo** | Sin método seleccionado → mensaje de error |
| **Postcondición** | Registro en `pago`, estado de reserva = `confirmada` |

---

### CU-05: Cancelar Reserva

| Campo | Detalle |
|-------|---------|
| **Actor** | Cliente autenticado |
| **Precondición** | Reserva en estado `pendiente` y perteneciente al cliente |
| **Flujo principal** | 1. Ir a "Mis Reservas" → 2. Clic en "Cancelar" → 3. Confirmar en diálogo → 4. Sistema actualiza estado a `cancelada` |
| **Postcondición** | Estado de reserva = `cancelada` |

---

### CU-06: Gestionar Servicios (Admin)

| Campo | Detalle |
|-------|---------|
| **Actor** | Administrador |
| **Flujo editar** | 1. Ir a "Servicios" → 2. Modificar descripción o precio en el campo inline → 3. Clic "Guardar" → 4. Sistema actualiza en BD |
| **Flujo eliminar** | 1. Clic "Eliminar" → 2. Confirmar → 3. Sistema elimina de BD |
| **Postcondición** | Servicio actualizado o eliminado |

---

### CU-07: Gestionar Estado de Reservas (Admin)

| Campo | Detalle |
|-------|---------|
| **Actor** | Administrador |
| **Flujo** | 1. Ir a "Reservas" → 2. Seleccionar nuevo estado en dropdown → 3. Clic "Guardar" → 4. Sistema actualiza estado |
| **Estados válidos** | pendiente, confirmada, en_curso, completada, cancelada |

---

### CU-08: Usar el Chatbot

| Campo | Detalle |
|-------|---------|
| **Actor** | Cualquier usuario (logueado o no) |
| **Flujo** | 1. Clic en botón flotante 💬 → 2. Se abre ventana de chat → 3. Escribe pregunta o usa sugerencias → 4. Sistema responde en menos de 1 segundo |
| **Disponibilidad** | Todas las páginas del sistema, incluyendo la página de inicio pública |

---

## 2. Diagrama de Flujo del Sistema

```
[Visitante]
    │
    ├─── Registro ──────────────────────────────► [Cliente en BD]
    │
    └─── Login
              │
              ├─── Admin ──► Panel Admin
              │                   ├── Gestionar Reservas
              │                   ├── Gestionar Clientes
              │                   ├── Gestionar Servicios
              │                   └── Ver Pagos
              │
              └─── Cliente ──► Dashboard
                                    ├── Agendar Cita
                                    │       ├── Seleccionar Servicios (múltiple)
                                    │       ├── Ver Total en Tiempo Real
                                    │       ├── Elegir Fecha/Hora
                                    │       └── Confirmar → Pago
                                    │                   ├── Efectivo
                                    │                   └── Transferencia (datos bancarios)
                                    │
                                    └── Mis Reservas
                                            ├── Ver historial
                                            ├── Pagar pendientes
                                            └── Cancelar pendientes
```

---

## 3. Modelo de Datos Detallado

### Tabla `reserva`

| Columna | Tipo | Descripción |
|---------|------|-------------|
| `id_reserva` | INT PK AUTO_INCREMENT | Identificador único |
| `fecha` | VARCHAR(20) | Fecha de la cita (YYYY-MM-DD) |
| `hora` | VARCHAR(10) | Hora de la cita (HH:MM) |
| `estado` | VARCHAR(25) | Estado actual de la reserva |
| `id_cliente` | INT FK | Cliente que agendó |
| `id_servicio` | INT FK | Servicio principal (compatibilidad) |
| `id_empleados` | INT FK NULL | Empleado asignado (opcional) |

### Tabla `detalle_servicio`

| Columna | Tipo | Descripción |
|---------|------|-------------|
| `id_detalle_servicio` | INT PK AUTO_INCREMENT | Identificador único |
| `cantidad` | VARCHAR(20) | Cantidad (siempre 1) |
| `precio_unitario` | DECIMAL(10,0) | Precio del servicio al momento de reservar |
| `subtotal` | DECIMAL(10,0) | Igual a precio_unitario (cantidad × precio) |
| `id_reserva` | INT FK | Reserva a la que pertenece |
| `id_servicio` | INT FK | Servicio incluido |

### Tabla `pago`

| Columna | Tipo | Descripción |
|---------|------|-------------|
| `id_pago` | INT PK AUTO_INCREMENT | Identificador único |
| `fecha_pago` | VARCHAR(20) | Fecha en que se registró el pago |
| `metodo_pago` | VARCHAR(25) | efectivo / transferencia |
| `valor_pagado` | DECIMAL(10,0) | Monto total pagado |
| `id_reserva` | INT FK | Reserva asociada |

---

## 4. API Interna — Chatbot

**Endpoint:** `POST /Mi-proyecto-formativo/public/index.php?action=chatbot`

**Request:**
```json
{
  "mensaje": "¿Cuáles son los servicios de manicure?"
}
```

**Response:**
```json
{
  "respuesta": "💅 <b>Servicios de Manicure:</b>\n\n• <b>Esmaltado básico</b>...\n"
}
```

**Códigos de respuesta:**
- `200 OK` — Respuesta generada correctamente
- La respuesta puede contener HTML básico (`<b>`, `<a>`, `<small>`)

---

## 5. Estructura de Sesión PHP

```php
// Después del login como cliente:
$_SESSION = [
    'usuario_id' => 5,           // id_cliente
    'nombre'     => 'María',     // nombre del cliente
    'correo'     => 'maria@...',
    'rol'        => 'cliente',
];

// Después del login como admin:
$_SESSION = [
    'usuario_id' => 1,           // id_administrador
    'nombre'     => 'Admin',
    'usuario'    => 'admin',
    'rol'        => 'admin',
];
```

---

## 6. Convenciones de Código

| Elemento | Convención |
|----------|------------|
| Clases | PascalCase (`AuthController`, `Reserva`) |
| Métodos | camelCase (`agendarCita`, `crearConDetalles`) |
| Variables PHP | camelCase (`$idReserva`, `$metodoActivo`) |
| Constantes | UPPER_SNAKE_CASE (`BASE`, `TEL_DISPLAY`) |
| Archivos de vista | kebab-case (`mis-reservas.php`, `agendar.php`) |
| Archivos de controlador | PascalCase (`UsuarioController.php`) |
| Columnas BD | snake_case (`id_cliente`, `nombre_servicio`) |

---

## 7. Dependencias Externas (CDN)

| Librería | Uso | URL |
|----------|-----|-----|
| Tailwind CSS | Estilos página de inicio | cdn.tailwindcss.com |
| Font Awesome 6 | Iconos | cdnjs.cloudflare.com |
| Google Fonts (Poppins) | Tipografía principal | fonts.googleapis.com |
| Google Fonts (Great Vibes) | Tipografía del logo | fonts.googleapis.com |

> El sistema funciona sin conexión a internet excepto por estas dependencias CDN. Para uso offline, descargar e incluir localmente.

---

## 8. Manejo de Errores

| Tipo | Manejo |
|------|--------|
| Errores de validación | `$_SESSION['flash_error']` → mostrado en la siguiente vista |
| Éxito de operación | `$_SESSION['flash_ok']` → mostrado en la siguiente vista |
| Errores de BD | `error_log()` + retorno `false` al controlador |
| Acceso no autorizado | `die("No autorizado")` o HTTP 403 |
| Transacciones fallidas | `rollBack()` + `error_log()` |

---

*Especificación Técnica — Aleja-Nails · Proyecto Formativo SENA · 2026*
