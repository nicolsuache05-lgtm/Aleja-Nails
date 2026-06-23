# Vista: Historial de Pagos

**Archivo:** `views/admin/pagos.php`  
**Acción:** `?action=verPagos`  
**Rol:** Administrador  
**Controlador:** `AdminUsuarioController::verPagos()`

---

## ¿Qué hace esta vista?

Muestra el historial completo de todos los pagos registrados, con resumen estadístico en la parte superior y la tabla detallada debajo. El administrador solo puede **consultar**, no modificar pagos.

---

## Estructura visual

```
┌──────────────┐ ┌──────────────┐ ┌──────────────┐ ┌──────────────┐
│ 💰 Total     │ │ 🧾 N° pagos  │ │ 🏦 Transfer. │ │ 💵 Efectivo  │
│ recaudado    │ │              │ │              │ │              │
│ $450.000     │ │     12       │ │      7       │ │      5       │
└──────────────┘ └──────────────┘ └──────────────┘ └──────────────┘

┌────┬──────────────┬────────────────┬────────────┬──────────────┬──────────┐
│ #  │ Cliente      │ Servicio       │ Fecha pago │ Método       │ Valor    │
├────┼──────────────┼────────────────┼────────────┼──────────────┼──────────┤
│ 12 │ María García │ Manicure gel   │ 2026-06-20 │ 🏦 Transfer. │ $80.000  │
│ 11 │ Ana López    │ Corte cabello  │ 2026-06-19 │ 💵 Efectivo  │ $45.000  │
└────┴──────────────┴────────────────┴────────────┴──────────────┴──────────┘
```

---

## Tarjetas de resumen (calculadas en PHP)

| Tarjeta | Color | Cálculo |
|---------|-------|---------|
| 💰 Total recaudado | Verde | `array_sum(array_column($pagos, 'valor_pagado'))` |
| 🧾 N° de pagos | Rosa | `count($pagos)` |
| 🏦 Transferencias | Azul | `count(filter metodo_pago === 'transferencia')` |
| 💵 Efectivo | Naranja | `count(filter metodo_pago === 'efectivo')` |

---

## Columnas de la tabla

| Columna | Fuente | Descripción |
|---------|--------|-------------|
| `#` | `pago.id_pago` | ID del pago |
| `Cliente` | `cliente.nombre` | Nombre del cliente que pagó |
| `Servicio` | `servicio.nombre_servicio` | Servicio principal de la reserva |
| `Fecha pago` | `pago.fecha_pago` | Fecha en que se registró el pago |
| `Método` | `pago.metodo_pago` | Badge con color: 🏦 azul o 💵 naranja |
| `Valor` | `pago.valor_pagado` | Monto en verde y negrita |

---

## Badges de método de pago

```
transferencia → 🏦 Transferencia  (fondo azul claro #e3f2fd, texto #1565c0)
efectivo      → 💵 Efectivo       (fondo naranja claro #fff3e0, texto #e65100)
```

---

## Flujo de datos

```
AdminUsuarioController::verPagos()
        │
        └── SQL con JOIN de 4 tablas:
                SELECT p.*, c.nombre AS nombre_cliente, s.nombre_servicio
                FROM pago p
                INNER JOIN reserva  r ON p.id_reserva  = r.id_reserva
                INNER JOIN cliente  c ON r.id_cliente  = c.id_cliente
                INNER JOIN servicio s ON r.id_servicio = s.id_servicio
                ORDER BY p.id_pago DESC
                        │
                        └── Array $pagos → views/admin/pagos.php
```

> El controlador tiene manejo de errores con `try/catch`. Si la consulta falla (por ejemplo, datos inconsistentes en BD), retorna `$pagos = []` y registra el error en el log del servidor.

---

## Tablas involucradas

| Tabla | Campos usados |
|-------|---------------|
| `pago` | `id_pago`, `fecha_pago`, `metodo_pago`, `valor_pagado`, `id_reserva` |
| `reserva` | `id_reserva`, `id_cliente`, `id_servicio` |
| `cliente` | `nombre` → alias `nombre_cliente` |
| `servicio` | `nombre_servicio` |

---

## Relación con otras vistas

Los pagos se **originan** en `views/usuarios/pagar.php` cuando el cliente confirma su pago. El admin solo los consulta aquí.

```
Cliente confirma pago
    → INSERT INTO pago
    → UPDATE reserva SET estado='confirmada'
        → Aparece en esta vista
```

---

## Caso sin pagos

Si no hay pagos registrados, se muestra:
```
💸  (ícono grande)
No hay pagos registrados aún.
```
