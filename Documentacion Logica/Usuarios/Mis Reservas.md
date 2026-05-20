# Vista: Mis Reservas

**Archivo:** `views/usuarios/mis-reservas.php`  
**Ruta de acceso:** `?action=misReservas`  
**Rol requerido:** Cliente autenticado  
**Controlador:** `UsuarioController::misReservas()`

---

## ¿Qué hace esta vista?

Muestra al cliente el historial completo de todas sus reservas con sus servicios, estado actual y las acciones disponibles según el estado.

---

## Datos que muestra

| Columna | Descripción |
|---------|-------------|
| `#` | ID de la reserva |
| `Servicios` | Lista de servicios con precio individual (si hay varios) |
| `Fecha` | Fecha de la cita |
| `Hora` | Hora de la cita |
| `Estado` | Badge de color según estado |
| `Total` | Suma de todos los servicios en COP |
| `Acciones` | Botones disponibles según el estado |

---

## Lógica de visualización de servicios

```
Si la reserva tiene más de 1 servicio:
    → Muestra lista <ul> con nombre y precio de cada uno

Si tiene solo 1 servicio:
    → Muestra el nombre directamente como texto
```

---

## Acciones por estado

| Estado | Acciones disponibles |
|--------|---------------------|
| `pendiente` (sin pago) | 💳 **Pagar** + **Cancelar** |
| `pendiente` (ya pagó) | ✅ Pagado + **Cancelar** |
| `confirmada` | ✅ Confirmada |
| `en_curso` | — |
| `completada` | — |
| `cancelada` | — |

---

## Botón Pagar

- Solo aparece si `$r['estado'] === 'pendiente'` Y `$r['pagado'] === false`
- Redirige a `?action=pagar&id={id_reserva}`

## Botón Cancelar

- Solo aparece si `$r['estado'] === 'pendiente'`
- Pide confirmación con `confirm()`
- Redirige a `?action=cancelarReserva&id={id_reserva}`

---

## Flujo de datos

```
UsuarioController::misReservas()
    └── Reserva::obtenerPorCliente($_SESSION['usuario_id'])
            ├── SELECT reserva.* WHERE id_cliente = ?
            │
            ├── Para cada reserva:
            │       SELECT nombre_servicio, precio
            │       FROM detalle_servicio INNER JOIN servicio
            │       WHERE id_reserva = ?
            │
            ├── Si no hay detalles → fallback a id_servicio directo
            │
            └── Verifica si tiene pago:
                    SELECT id_pago FROM pago WHERE id_reserva = ?
                            └── $r['pagado'] = true/false
                                    └── views/usuarios/mis-reservas.php
```

---

## Tablas de base de datos involucradas

| Tabla | Uso |
|-------|-----|
| `reserva` | Datos principales de cada cita |
| `detalle_servicio` | Servicios incluidos y precios |
| `servicio` | Nombre de cada servicio |
| `pago` | Verificar si la reserva ya fue pagada |
