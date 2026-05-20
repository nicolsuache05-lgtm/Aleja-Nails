# Vista: Gestión de Servicios (Admin)

**Archivo:** `views/admin/servicios.php`  
**Ruta de acceso:** `?action=listarServicios`  
**Rol requerido:** Administrador  
**Controlador:** `AdminUsuarioController::listarServicios()`

---

## ¿Qué hace esta vista?

Permite al administrador ver, editar y eliminar los servicios del catálogo. Los servicios están organizados por categoría, cada una en su propia tarjeta.

---

## Organización por categorías

Los servicios se agrupan automáticamente según su campo `categoria`:

| Categoría | Ícono |
|-----------|-------|
| Manicure | 💅 |
| Pedicure | 👣 |
| Capilar | 💆🏽‍♀️ |
| Otros | ✨ |

---

## Datos que muestra por servicio

| Columna | Descripción |
|---------|-------------|
| `Nombre` | Nombre del servicio (solo lectura) |
| `Descripción` | Campo editable de texto |
| `Precio (COP)` | Campo editable numérico (pasos de $500) |
| `Acción` | Botones **Guardar** y **Eliminar** |

---

## Editar un servicio

- La descripción y el precio son campos editables directamente en la tabla (edición inline)
- Al hacer clic en **"Guardar"** se envía `POST` a `?action=actualizarServicio`
- El controlador ejecuta `UPDATE servicio SET descripcion=?, precio=? WHERE id_servicio=?`

---

## Eliminar un servicio

- Al hacer clic en **"Eliminar"** aparece un diálogo de confirmación
- Si se confirma, se envía `POST` a `?action=eliminarServicio`
- El controlador ejecuta `DELETE FROM servicio WHERE id_servicio=?`

> ⚠️ No se puede eliminar un servicio que tenga reservas asociadas (restricción de clave foránea en la base de datos).

---

## Flujo de datos

```
AdminUsuarioController::listarServicios()
    └── Servicio::obtenerTodos()
            └── SELECT * FROM servicio ORDER BY categoria, nombre_servicio
                    └── PHP agrupa por $s['categoria']
                            └── views/admin/servicios.php
```

---

## Tablas de base de datos involucradas

| Tabla | Campos usados |
|-------|---------------|
| `servicio` | `id_servicio`, `nombre_servicio`, `descripcion`, `precio`, `categoria` |
