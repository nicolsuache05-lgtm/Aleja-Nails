# Vista: Catálogo (versión anterior)

**Archivo:** `views/dashboard/catalogo.php`  
**Estado:** Vista de referencia / versión inicial del agendamiento  

---

## ¿Qué hace esta vista?

Era la vista original de agendamiento de citas. Permitía al cliente seleccionar un solo servicio, elegir técnica, fecha y hora, y ver el historial de citas.

> Esta vista fue reemplazada por `views/usuarios/agendar.php`, que soporta **selección múltiple de servicios** y cálculo de total en tiempo real.

---

## Diferencias con la vista actual (`agendar.php`)

| Característica | `catalogo.php` (anterior) | `agendar.php` (actual) |
|----------------|--------------------------|------------------------|
| Selección de servicios | Un solo servicio (`<select>`) | Múltiples servicios (tarjetas con checkbox) |
| Total en tiempo real | ❌ No | ✅ Sí |
| Selección de técnica | ✅ Sí (hardcodeada) | ❌ No (asignación futura) |
| Notas adicionales | ✅ Sí | ❌ No |
| Categorías con tabs | ❌ No | ✅ Sí |
| Resumen visual | ❌ No | ✅ Sí |

---

## Campos del formulario (versión anterior)

| Campo | Tipo | Descripción |
|-------|------|-------------|
| `id_servicio` | `<select>` | Un servicio del catálogo |
| `tecnica` | `<select>` | Alejandra / Valentina / Daniela |
| `fecha` | `date` | Fecha de la cita |
| `hora` | `<select>` | Horario disponible |
| `notas` | `textarea` | Observaciones adicionales |

---

## Nota

Esta vista se conserva en el proyecto como referencia histórica del desarrollo. No está activa en el router principal.
