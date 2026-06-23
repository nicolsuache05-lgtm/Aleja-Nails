# Layout: Sidebar (Menú Lateral)

**Archivo:** `views/layouts/sidebar.php`  
**Tipo:** Componente reutilizable — se incluye después del header en cada vista  
**Uso:** `require_once __DIR__ . '/../layouts/sidebar.php';`

---

## ¿Qué hace este layout?

Genera la barra de navegación lateral izquierda fija (`<aside>`). Este menú cambia automáticamente las opciones disponibles según el rol de la sesión activa y resalta visualmente el enlace activo. Además, incorpora una tarjeta de perfil de usuario simplificada en la parte superior.

---

## Componentes y Secciones

### 1. Perfil Mini de Usuario
Ubicado en la parte superior del Sidebar. Contiene:
- **Avatar:** Círculo con degradado de rosa a rosa oscuro (`linear-gradient(135deg, #e8527a, #c93060)`) y sombra suave. Muestra un emoji dinámico según el rol:
  - 👑 para **Administrador**
  - 💅 para **Cliente**
- **Nombre:** Muestra el valor de `$_SESSION['nombre']` (con escape HTML).
- **Badge de Rol:** 
  - *Administrador:* Fondo rosado claro con texto rojo oscuro.
  - *Cliente:* Fondo verde claro con texto verde oscuro.

### 2. Menú de Navegación según Rol

El menú se carga dinámicamente desde un arreglo asociativo `$links`:

#### Menú del Cliente
| Ícono | Label (Etiqueta) | Acción URL | Color de ícono inactivo |
|:---|:---|:---|:---|
| 🏠 | Inicio | `?action=dashboard` | `#fce4ef` |
| 📅 | Agendar cita | `?action=agendarCita` | `#fce4ef` |
| 📋 | Mis reservas | `?action=misReservas` | `#fce4ef` |

#### Menú del Administrador
| Ícono | Label (Etiqueta) | Acción URL | Color de ícono inactivo |
|:---|:---|:---|:---|
| 📊 | Dashboard | `?action=adminPanel` | `#f3e5f5` |
| 📅 | Reservas | `?action=listarReservas` | `#fce4ef` |
| 👥 | Clientes | `?action=listarClientes` | `#e3f2fd` |
| 💅 | Servicios | `?action=listarServicios` | `#fce4ef` |
| 💰 | Pagos | `?action=verPagos` | `#e8f5e9` |

---

## Lógica de Resaltado Activo

El archivo PHP determina el estado activo comparando el valor de `$_GET['action']` con la propiedad `'action'` del enlace. Los estilos aplicados dinámicamente son:

```php
$activo = ($action === $link['action']);

// Si el enlace está activo ($activo == true):
font-weight: 600;
color:       '#c93060';                             // Rosa oscuro
background:  'linear-gradient(135deg,#fce4ef,#fdf0f5)'; // Degradado rosa suave
border:      '1px solid #f4c0d1';                   // Borde rosa delimitado

// Si el enlace está inactivo ($activo == false):
font-weight: 500;
color:       '#7a4a5e';                             // Texto medio
background:  'transparent';
border:      '1px solid transparent';
```

Adicionalmente, si el enlace está activo, se añade un pequeño punto rosa (`#e8527a`) al final del botón para indicar la ubicación del cursor de navegación.

---

## Botón de Salida (Cierre de Sesión)

En la base del menú lateral se incluye de manera fija el botón **Cerrar sesión** (`🚪`), con estilos diferenciados:
- **Por defecto:** Texto color rosa silenciado (`#b07090`).
- **Hover (CSS inline / JS):** Cambia a fondo rojo muy claro (`#fff0f0`), texto rojo (`#c0392b`) y borde rojo claro (`#f5c6cb`).

---

## Dimensiones del Layout

- **Ancho:** 230px de ancho fijo.
- **Estructura:** Flexbox vertical (`flex-direction: column`) con un espaciado inter-elementos de 4px.
- **Fondo:** Blanco puro (`#ffffff`) con una línea de división derecha muy sutil (`1px solid #fde8f0`).
- **Padding:** 1.5rem vertical y 1rem horizontal.

