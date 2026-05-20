# Diccionario de Datos — Aleja-Nails

**Base de datos:** `aleja-nails`  
**Motor:** MariaDB 10.4  
**Charset:** utf8mb4 / utf8mb4_general_ci  

---

## Tabla: `administrador`

Almacena los usuarios con privilegios de administración del sistema.

| Columna | Tipo | Nulo | PK | Descripción |
|---------|------|------|----|-------------|
| `id_administrador` | INT(11) | NO | ✅ AUTO_INCREMENT | Identificador único del administrador |
| `nombre` | VARCHAR(30) | NO | — | Nombre completo del administrador |
| `usuario` | VARCHAR(20) | NO | — | Nombre de usuario para login |
| `contraseña` | VARCHAR(15) | NO | — | Contraseña en texto plano *(pendiente migrar a hash)* |
| `correo` | VARCHAR(100) | SÍ | — | Correo electrónico *(columna agregada dinámicamente)* |

**Relaciones:** Referenciada por `servicio.id_administrador` y `empleados.id_administrador`

---

## Tabla: `cliente`

Almacena los clientes registrados que pueden agendar citas.

| Columna | Tipo | Nulo | PK | Descripción |
|---------|------|------|----|-------------|
| `id_cliente` | INT(11) | NO | ✅ AUTO_INCREMENT | Identificador único del cliente |
| `nombre` | VARCHAR(30) | NO | — | Nombre completo del cliente |
| `telefono` | VARCHAR(15) | NO | — | Número de teléfono de contacto |
| `correo` | VARCHAR(25) | SÍ | — | Correo electrónico (usado como usuario de login) |
| `password` | VARCHAR(255) | NO | — | Contraseña hasheada con `password_hash()` *(columna agregada dinámicamente)* |
| `activo` | TINYINT(1) | NO | — | Estado de la cuenta: 1=activo, 0=inactivo *(columna agregada dinámicamente)* |

**Relaciones:** Referenciada por `reserva.id_cliente`

---

## Tabla: `servicio`

Catálogo de servicios ofrecidos por el salón.

| Columna | Tipo | Nulo | PK | Descripción |
|---------|------|------|----|-------------|
| `id_servicio` | INT(11) | NO | ✅ AUTO_INCREMENT | Identificador único del servicio |
| `nombre_servicio` | VARCHAR(30) | NO | — | Nombre del servicio |
| `descripcion` | VARCHAR(30) | SÍ | — | Descripción breve del servicio |
| `precio` | DECIMAL(10,0) | SÍ | — | Precio en pesos colombianos (COP) |
| `categoria` | VARCHAR(50) | NO | — | Categoría: Manicure / Pedicure / Capilar / Otros *(columna agregada dinámicamente)* |
| `id_administrador` | INT(11) | NO | — | FK → administrador.id_administrador |

**Relaciones:** Referenciada por `reserva.id_servicio` y `detalle_servicio.id_servicio`

---

## Tabla: `empleados`

Personal del salón (para asignación futura a reservas).

| Columna | Tipo | Nulo | PK | Descripción |
|---------|------|------|----|-------------|
| `id_empleados` | INT(11) | NO | ✅ AUTO_INCREMENT | Identificador único del empleado |
| `nombre` | VARCHAR(30) | NO | — | Nombre completo del empleado |
| `telefono` | VARCHAR(15) | SÍ | — | Teléfono de contacto |
| `id_administrador` | INT(11) | NO | — | FK → administrador.id_administrador |

**Relaciones:** Referenciada por `reserva.id_empleados`

---

## Tabla: `reserva`

Registro de citas agendadas por los clientes.

| Columna | Tipo | Nulo | PK | Descripción |
|---------|------|------|----|-------------|
| `id_reserva` | INT(11) | NO | ✅ AUTO_INCREMENT | Identificador único de la reserva |
| `fecha` | VARCHAR(20) | NO | — | Fecha de la cita en formato YYYY-MM-DD |
| `hora` | VARCHAR(10) | NO | — | Hora de la cita en formato HH:MM |
| `estado` | VARCHAR(25) | NO | — | Estado: pendiente / confirmada / en_curso / completada / cancelada |
| `id_cliente` | INT(11) | NO | — | FK → cliente.id_cliente |
| `id_servicio` | INT(11) | NO | — | FK → servicio.id_servicio *(servicio principal, para compatibilidad)* |
| `id_empleados` | INT(11) | SÍ | — | FK → empleados.id_empleados *(puede ser NULL)* |

