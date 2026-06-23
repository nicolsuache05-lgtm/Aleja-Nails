# Vista: Dashboard Administrador

**Archivo:** `views/dashboard/admin.php`  
**Acción:** `?action=adminPanel`  
**Rol:** Administrador  
**Controlador:** `AdminUsuarioController::dashboard()`

---

## ¿Qué hace esta vista?

Es la pantalla principal del administrador. Presenta un resumen en tiempo real del estado del sistema con 4 contadores y accesos directos a cada módulo de gestión.

---

## Estructura visual

```
Panel de Administración 📊

┌──────────────┐ ┌──────────────┐ ┌──────────────┐ ┌──────────────┐
│ 👥 Clientes  │ │ 📅 Reservas  │ │ 💰 Pagos     │ │ 💅 Servicios │
│    azul      │ │    rosado    │ │    verde     │ │    púrpura   │
│     12       │ │     34       │ │     21       │ │      8       │
└──────────────┘ └──────────────┘ └──────────────┘ └──────────────┘

⚡ Accesos rápidos

┌──────────────────┐ ┌──────────────────┐ ┌──────────────────┐ ┌──────────────────┐
│ 📅 Ver Reservas  │ │ 👥 Ver Clientes  │ │ 💅 Ver Servicios │ │ 💰 Ver Pagos     │
│ (rosado)        │ │ (azul)          │ │ (púrpura)       │ │ (verde)         │
└──────────────────┘ └──────────────────┘ └──────────────────┘ └──────────────────┘
```

---

## Tarjetas de estadísticas

Cada tarjeta usa el sistema de `stat-card` con variante de color. Al pasar el cursor, se elevan con `transform: translateY(-3px)`.

| Tarjeta | Variante CSS | Ícono | Consulta SQL |
|---------|-------------|-------|-------------|
| Clientes | `stat-blue` | 👥 | `SELECT COUNT(*) FROM cliente` |
| Reservas | `stat-pink` | 📅 | `SELECT COUNT(*) FROM reserva` |
| Pagos | `stat-green` | 💰 | `SELECT COUNT(*) FROM pago` |
| Servicios | `stat-purple` | 💅 | `SELECT COUNT(*) FROM servicio` |

Cada variante tiene una barra de 3px en la parte superior con el color correspondiente.

---

## Tarjetas de accesos rápidos

Diseño de cuadrícula responsive. Cada tarjeta tiene su color temático y un efecto `hover` de elevación:

| Módulo | Color | Redirige a |
|--------|-------|-----------|
| 📅 Ver Reservas | Rosado | `?action=listarReservas` |
| 👥 Ver Clientes | Azul | `?action=listarClientes` |
| 💅 Ver Servicios | Púrpura | `?action=listarServicios` |
| 💰 Ver Pagos | Verde | `?action=verPagos` |

---

## Flujo de datos

```
AdminUsuarioController::dashboard()
        │
        ├── SELECT COUNT(*) FROM cliente  → $totalClientes
        ├── SELECT COUNT(*) FROM reserva  → $totalReservas
        ├── SELECT COUNT(*) FROM pago     → $totalPagos
        └── SELECT COUNT(*) FROM servicio → $totalServicios
                │
                └── views/dashboard/admin.php
```

---

## Acceso y seguridad

```
Cualquier request al adminPanel:
        │
        └── AdminUsuarioController::__construct()
                └── validarAdmin()
                        ├── Si no hay sesión → Redirige a ?action=login
                        └── Si rol ≠ 'admin' → HTTP 403 "Acceso denegado"
```
