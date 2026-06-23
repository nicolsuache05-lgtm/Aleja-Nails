# Vista: Gestión de Clientes

**Archivo:** `views/admin/clientes.php`  
**Acción:** `?action=listarClientes`  
**Rol:** Administrador  
**Controlador:** `AdminUsuarioController::listarClientes()`

---

## ¿Qué hace esta vista?

Muestra la lista completa de clientes registrados en el sistema. El administrador puede ver su información de contacto, su estado de cuenta y activarlos o desactivarlos con un solo clic.

---

## Estructura visual

```
┌─────────────────────────────────────────────────────────────┐
│  👥 Clientes                                                │
├────┬─────────────────┬──────────────────┬──────────┬────────┤
│ #  │ Cliente         │ Correo           │ Teléfono │ Estado │ Acción
├────┼─────────────────┼──────────────────┼──────────┼────────┤
│ 5  │ 👤 María García │ maria@gmail.com  │ 300...   │ Activo │ 🔒 Desactivar
│ 4  │ 👤 Ana López    │ ana@gmail.com    │ 311...   │Inactivo│ 🔓 Activar
└────┴─────────────────┴──────────────────┴──────────┴────────┘
```

---

## Columnas de la tabla

| Columna | Fuente en BD | Descripción |
|---------|-------------|-------------|
| `#` | `cliente.id_cliente` | ID en gris discreto, prefijado con `#` |
| `Cliente` | `cliente.nombre` | Avatar emoji 👤 + nombre en negrita |
| `Correo` | `cliente.correo` | Email usado para iniciar sesión |
| `Teléfono` | `cliente.telefono` | Número de contacto, muestra `—` si no hay |
| `Estado` | `cliente.activo` | Badge verde **Activo** / rojo **Inactivo** |
| `Acción` | — | Botón para cambiar el estado |

---

## Botón de acción (toggle estado)

```
Si activo == 1:
    Botón  →  🔒 Desactivar  (estilo btn-danger, rojo)
    Acción →  ?action=toggleCliente&id={id}
    Efecto →  cliente.activo = 0

Si activo == 0:
    Botón  →  🔓 Activar  (estilo btn-success, verde)
    Acción →  ?action=toggleCliente&id={id}
    Efecto →  cliente.activo = 1
```

Antes de ejecutar, el navegador muestra un `confirm()`:
> *"¿Cambiar estado del cliente?"*

> ⚠️ Un cliente con `activo = 0` **no puede iniciar sesión**. El sistema verifica este campo en `AuthController::procesarLogin()`.

---

## Flujo completo de datos

```
AdminUsuarioController::listarClientes()
        │
        └── Cliente::obtenerTodos()
                │
                └── SELECT * FROM cliente ORDER BY id_cliente DESC
                        │
                        └── Array $clientes → views/admin/clientes.php
```

**Acción Desactivar / Activar:**
```
GET ?action=toggleCliente&id={id}
        │
        ├── Cliente::buscarPorId($id)
        │       └── SELECT * FROM cliente WHERE id_cliente = ?
        │
        ├── Lee el valor actual de $cliente['activo']
        │       Si 1 → nuevo estado = 0
        │       Si 0 → nuevo estado = 1
        │
        └── Cliente::cambiarEstado($id, $nuevoEstado)
                └── UPDATE cliente SET activo = ? WHERE id_cliente = ?
                        └── Redirige a ?action=listarClientes
```

---

## Tabla de base de datos

**`cliente`**

| Campo | Tipo | Descripción |
|-------|------|-------------|
| `id_cliente` | INT PK | Identificador único |
| `nombre` | VARCHAR(30) | Nombre completo |
| `correo` | VARCHAR(25) | Email de acceso |
| `telefono` | VARCHAR(15) | Teléfono (puede ser NULL) |
| `activo` | TINYINT(1) | 1 = activo, 0 = desactivado |

---

## Caso sin clientes

Si no hay clientes registrados, se muestra un estado vacío:
```
👤  (ícono grande)
No hay clientes registrados aún.
```

---

## Seguridad

- El método `validarAdmin()` verifica `$_SESSION['rol'] === 'admin'` antes de cargar esta vista.
- Si un cliente intenta acceder directamente, recibe HTTP 403.
