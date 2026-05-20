# Layout: Footer

**Archivo:** `views/layouts/footer.php`  
**Tipo:** Componente reutilizable — se incluye al final de cada vista  
**Uso:** `require_once __DIR__ . '/../layouts/footer.php';`

---

## ¿Qué hace este layout?

Cierra correctamente todos los contenedores HTML abiertos por el header, muestra el pie de página del sistema e incluye el widget del chatbot.

---

## Estructura que genera

```html
</div>          <!-- Cierra .layout (flex container) -->
</main>         <!-- Cierra el <main> de cada vista -->
</div>          <!-- Cierre adicional del layout -->

<footer>        <!-- Pie de página con copyright -->
  © 2026 Aleja-Nails · Salón de Belleza Profesional
</footer>

<!-- Widget del chatbot flotante -->
<?php require_once 'chatbot.php'; ?>

</body>
</html>
```

---

## Componentes incluidos

### Pie de página
- Texto de copyright con año dinámico: `<?= date('Y') ?>`
- Fondo blanco con borde superior rosado suave

### Chatbot
- Incluye automáticamente `views/layouts/chatbot.php`
- El botón flotante 💬 aparece en **todas las páginas** que usen este footer
- No requiere configuración adicional en cada vista

---

## Relación con el Header

El header abre estas etiquetas:
```html
<body>
  <header>...</header>
  <div class="layout">   ← abierto en header.php
    <aside>...</aside>   ← generado por sidebar.php
    <main>              ← abierto en cada vista
```

El footer las cierra todas:
```html
    </main>
  </div>
</body>
</html>
```

---

## Nota importante

Cada vista debe incluir el footer **después** de cerrar su propio `</main>`. El orden correcto de includes en cada vista es:

```php
// 1. Al inicio de la vista:
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';

// 2. Contenido de la vista dentro de <main>...</main>

// 3. Al final de la vista:
require_once __DIR__ . '/../layouts/footer.php';
```
