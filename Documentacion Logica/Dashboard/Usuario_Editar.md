# Vista: Editar Usuario

**Archivo:** `views/dashboard/usuario_editar.php`  
**Estado:** Vista de referencia / funcionalidad futura  

---

## ¿Qué hace esta vista?

Formulario para editar los datos de un usuario (nombre, apellido, correo, teléfono y rol). Fue diseñada para una funcionalidad de gestión de usuarios más completa.

---

## Campos del formulario

| Campo | Tipo | Obligatorio | Descripción |
|-------|------|-------------|-------------|
| `nombre` | text | ✅ | Nombre del usuario |
| `apellido` | text | ✅ | Apellido del usuario |
| `correo` | email | ✅ | Correo electrónico |
| `telefono` | tel | ❌ | Teléfono de contacto |
| `rol` | select | ✅ | cliente / admin / empleado |

---

## Datos que recibe

La vista espera la variable `$usuario` con los datos actuales del usuario a editar:

```php
$usuario = [
    'id_cliente'  => 5,
    'nombre'      => 'María',
    'apellido'    => 'García',
    'correo'      => 'maria@ejemplo.com',
    'telefono'    => '300 000 0000',
    'rol'         => 'cliente',
];
```

---

## Envío del formulario

- **Acción:** `POST` a `?action=guardarEdicion`
- Incluye campo oculto `id` con el ID del usuario

---

## Estado actual

Esta vista existe en el proyecto pero la acción `guardarEdicion` no está registrada en el router (`public/index.php`). Es una funcionalidad pendiente de implementar.
