# Vista: Historial de Pagos (Admin)

**Archivo:** `views/admin/pagos.php`  
**Ruta de acceso:** `?action=verPagos`  
**Rol requerido:** Administrador  
**Controlador:** `AdminUsuarioController::verPagos()`

---

## ¿Qué hace esta vista?

Muestra el historial completo de todos los pagos registrados en el sistema, con información del cliente, el servicio y el método de pago utilizado.

---

## Datos que muestra

| Columna | Descripción |
|---------|-------------|
| `#` | ID único del pago |
| `Cliente` | Nombre del cliente que realizó el pago |
| `Servicio` | Nombre del servicio asociado a la reserva |
| `Fecha pago` | Fecha en que se registró el pago |
| `Método` | `efectivo` o `transferencia` |
| `Valor` | Monto pagado en pesos colombianos (COP) |

---

## Flujo de datos

```
AdminUsuarioController::verPagos()
    └── Consulta SQL directa (JOIN de 4 tablas):
            SELECT pago.*, cliente.nombre, servicio.nombre_servicio
            FROM pago
            INNER JOIN reserva   ON pago.id_reserva    = reserva.id_reserva
            INNER JOIN cliente   ON reserva.id_cliente  = cliente.id_cliente
            INNER JOIN servicio  ON reserva.id_servicio = servicio.id_servicio
            ORDER BY pago.id_pago DESC
                └── views/admin/pagos.php
```

---

## Tablas de base de datos involucradas

| Tabla | Campos usados |
|-------|---------------|
| `pago` | `id_pago`, `fecha_pago`, `metodo_pago`, `valor_pagado`, `id_reserva` |
| `reserva` | `id_reserva`, `id_cliente`, `id_servicio` |
| `cliente` | `nombre` (como `nombre_cliente`) |
| `servicio` | `nombre_servicio` |

---

## Casos posibles

- **Sin pagos:** Muestra el mensaje *"No hay pagos registrados."*
- **Con pagos:** Muestra la tabla completa ordenada del más reciente al más antiguo

---

## Relación con otras vistas

Un pago se genera cuando el cliente confirma su reserva en la vista **Pagar** (`views/usuarios/pagar.php`). El administrador solo puede **consultar** los pagos, no modificarlos.
