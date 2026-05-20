# Layout: Sidebar (Menú Lateral)

**Archivo:** `views/layouts/sidebar.php`  
**Tipo:** Componente reutilizable — se incluye después del header en cada vista  
**Uso:** `require_once __DIR__ . '/../layouts/sidebar.php';`

---

## ¿Qué hace este layout?

Genera el menú de navegación lateral izquierdo. Los links que muestra cambian automáticamente según el rol del usuario en sesión.

---

## Menú según rol

### Menú del Cliente

| Ícono | Label | Acción |
|-------|-------|--------|
| 🏠 | Inicio | `?action=dashboard` |
| 📒 | Agendar cita | `?action=agendarCita` |
| 📅 | Mis reservas | `?action=misReservas` |
| 🚪 | Salir | `?action=logout` |

### Menú del Administrador

| Ícono | Label | Acción |
|-------|-------|--------|
| 📊 | Dashboard | `?action=adminPanel` |
| 📅 | Reservas | `?action=listarReservas` |
| 👥 | Clientes | `?action=listarClientes` |
| 💅 | Servicios | `?action=listarServicios` |
| 💰 | Pagos | `?action=verPagos` |
| 🚪 | Salir | `?action=logout` |

---

## Lógica de resaltado activo

El link correspondiente a la página actual se resalta con fondo rosado:

```php
$action = $_GET['action'] ?? '';

// Si el link coincide con la acción actual:
color:     '#c0375a'    // rosado oscuro
background: '#eb6ba5ff' // rosado activo

// Si no coincide:
color:     '#8a5068'    // gris rosado
background: 'transparent'
```

---

## Variables que usa

| Variable | Origen | Uso |
|----------|--------|-----|
| `$_SESSION['rol']` | Sesión PHP | Determina qué menú mostrar |
| `$_GET['action']` | URL | Determina qué link resaltar como activo |

---

## Dimensiones

- **Ancho:** 220px fijo
- **Fondo:** Blanco con borde derecho rosado
- **Padding:** 1.5rem vertical, 1rem horizontal
