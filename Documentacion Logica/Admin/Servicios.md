# Vista: Gestión de Servicios

**Archivo:** `views/admin/servicios.php`  
**Acción:** `?action=listarServicios`  
**Rol:** Administrador  
**Controlador:** `AdminUsuarioController::listarServicios()`

---

## ¿Qué hace esta vista?

Muestra el catálogo completo de servicios agrupado por categoría. El administrador puede editar la descripción y el precio de cualquier servicio directamente en la tabla (edición inline), o eliminar servicios que ya no estén disponibles.

---

## Estructura visual

```
┌──────────────────────────────────────────────────────────────┐
│  💅 Manicure  (4 servicios)                                  │
├──────────────────┬──────────────────┬──────────┬────────────┤
│ Nombre           │ Descripción      │ Precio   │ Acciones   │
├──────────────────┼──────────────────┼──────────┼────────────┤
│ 💅 Manicure gel  │ [campo editable] │ [campo]  │ ✓ Guardar  🗑│
│ 💅 Esmaltado     │ [campo editable] │ [campo]  │ ✓ Guardar  🗑│
└──────────────────┴──────────────────┴──────────┴────────────┘

┌──────────────────────────────────────────────────────────────┐
│  👣 Pedicure  (3 servicios)                                  │
│  ...                                                         │
└──────────────────────────────────────────────────────────────┘
```

---

## Organización por categorías

Los servicios se agrupan automáticamente según `servicio.categoria`. Cada categoría tiene su propio color temático:

| Categoría | Ícono | Color borde | Color texto |
|-----------|-------|-------------|-------------|
| Manicure | 💅 | `#f4c0d1` rosado | `#c93060` |
| Pedicure | 👣 | `#bbdefb` azul | `#1565c0` |
| Capilar | 💆🏽‍♀️ | `#ce93d8` púrpura | `#6a1b9a` |
| Otros | ✨ | `#a5d6a7` verde | `#2e7d32` |

El encabezado de cada tarjeta muestra el ícono, nombre y cantidad de servicios:
> *💅 Manicure (4 servicios)*

---

## Columnas de la tabla

| Columna | Descripción |
|---------|-------------|
| `Nombre` | Ícono de categoría + nombre del servicio (solo lectura) |
| `Descripción` | `<input type="text">` editable directamente |
| `Precio (COP)` | `<input type="number">` editable, pasos de $500, mínimo $0 |
| `Acciones` | Botón **✓ Guardar** + botón **🗑 Eliminar** |

---

## Editar un servicio (inline)

Cada fila tiene un formulario con `id="form-{id_servicio}"`. Los campos de descripción y precio están vinculados a ese formulario aunque estén en columnas distintas (atributo `form="form-{id}"`).

```
1. Admin modifica descripción y/o precio directamente en la celda
2. Clic en "✓ Guardar"
3. POST ?action=actualizarServicio
        │
        ├── Lee id_servicio, descripcion, precio del POST
        ├── Valida $id > 0 y $precio >= 0
        └── Servicio::editar($id, ['descripcion'=>..., 'precio'=>...])
                └── UPDATE servicio SET descripcion=?, precio=? WHERE id_servicio=?
                        └── Flash "Servicio actualizado" + Redirige a listarServicios
```

---

## Eliminar un servicio

```
1. Clic en botón "🗑"
2. confirm("¿Eliminar este servicio?")
3. POST ?action=eliminarServicio
        │
        └── Servicio::eliminar($id)
                └── DELETE FROM servicio WHERE id_servicio = ?
                        └── Flash "Servicio eliminado" + Redirige a listarServicios
```

> ⚠️ **Restricción de integridad:** Si el servicio tiene reservas asociadas en `detalle_servicio`, la eliminación fallará por la clave foránea. En ese caso el sistema mostrará un error en el log. Se recomienda desactivar el servicio en lugar de eliminarlo.

---

## Flujo de datos (GET)

```
AdminUsuarioController::listarServicios()
        │
        └── Servicio::obtenerTodos()
                │
                ├── DESCRIBE servicio → verifica si existe columna 'categoria'
                │   Si no existe → ALTER TABLE ADD COLUMN categoria
                │
                └── SELECT * FROM servicio ORDER BY categoria ASC, nombre_servicio ASC
                        │
                        └── PHP agrupa: $grupos['Manicure'][] = [...], etc.
                                └── views/admin/servicios.php
```

---

## Tabla de base de datos

**`servicio`**

| Campo | Tipo | Descripción |
|-------|------|-------------|
| `id_servicio` | INT PK | Identificador único |
| `nombre_servicio` | VARCHAR(30) | Nombre del servicio |
| `descripcion` | VARCHAR(30) | Descripción breve (editable) |
| `precio` | DECIMAL(10,0) | Precio en COP (editable) |
| `categoria` | VARCHAR(50) | Manicure / Pedicure / Capilar / Otros |
| `id_administrador` | INT FK | Admin que creó el servicio |

---

## Caso sin servicios

```
💅  (ícono grande)
No hay servicios registrados.
```
