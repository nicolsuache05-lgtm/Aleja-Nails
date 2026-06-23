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

## Paleta de Colores y Variables de Diseño CSS (:root)

El diseño visual del sistema está centralizado en variables personalizadas de CSS (`:root`), lo que permite cambiar la estética global modificando únicamente este archivo:

### Colores del Sistema

| Variable CSS | Valor Hex | Uso en el Sistema |
|:---|:---|:---|
| `--pink` | `#e8527a` | Rosa principal: botones primarios, bordes de enfoque y elementos activos |
| `--pink-dark` | `#c93060` | Rosa oscuro: estados hover, texto destacado y degradados secundarios |
| `--pink-light` | `#fce4ef` | Rosa claro: fondos hover suaves y badges de confirmación |
| `--pink-bg` | `#fdf5f8` | Fondo rosa: fondo general de la página y de campos de entrada |
| `--pink-border` | `#f4c0d1` | Borde rosa: bordes de campos y divisiones de tarjetas |
| `--text` | `#3d1a28` | Color de texto principal: títulos y cuerpo de texto de alta visibilidad |
| `--text-soft` | `#7a4a5e` | Texto medio: etiquetas de formularios y descripciones |
| `--text-muted` | `#b07090` | Texto silenciado: subtítulos e íconos inactivos |
| `--white` | `#ffffff` | Blanco puro: fondo de tarjetas y elementos contrastantes |

### Bordes y Sombras (Sistema de Elevación)

| Variable CSS | Valor | Uso en el Sistema |
|:---|:---|:---|
| `--radius-sm` | `10px` | Esquinas para inputs, selectores y botones pequeños |
| `--radius-md` | `16px` | Esquinas para envoltorios de tablas y tarjetas medianas |
| `--radius-lg` | `22px` | Esquinas para contenedores principales y tarjetas de dashboard |
| `--shadow-sm` | `0 1px 6px rgba(200,50,90,.08)` | Sombra leve por defecto para tarjetas |
| `--shadow-md` | `0 4px 20px rgba(200,50,90,.12)` | Sombra en estados hover o foco |
| `--shadow-lg` | `0 8px 40px rgba(200,50,90,.16)` | Sombra para ventanas emergentes o modales |