**Relaciones:**
- Referencia a `cliente`, `servicio`, `empleados`
- Referenciada por `detalle_servicio.id_reserva` y `pago.id_reserva`

**Nota:** El campo `id_servicio` mantiene el primer servicio seleccionado por compatibilidad con la FK. Los servicios reales de la reserva se almacenan en `detalle_servicio`.

---

## Tabla: `detalle_servicio`

Relación N:N entre reservas y servicios. Permite múltiples servicios por reserva.

| Columna | Tipo | Nulo | PK | Descripción |
|---------|------|------|----|-------------|
| `id_detalle_servicio` | INT(11) | NO | ✅ AUTO_INCREMENT | Identificador único del detalle |
| `cantidad` | VARCHAR(20) | NO | — | Cantidad del servicio (siempre "1") |
| `precio_unitario` | DECIMAL(10,0) | SÍ | — | Precio del servicio al momento de la reserva |
| `subtotal` | DECIMAL(10,0) | SÍ | — | Subtotal (precio_unitario × cantidad) |
| `id_reserva` | INT(11) | NO | — | FK → reserva.id_reserva |
| `id_servicio` | INT(11) | NO | — | FK → servicio.id_servicio |

**Relaciones:** Referencia a `reserva` y `servicio`

**Uso:** Para calcular el total de una reserva se suma `SUM(subtotal)` filtrando por `id_reserva`.

---

## Tabla: `pago`

Registro de pagos realizados por los clientes.

| Columna | Tipo | Nulo | PK | Descripción |
|---------|------|------|----|-------------|
| `id_pago` | INT(11) | NO | ✅ AUTO_INCREMENT | Identificador único del pago |
| `fecha_pago` | VARCHAR(20) | NO | — | Fecha en que se registró el pago (YYYY-MM-DD) |
| `metodo_pago` | VARCHAR(25) | NO | — | Método: efectivo / transferencia |
| `valor_pagado` | DECIMAL(10,0) | SÍ | — | Monto total pagado en COP |
| `id_reserva` | INT(11) | NO | — | FK → reserva.id_reserva |

**Relaciones:** Referencia a `reserva`

---

## Relaciones entre tablas

```
administrador ──< servicio
administrador ──< empleados

cliente ──< reserva >── servicio
                │
                ├──< detalle_servicio >── servicio
                │
                └──< pago
```

**Cardinalidades:**
- Un administrador puede tener muchos servicios y empleados
- Un cliente puede tener muchas reservas
- Una reserva pertenece a un cliente
- Una reserva puede tener muchos detalles de servicio (N:N con servicio)
- Una reserva puede tener un pago

---

## Valores de dominio

### `reserva.estado`
| Valor | Descripción |
|-------|-------------|
| `pendiente` | Reserva creada, sin pago confirmado |
| `confirmada` | Pago registrado en el sistema |
| `en_curso` | La cita está siendo atendida |
| `completada` | El servicio fue finalizado |
| `cancelada` | La reserva fue cancelada |

### `pago.metodo_pago`
| Valor | Descripción |
|-------|-------------|
| `efectivo` | Pago en efectivo en el salón |
| `transferencia` | Pago por Nequi, Daviplata o PSE |

### `servicio.categoria`
| Valor | Descripción |
|-------|-------------|
| `Manicure` | Servicios de uñas de manos |
| `Pedicure` | Servicios de uñas de pies |
| `Capilar` | Tratamientos de cabello |
| `Otros` | Servicios adicionales |

### `cliente.activo`
| Valor | Descripción |
|-------|-------------|
| `1` | Cuenta activa, puede iniciar sesión |
| `0` | Cuenta desactivada por el administrador |

---

*Diccionario de Datos — Aleja-Nails · Proyecto Formativo SENA · 2026*
