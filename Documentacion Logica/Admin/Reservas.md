# Vista: Gestión de Reservas

**Archivo:** `views/admin/reservas.php`  
**Acción:** `?action=listarReservas`  
**Rol:** Administrador  
**Controlador:** `AdminUsuarioController::listarReservas()`

---

## ¿Qué hace esta vista?

Muestra todas las reservas del sistema con sus servicios, totales y estado actual. El administrador puede cambiar manualmente el estado de cualquier reserva desde un menú desplegable.

---

## Estructura visual

```
┌─────────────────────────────────────────────────────────────────────────────────┐
│  📅 Reservas                                                                    │
├────┬──────────────────┬──────────────────────┬──────────┬────────┬──────┬───────┤
│ #  │ Cliente          │ Servicios            │ Total    │ Fecha  │ Hora │Estado │ Cambiar estado
├────┼──────────────────┼──────────────────────┼──────────┼────────┼──────┼───────┤
│ 8  │ 💅 María García  │ • Manicure gel       │ $125.000 │01/07/26│10:00 │🟡Pend.│ [dropdown] [Guardar]
│    │                  │ • Pedicure básica    │          │        │      │       │
│ 7  │ 💅 Ana López     │ Corte de cabello     │ $45.000  │30/06/26│14:30 │🌸Conf.│ [dropdown] [Guardar]
└────┴──────────────────┴──────────────────────┴──────────┴────────┴──────┴───────┘
```

---

## Columnas de la tabla

| Columna | Fuente | Descripción |
|---------|--------|-------------|
| `#` | `reserva.id_reserva` | ID en gris, prefijado con `#` |
| `Cliente` | `cliente.nombre` | Avatar 💅 + nombre en negrita |
| `Servicios` | `detalle_servicio` + `servicio` | Lista o texto según cantidad |
| `Total` | Suma de `detalle_servicio.precio_unitario` | En rosado negrita |
| `Fecha` | `reserva.fecha` | Formato dd/mm/YYYY |
| `Hora` | `reserva.hora` | Formato HH:MM |
| `Estado` | `reserva.estado` | Badge de color |
| `Cambiar estado` | — | `<select>` + botón Guardar |

---

## Lógica de visualización de servicios

```
Si count($r['servicios']) > 1:
    → Lista vertical:
        • Nombre servicio 1
        • Nombre servicio 2
        ...

Si count($r['servicios']) == 1:
    → Texto simple: "Nombre del servicio"
```

---

## Badges de estado

| Estado | Color fondo | Color texto | Punto |
|--------|------------|-------------|-------|
| `pendiente` | `#fff8e1` (amarillo) | `#795548` | 🟠 naranja |
| `confirmada` | `#fce4ef` (rosado) | `#c93060` | 🩷 rosa |
| `en_curso` | `#e3f2fd` (azul) | `#1565c0` | 🔵 azul |
| `completada` | `#e8f5e9` (verde) | `#2e7d32` | 🟢 verde |
| `cancelada` | `#f5f5f5` (gris) | `#616161` | ⚫ gris |

---

## Cambiar estado de una reserva

```
1. Admin selecciona nuevo estado en el <select>
2. Clic en "Guardar"
3. POST ?action=actualizarReserva
        │
        ├── Valida $id > 0
        ├── Valida $estado ∈ ['pendiente','confirmada','en_curso','completada','cancelada']
        └── Reserva::actualizarEstado($id, $estado)
                └── UPDATE reserva SET estado = ? WHERE id_reserva = ?
                        └── Redirige a ?action=listarReservas
```

---

## Flujo completo de datos (GET)

```
AdminUsuarioController::listarReservas()
        │
        └── Reserva::obtenerTodas()
                │
                ├── SELECT reserva.*, cliente.nombre, empleados.nombre
                │   FROM reserva
                │   INNER JOIN cliente   ON reserva.id_cliente   = cliente.id_cliente
                │   LEFT  JOIN empleados ON reserva.id_empleados = empleados.id_empleados
                │   ORDER BY fecha DESC, hora DESC
                │
                └── Para cada reserva:
                        └── SELECT ds.id_reserva, s.nombre_servicio, ds.precio_unitario
                            FROM detalle_servicio ds
                            INNER JOIN servicio s ON ds.id_servicio = s.id_servicio
                            WHERE ds.id_reserva = ?
                                    │
                                    ├── Si hay detalles → $r['servicios'], $r['precio'] = SUM
                                    └── Si no hay → fallback al id_servicio de reserva
                                            └── Array $reservas → views/admin/reservas.php
```

---

## Tablas involucradas

| Tabla | Uso |
|-------|-----|
| `reserva` | Datos principales + campo estado a actualizar |
| `cliente` | Nombre del cliente |
| `empleados` | Nombre del empleado asignado (LEFT JOIN, puede ser NULL) |
| `detalle_servicio` | Servicios de cada reserva y precios |
| `servicio` | Nombre de cada servicio |

---

## Caso sin reservas

Si no hay reservas, se muestra:
```
📭  (ícono grande)
No hay reservas registradas.
```
