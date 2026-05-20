# Vista: Realizar Pago

**Archivo:** `views/usuarios/pagar.php`  
**Ruta de acceso:** `?action=pagar&id={id_reserva}`  
**Rol requerido:** Cliente autenticado (dueño de la reserva)  
**Controlador:** `UsuarioController::pagar()`

---

## ¿Qué hace esta vista?

Muestra el resumen completo de la reserva y permite al cliente seleccionar el método de pago para confirmar su cita.

---

## Secciones de la vista

### Lado izquierdo — Resumen de la reserva

| Elemento | Descripción |
|----------|-------------|
| Fecha | Fecha de la cita en formato dd/mm/aaaa |
| Hora | Hora de la cita |
| Lista de servicios | Nombre de cada servicio con su precio individual |
| Total | Suma de todos los servicios, destacado en degradado rosado |

---

### Lado derecho — Método de pago

#### Opción 1: Efectivo 💵
- Pago presencial en el salón el día de la cita
- No requiere información adicional

#### Opción 2: Transferencia bancaria 🏦
- Al seleccionar esta opción se despliega automáticamente un panel con los datos bancarios:

| Dato | Valor |
|------|-------|
| 💜 Nequi | 304 408 5465 |
| ❤️ Daviplata | 304 408 5465 |
| 👤 Titular | Alejandra Vanegas |

- Cada número tiene un botón **📋 Copiar** que copia al portapapeles
- Aviso para enviar el comprobante por WhatsApp

---

## Lógica del botón Copiar

```javascript
navigator.clipboard.writeText(numero)
    → Cambia el botón a "✅ Copiado" por 2 segundos
    → Luego vuelve al texto original
```

---

## Validación antes de confirmar

- Si no se ha seleccionado ningún método → muestra mensaje de error y bloquea el envío
- La validación ocurre en JavaScript antes de enviar el formulario

---

## Flujo al confirmar el pago (POST)

```
POST ?action=pagar&id={id_reserva}
    │
    ├── Verifica que la reserva pertenece al cliente en sesión
    │
    ├── Valida que metodo_pago ∈ ['efectivo', 'transferencia']
    │
    └── Reserva::registrarPago($idReserva, $metodo, $total)
            ├── BEGIN TRANSACTION
            ├── INSERT INTO pago (fecha_pago, metodo_pago, valor_pagado, id_reserva)
            ├── UPDATE reserva SET estado = 'confirmada' WHERE id_reserva = ?
            └── COMMIT
                    └── Flash "✅ Pago registrado exitosamente"
                            └── Redirige a ?action=misReservas
```

---

## Botón "Después"

- Redirige a `?action=misReservas` sin registrar el pago
- La reserva permanece en estado `pendiente`
- El cliente puede pagar más tarde desde "Mis Reservas"

---

## Seguridad

- Verifica que `$reserva['id_cliente'] == $_SESSION['usuario_id']`
- Si no coincide → `die("No autorizado")`
- Evita que un cliente pague la reserva de otro

---

## Variables que recibe la vista

| Variable | Origen | Descripción |
|----------|--------|-------------|
| `$reserva` | `Reserva::buscarPorId()` | Datos de la reserva (fecha, hora, estado) |
| `$detalles` | Consulta directa | Array de servicios con nombre y precio |
| `$total` | `Reserva::obtenerTotal()` | Suma de subtotales en `detalle_servicio` |

---

## Tablas de base de datos involucradas

| Tabla | Operación |
|-------|-----------|
| `reserva` | `SELECT` para cargar datos + `UPDATE` estado al confirmar |
| `detalle_servicio` | `SELECT` para listar servicios y calcular total |
| `servicio` | `SELECT` para obtener nombres |
| `pago` | `INSERT` al confirmar el pago |
