# Vista: Dashboard Cliente

**Archivo:** `views/dashboard/cliente.php`  
**Acción:** `?action=dashboard`  
**Rol:** Cliente autenticado  
**Controlador:** `UsuarioController::dashboard()`

---

## ¿Qué hace esta vista?

Es la pantalla principal del cliente después de iniciar sesión. Muestra un resumen personal de sus citas, acciones rápidas, la tabla con sus últimas 5 reservas y el catálogo de servicios disponibles.

---

## Estructura visual

```
💅 Bienvenida, María
Aquí puedes gestionar tus citas y ver tus servicios favoritos.

┌──────────────┐ ┌──────────────┐ ┌──────────────┐
│ 📅 Total     │ │ ⏳ Pendientes │ │ ✅ Completadas│
│   citas      │ │              │ │              │
│   stat-pink  │ │  stat-orange │ │  stat-green  │
│     8        │ │      2       │ │      5       │
└──────────────┘ └──────────────┘ └──────────────┘

⚡ ¿Qué deseas hacer?
[ 📅 Agendar nueva cita ]  [ 📋 Ver mis reservas ]

📅 Mis últimas reservas
┌──────────────────┬────────────┬──────┬────────┬──────────┬────────┐
│ Servicio         │ Fecha      │ Hora │ Estado │ Total    │ Acción │
├──────────────────┼────────────┼──────┼────────┼──────────┼────────┤
│ Manicure + Pedi  │ 01/07/2026 │ 10:00│🌸Conf. │ $125.000 │   —    │
│ Corte cabello    │ 25/06/2026 │ 14:30│🟡Pend. │ $45.000  │💳 Pagar│
└──────────────────┴────────────┴──────┴────────┴──────────┴────────┘

💅 Nuestros servicios
┌──────────────┐ ┌──────────────┐ ┌──────────────┐
│ 💅            │ │ 👣            │ │ 💆🏽‍♀️           │
│ Manicure gel  │ │ Pedicure     │ │ Keratina     │
│ $80.000      │ │ $60.000      │ │ $120.000     │
└──────────────┘ └──────────────┘ └──────────────┘
```

---

## Tarjetas de estadísticas

Calculadas en PHP a partir del array `$reservas`:

| Tarjeta | Variante CSS | Ícono | Cálculo PHP |
|---------|-------------|-------|-------------|
| Total citas | `stat-pink` | 📅 | `count($reservas)` |
| Pendientes | `stat-orange` | ⏳ | `count(filter estado==='pendiente')` |
| Completadas | `stat-green` | ✅ | `count(filter estado==='completada')` |

---

## Tabla de últimas 5 reservas

Muestra solo las primeras 5 con `array_slice($reservas, 0, 5)`.

| Columna | Fuente | Descripción |
|---------|--------|-------------|
| Servicio | `$r['nombre_servicio']` | Nombre(s) del servicio |
| Fecha | `$r['fecha']` | Formateada como `dd/mm/YYYY` |
| Hora | `$r['hora']` | HH:MM |
| Estado | `$r['estado']` | Badge de color |
| Total | `$r['precio']` | En rosado negrita |
| Acción | — | Botón 💳 Pagar si pendiente sin pago |

**Botón Pagar:** aparece si `$r['estado'] === 'pendiente'` y `empty($r['pagado'])`.
Redirige a `?action=pagar&id={id_reserva}`.

Si hay más de 5 reservas, aparece el enlace **"Ver todas las reservas →"**.

---

## Catálogo de servicios

Cuadrícula de tarjetas con efecto hover de elevación. Cada tarjeta muestra:
- Ícono de categoría (💅 / 👣 / 💆🏽‍♀️ / ✨)
- Nombre del servicio
- Descripción breve
- Precio en COP

---

## Flujo de datos

```
UsuarioController::dashboard()
        │
        ├── Reserva::obtenerPorCliente($_SESSION['usuario_id'])
        │       │
        │       ├── SELECT reserva.* WHERE id_cliente = ?
        │       ├── Para cada reserva → obtiene servicios de detalle_servicio
        │       └── Verifica pago: SELECT FROM pago WHERE id_reserva = ?
        │               └── $reservas (con 'servicios', 'precio', 'pagado')
        │
        └── Servicio::obtenerTodos()
                └── SELECT * FROM servicio ORDER BY categoria, nombre_servicio
                        └── $servicios
                                └── views/dashboard/cliente.php
```

---

## Caso sin reservas

Se muestra un estado vacío:
```
📭  (ícono grande)
Aún no tienes reservas agendadas.
[ 📅 Agendar mi primera cita ]
```

---

## Seguridad

```
UsuarioController::dashboard()
        └── validarSesion()
                └── Si no hay $_SESSION['usuario_id']
                        → Redirige a ?action=login
```
