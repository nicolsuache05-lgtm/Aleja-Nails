# Vista: Dashboard Cliente

**Archivo:** `views/dashboard/cliente.php`  
**Ruta de acceso:** `?action=dashboard`  
**Rol requerido:** Cliente autenticado  
**Controlador:** `UsuarioController::dashboard()`

---

## ¿Qué hace esta vista?

Es la pantalla principal del cliente después de iniciar sesión. Muestra un resumen de sus citas, accesos rápidos y el catálogo de servicios disponibles.

---

## Secciones de la vista

### 1. Saludo personalizado

Muestra el nombre del cliente tomado de `$_SESSION['nombre']`.

---

### 2. Tarjetas de estadísticas

Calculadas en PHP a partir del array `$reservas`:

| Tarjeta | Cálculo |
|---------|---------|
| Mis reservas | `count($reservas)` |
| Pendientes | Filtra reservas donde `estado === 'pendiente'` |
| Completadas | Filtra reservas donde `estado === 'completada'` |

---

### 3. Acciones rápidas

| Botón | Redirige a |
|-------|-----------|
| 📅 Agendar nueva cita | `?action=agendarCita` |
| 📋 Ver mis reservas | `?action=misReservas` |

---

### 4. Últimas 5 reservas

Tabla con las 5 reservas más recientes del cliente:

| Columna | Descripción |
|---------|-------------|
| Servicio | Nombre del servicio principal |
| Fecha | Fecha de la cita |
| Hora | Hora de la cita |
| Estado | Badge de color según estado |
| Precio | Total de la reserva |
| Acción | Botón "Cancelar" si está pendiente |

---

### 5. Catálogo de servicios

Tarjetas con todos los servicios disponibles mostrando nombre, descripción y precio.

---

## Flujo de datos

```
UsuarioController::dashboard()
    ├── Reserva::obtenerPorCliente($_SESSION['usuario_id'])
    │       └── SELECT + detalle_servicio por cliente
    │               └── $reservas
    │
    └── Servicio::obtenerTodos()
            └── SELECT * FROM servicio
                    └── $servicios
                            └── views/dashboard/cliente.php
```

---

## Tablas de base de datos involucradas

| Tabla | Uso |
|-------|-----|
| `reserva` | Reservas del cliente |
| `detalle_servicio` | Servicios de cada reserva |
| `servicio` | Catálogo completo de servicios |
