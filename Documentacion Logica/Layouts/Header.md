# Layout: Header

**Archivo:** `views/layouts/header.php`  
**Tipo:** Componente reutilizable — se incluye al inicio de cada vista  
**Uso:** `require_once __DIR__ . '/../layouts/header.php';`

---

## ¿Qué hace este layout?

Genera el `<head>` HTML completo, la barra de navegación superior (topbar) y abre el contenedor principal del layout. También muestra los mensajes flash de éxito o error.

---

## Variables que recibe

| Variable | Origen | Uso |
|----------|--------|-----|
| `$titulo` | Definida en cada vista antes del include | Título de la pestaña del navegador |
| `$_SESSION['rol']` | Sesión PHP | Determina los links del topbar |
| `$_SESSION['nombre']` | Sesión PHP | Muestra "Hola, {nombre}" en el topbar |
| `$_SESSION['flash_ok']` | Sesión PHP | Mensaje verde de éxito |
| `$_SESSION['flash_error']` | Sesión PHP | Mensaje rojo de error |

---

## Secciones que genera

### 1. `<head>`
- Charset UTF-8 y viewport responsive
- Título dinámico: `{$titulo} — Aleja-Nails`
- Fuentes de Google: **Poppins** (texto) y **Great Vibes** (logo)
- Todos los estilos CSS globales del sistema

### 2. Topbar (barra superior)
- Logo "Aleja-Nails" con fuente cursiva
- Saludo con el nombre del usuario logueado
- Link al panel según rol (`adminPanel` o `dashboard`)
- Botón "Cerrar sesión"

### 3. Mensajes flash
- Si existe `$_SESSION['flash_ok']` → muestra banner verde y lo elimina
- Si existe `$_SESSION['flash_error']` → muestra banner rojo y lo elimina

### 4. Apertura del layout flex
- Abre `<div class="layout">` que contiene el sidebar y el `<main>`

---

## Estilos CSS globales definidos aquí

| Clase | Descripción |
|-------|-------------|
| `.topbar` | Barra superior con degradado rosado |
| `.card` | Tarjeta blanca con sombra suave |
| `.btn`, `.btn-primary`, `.btn-outline`, `.btn-danger`, `.btn-sm` | Sistema de botones |
| `.tabla-wrap`, `table`, `th`, `td` | Estilos de tablas |
| `.badge-*` | Badges de colores para estados |
| `.form-group`, `.form-row` | Estilos de formularios |
| `.stats-grid`, `.stat-card` | Tarjetas de estadísticas del dashboard |
| `.flash.ok`, `.flash.error` | Mensajes de notificación |

---

## Paleta de colores del sistema

| Color | Hex | Uso |
|-------|-----|-----|
| Rosa principal | `#e8527a` | Botones, bordes activos |
| Rosa oscuro | `#c93060` | Hover, degradados |
| Rosa fondo | `#fdf0f5` | Fondo de inputs y tarjetas |
| Vino texto | `#4a2030` | Texto principal |
| Rosa título | `#c0375a` | Títulos y encabezados |
