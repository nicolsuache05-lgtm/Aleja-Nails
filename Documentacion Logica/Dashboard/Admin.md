# Vista: Dashboard Administrador

**Archivo:** `views/dashboard/admin.php`  
**Ruta de acceso:** `?action=adminPanel`  
**Rol requerido:** Administrador  
**Controlador:** `AdminUsuarioController::dashboard()`

---

## ¿Qué hace esta vista?

Es la pantalla principal del administrador. Muestra un resumen general del sistema con contadores en tiempo real y accesos rápidos a cada módulo.

---

## Secciones de la vista

### 1. Tarjetas de estadísticas

Cuatro tarjetas que muestran conteos directos de la base de datos:

| Tarjeta | Consulta SQL |
|---------|-------------|
| Total clientes | `SELECT COUNT(*) FROM cliente` |
| Total reservas | `SELECT COUNT(*) FROM reserva` |
| Total pagos | `SELECT COUNT(*) FROM pago` |
| Servicios | `SELECT COUNT(*) FROM servicio` |

### 2. Accesos rápidos

Botones de navegación directa a cada módulo:

| Botón | Redirige a |
|-------|-----------|
| 📅 Ver reservas | `?action=listarReservas` |
| 👥 Ver clientes | `?action=listarClientes` |
| 💅 Ver servicios | `?action=listarServicios` |
| 💰 Ver pagos | `?action=verPagos` |

---

## Flujo de datos

```
AdminUsuarioController::dashboard()
    ├── SELECT COUNT(*) FROM cliente    → $totalClientes
    ├── SELECT COUNT(*) FROM reserva    → $totalReservas
    ├── SELECT COUNT(*) FROM pago       → $totalPagos
    └── SELECT COUNT(*) FROM servicio   → $totalServicios
            └── views/dashboard/admin.php
```

---

## Acceso y seguridad

- Solo accesible si `$_SESSION['rol'] === 'admin'`
- Si un cliente intenta acceder, el método `validarAdmin()` retorna HTTP 403
- Si no hay sesión activa, redirige a `?action=login`
