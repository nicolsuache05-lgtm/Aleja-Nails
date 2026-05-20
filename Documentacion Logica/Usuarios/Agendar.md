# Vista: Agendar Cita

**Archivo:** `views/usuarios/agendar.php`  
**Ruta de acceso:** `?action=agendarCita`  
**Rol requerido:** Cliente autenticado  
**Controlador:** `UsuarioController::agendarCita()`

---

## ¿Qué hace esta vista?

Permite al cliente seleccionar uno o varios servicios, ver el total acumulado en tiempo real, elegir fecha y hora, y confirmar la reserva.

---

## Secciones de la vista

### 1. Tabs de categoría

Botones que filtran los servicios por categoría:

| Tab | Categoría |
|-----|-----------|
| 💅 Manicure | Servicios de uñas de manos |
| 👣 Pedicure | Servicios de uñas de pies |
| 💆🏽‍♀️ Capilar | Tratamientos de cabello |
| ✨ Otros | Servicios adicionales |

Solo se muestra el panel de la categoría activa. Al cambiar de tab, el panel anterior se oculta con `display:none`.

---

### 2. Tarjetas de servicios (checkboxes)

Cada servicio se muestra como una tarjeta clickeable con:
- Imagen representativa de la categoría
- Nombre del servicio
- Descripción breve
- Precio en COP

**Al hacer clic en una tarjeta:**
- Se activa/desactiva el `<input type="checkbox">` oculto
- La tarjeta se resalta con borde rosado y fondo más intenso
- Aparece un ✓ en la esquina superior derecha
- Se actualiza el panel de resumen

---

### 3. Panel de resumen (dinámico)

Aparece automáticamente al seleccionar el primer servicio:

```
🛒 Resumen de servicios seleccionados
─────────────────────────────────────
• Corte de cabello          $15.000
• Hidratación capilar       $45.000
─────────────────────────────────────
Total a pagar:              $60.000
```

El total se recalcula en JavaScript cada vez que se selecciona o deselecciona un servicio.

---

### 4. Fecha y hora

| Campo | Tipo | Restricción |
|-------|------|-------------|
| `fecha` | date | No puede ser anterior a hoy |
| `hora` | select | Intervalos de 30 min, de 8:00 a 19:00 |

---

## Validaciones

### En el navegador (JavaScript)
- Al enviar el formulario, verifica que `seleccionados.size > 0`
- Si no hay servicios seleccionados, muestra mensaje de error y bloquea el envío

### En el servidor (PHP)
```
1. Array $ids no vacío
   → Error: "Debes seleccionar al menos un servicio."

2. Fecha no anterior a hoy
   → Error: "No puedes agendar en fechas pasadas."

3. Horario no ocupado (Reserva::existeConflicto)
   → Error: "Ese horario ya está reservado. Elige otro."

4. IDs de servicios válidos en BD
   → Error: "Los servicios seleccionados no son válidos."
```

---

## Flujo al confirmar (POST)

```
POST ?action=agendarCita
    │
    ├── Validaciones (ver arriba)
    │
    ├── Para cada id en $_POST['servicios[]']:
    │       Servicio::buscarPorId($id) → obtiene precio real de BD
    │
    ├── Reserva::crearConDetalles($datos, $servicios)
    │       ├── BEGIN TRANSACTION
    │       ├── INSERT INTO reserva (fecha, hora, id_cliente, id_servicio, estado='pendiente')
    │       ├── Para cada servicio:
    │       │       INSERT INTO detalle_servicio (cantidad=1, precio_unitario, subtotal, id_reserva, id_servicio)
    │       └── COMMIT → retorna $idReserva
    │
    └── Redirige a ?action=pagar&id={$idReserva}
```

---

## Tablas de base de datos involucradas

| Tabla | Operación |
|-------|-----------|
| `servicio` | `SELECT` para cargar el catálogo |
| `reserva` | `INSERT` con datos de la cita |
| `detalle_servicio` | `INSERT` por cada servicio seleccionado |

---

## Datos que pasa el formulario al servidor

```
POST:
  servicios[] = [3, 7, 12]   ← IDs de los servicios seleccionados
  fecha       = "2026-06-15"
  hora        = "10:00"
```
