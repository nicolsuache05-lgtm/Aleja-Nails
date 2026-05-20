# Vista: Gestión de Reservas (Admin)

**Archivo:** `views/admin/reservas.php`  
**Ruta de acceso:** `?action=listarReservas`  
**Rol requerido:** Administrador  
**Controlador:** `AdminUsuarioController::listarReservas()`

---

## ¿Qué hace esta vista?

Permite al administrador ver todas las reservas del sistema y cambiar su estado manualmente (por ejemplo, marcar una cita como completada o cancelada).

---

## Datos que muestra

| Columna | Descripción |
|---------|-------------|
| `#` | ID único de la reserva |
| `Cliente` | Nombre del cliente que agendó |
| `Servicios` | Lista de servicios incluidos en la reserva |
| `Total` | Suma de todos los servicios en COP |
| `Fecha` | Fecha de la cita |
| `Hora` | Hora de la cita |
| `Estado` | Badge con el estado actual |
| `Cambiar estado` | Dropdown + botón Guardar |

---

## Lógica de servicios múltiples

```
Si la reserva tiene más de 1 servicio:
    → Muestra lista <ul> con cada nombre de servicio

Si tiene solo 1 servicio:
    → Muestra el nombre directamente como texto
```

---

## Cambiar estado de una reserva

1. El admin selecciona el nuevo estado en el `<select>`
2. Hace clic en **"Guardar"**
3. Se envía `POST` a `?action=actualizarReserva`
4. El controlador valida el estado y ejecuta `UPDATE reserva SET estado = ?`

### Estados disponibles

| Estado | Descripción |
|--------|-------------|
| `pendiente` | Reserva creada, sin pago |
| `confirmada` | Pago registrado |
| `en_curso` | Cita siendo atendida |
| `completada` | Servicio finalizado |
| `cancelada` | Reserva cancelada |

---

## Flujo de datos

```
AdminUsuarioController::listarReservas()
    └── Reserva::obtenerTodas()
            ├── SELECT reserva.*, cliente.nombre, empleados.nombre
            │   FROM reserva INNER JOIN cliente LEFT JOIN empleados
            │
            └── Para cada reserva:
                    └── SELECT nombre_servicio, precio
                        FROM detalle_servicio INNER JOIN servicio
                        WHERE id_reserva = ?
                                └── Calcula total = SUM(precio)
                                        └── views/admin/reservas.php
```

---

## Tablas de base de datos involucradas

| Tabla | Uso |
|-------|-----|
| `reserva` | Datos principales de la cita |
| `cliente` | Nombre del cliente |
| `empleados` | Nombre del empleado asignado (puede ser NULL) |
| `detalle_servicio` | Servicios incluidos y sus precios |
| `servicio` | Nombre de cada servicio |
