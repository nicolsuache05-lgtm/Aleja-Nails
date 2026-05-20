# Vista: Gestión de Clientes (Admin)

**Archivo:** `views/admin/clientes.php`  
**Ruta de acceso:** `?action=listarClientes`  
**Rol requerido:** Administrador  
**Controlador:** `AdminUsuarioController::listarClientes()`

---

## ¿Qué hace esta vista?

Permite al administrador ver todos los clientes registrados en el sistema y activar o desactivar sus cuentas.

---

## Datos que muestra

| Columna | Descripción |
|---------|-------------|
| `#` | ID único del cliente en la base de datos |
| `Nombre` | Nombre completo del cliente |
| `Correo` | Correo electrónico (usado para iniciar sesión) |
| `Teléfono` | Número de contacto |
| `Estado` | Badge verde **Activo** o rojo **Inactivo** |
| `Acción` | Botón para cambiar el estado del cliente |

---

## Acciones disponibles

### Desactivar / Activar cliente
- Si el cliente está **Activo** → aparece botón **"Desactivar"**
- Si el cliente está **Inactivo** → aparece botón **"Activar"**
- Al hacer clic se pide confirmación con un diálogo
- Redirige a `?action=toggleCliente&id={id_cliente}`

> Un cliente desactivado **no puede iniciar sesión** en el sistema.

---

## Flujo de datos

```
AdminUsuarioController::listarClientes()
    └── Cliente::obtenerTodos()
            └── SELECT * FROM cliente ORDER BY id_cliente DESC
                    └── Retorna array con todos los clientes
                            └── views/admin/clientes.php
```

---

## Tabla de base de datos involucrada

**`cliente`**

| Campo | Uso en esta vista |
|-------|-------------------|
| `id_cliente` | Columna # y parámetro del botón acción |
| `nombre` | Columna Nombre |
| `correo` | Columna Correo |
| `telefono` | Columna Teléfono |
| `activo` | Determina el badge y el texto del botón |

---

## Lógica de estado

```
$activo = $c['activo'] ?? 1

Si $activo == 1  →  Badge "Activo"   + Botón "Desactivar" (rojo)
Si $activo == 0  →  Badge "Inactivo" + Botón "Activar"    (outline)
```
