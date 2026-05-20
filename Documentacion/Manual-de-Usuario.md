# Manual de Usuario — Aleja-Nails

**Sistema de Gestión de Salón de Belleza**  
**Versión:** 1.0 · Mayo 2026  

---

## Contenido

- [Para Clientes](#para-clientes)
- [Para Administradores](#para-administradores)
- [Chatbot de Ayuda](#chatbot-de-ayuda)
- [Preguntas Frecuentes](#preguntas-frecuentes)

---

# Para Clientes

## 1. Crear una cuenta

1. Ingresa a la página principal del sistema
2. Haz clic en el botón **"Registrarse"**
3. Completa el formulario:
   - **Nombre completo** *(obligatorio)*
   - **Teléfono**
   - **Correo electrónico** *(obligatorio — será tu usuario)*
   - **Contraseña** *(mínimo 8 caracteres)*
   - **Confirmar contraseña**
4. Haz clic en **"Registrarme"**
5. Serás redirigida al login con el mensaje *"Registro exitoso"*

> ⚠️ El correo electrónico debe ser único. Si ya existe una cuenta con ese correo, verás un mensaje de error.

---

## 2. Iniciar sesión

1. Haz clic en **"Iniciar Sesión"** en la página principal
2. Ingresa tu **correo** y **contraseña**
3. Haz clic en **"Ingresar"**
4. Serás redirigida a tu panel personal

---

## 3. Panel principal (Dashboard)

Al iniciar sesión verás tu panel con:

- **Contador de reservas** — total, pendientes y completadas
- **Accesos rápidos** — Agendar nueva cita / Ver mis reservas
- **Últimas 5 reservas** con estado y precio
- **Catálogo de servicios** disponibles

---

## 4. Agendar una cita

### Paso 1 — Seleccionar servicios

1. Haz clic en **"Agendar nueva cita"**
2. Verás los servicios organizados por categorías:
   - 💅 Manicure
   - 👣 Pedicure
   - 💆🏽‍♀️ Capilar
3. Haz clic en las **pestañas** para cambiar de categoría
4. Haz clic en las **tarjetas** de los servicios que deseas
   - La tarjeta se resalta en rosado con un ✓ al seleccionarse
   - Puedes seleccionar **varios servicios** a la vez, incluso de diferentes categorías

### Paso 2 — Ver el total

Al seleccionar servicios aparece automáticamente el **panel de resumen** con:
- Lista de servicios elegidos con precio individual
- **Total acumulado** que se actualiza en tiempo real

### Paso 3 — Elegir fecha y hora

- Selecciona la **fecha** (no se permiten fechas pasadas)
- Selecciona la **hora** disponible (intervalos de 30 minutos, de 8:00 am a 7:00 pm)

### Paso 4 — Confirmar

- Haz clic en **"Confirmar reserva"**
- Si el horario ya está ocupado, verás un mensaje de error y podrás elegir otro
- Si todo está bien, serás redirigida a la pantalla de pago

---

## 5. Realizar el pago

Después de confirmar la reserva llegarás a la pantalla de pago:

### Resumen de la reserva (lado izquierdo)
- Fecha y hora de la cita
- Lista de servicios con precio individual
- **Total a pagar** destacado en rosado

### Método de pago (lado derecho)

**Opción 1 — Efectivo 💵**
- Selecciona esta opción si pagarás en el salón el día de tu cita
- Haz clic en **"Confirmar pago"**

**Opción 2 — Transferencia bancaria 🏦**
- Selecciona esta opción para pagar por Nequi o Daviplata
- Se desplegará automáticamente el panel con los datos:
  - 💜 **Nequi:** 300 123 4567
  - ❤️ **Daviplata:** 300 123 4567
  - 👤 **Titular:** Alejandra García
- Usa el botón **"📋 Copiar"** para copiar el número al portapapeles
- Realiza la transferencia desde tu app
- Envía el comprobante por WhatsApp al número indicado
- Haz clic en **"Confirmar pago"**

> 💡 Si no puedes pagar en este momento, haz clic en **"Después"** para pagar más tarde desde "Mis Reservas".

---

## 6. Ver mis reservas

En la sección **"Mis Reservas"** puedes ver:

| Columna | Descripción |
|---------|-------------|
| # | Número de la reserva |
| Servicios | Lista de servicios (con precio individual si son varios) |
| Fecha | Fecha de la cita |
| Hora | Hora de la cita |
| Estado | Estado actual de la reserva |
| Total | Suma de todos los servicios |
| Acciones | Botones disponibles según el estado |

### Estados de una reserva

| Estado | Significado | Acciones disponibles |
|--------|-------------|---------------------|
| 🟡 Pendiente | Creada, sin pago | 💳 Pagar · Cancelar |
| 🌸 Confirmada | Pago registrado | — |
| 🔵 En curso | Cita en progreso | — |
| 🟢 Completada | Servicio finalizado | — |
| ⚫ Cancelada | Cancelada | — |

---

## 7. Cancelar una reserva

Solo puedes cancelar reservas en estado **Pendiente**:

1. Ve a **"Mis Reservas"**
2. Busca la reserva que deseas cancelar
3. Haz clic en el botón **"Cancelar"**
4. Confirma en el diálogo que aparece
5. La reserva cambiará a estado *Cancelada*

---

## 8. Cerrar sesión

Haz clic en **"Cerrar sesión"** en la barra superior o en el menú lateral.

---

---

# Para Administradores

## 1. Iniciar sesión como administrador

1. Ve a la página de inicio
2. Haz clic en **"Iniciar Sesión"**
3. Ingresa el **usuario** y **contraseña** de administrador
4. Serás redirigido al **Panel de Administración**

---

## 2. Panel de Administración (Dashboard)

El panel muestra 4 contadores en tiempo real:
- 👥 **Total clientes** registrados
- 📅 **Total reservas** en el sistema
- 💰 **Total pagos** registrados
- 💅 **Servicios** en el catálogo

Y accesos rápidos a cada sección.

---

## 3. Gestionar Reservas

En **"Reservas"** verás todas las citas del sistema con:
- Número, cliente, servicios, total, fecha, hora y estado actual

### Cambiar el estado de una reserva

1. Busca la reserva en la tabla
2. En la columna **"Cambiar estado"**, selecciona el nuevo estado en el menú desplegable:
   - Pendiente → Confirmada → En curso → Completada → Cancelada
3. Haz clic en **"Guardar"**

---

## 4. Gestionar Clientes

En **"Clientes"** verás la lista de todos los clientes con nombre, correo, teléfono y estado.

### Activar / Desactivar un cliente

- Haz clic en **"Desactivar"** para bloquear el acceso de un cliente
- Haz clic en **"Activar"** para restaurar el acceso
- Un cliente desactivado no podrá iniciar sesión

---

## 5. Gestionar Servicios

En **"Servicios"** verás el catálogo organizado por categorías (Manicure, Pedicure, Capilar).

### Editar un servicio

1. Modifica directamente el campo de **descripción** o **precio** en la fila del servicio
2. Haz clic en **"Guardar"**
3. Los cambios se reflejan inmediatamente en el catálogo de clientes

### Eliminar un servicio

1. Haz clic en **"Eliminar"** en la fila del servicio
2. Confirma en el diálogo
3. El servicio se elimina permanentemente

> ⚠️ No se puede eliminar un servicio que tenga reservas asociadas (restricción de clave foránea en BD).

---

## 6. Ver Pagos

En **"Pagos"** verás el historial completo con:
- Número de pago
- Cliente
- Servicio
- Fecha del pago
- Método (efectivo / transferencia)
- Valor pagado

---

---

# Chatbot de Ayuda

El chatbot está disponible en **todas las páginas** como un botón flotante 💬 en la esquina inferior derecha.

## Cómo usarlo

1. Haz clic en el botón **💬**
2. Escribe tu pregunta en el campo de texto, o
3. Usa los **botones de sugerencias rápidas**:
   - 💅 Servicios
   - 📅 Agendar
   - 🕐 Horarios
   - 💳 Pagos
   - 📞 Contacto
   - 📍 Ubicación

## Ejemplos de preguntas

- *"¿Cuáles son los servicios de manicure?"*
- *"¿Cuánto cuesta la keratina?"*
- *"¿Cómo agendo una cita?"*
- *"¿Cuál es el número de Nequi?"*
- *"¿A qué hora atienden los sábados?"*
- *"¿Dónde están ubicados?"*

---

---

# Preguntas Frecuentes

**¿Puedo agendar varios servicios en una sola cita?**  
Sí. En la pantalla de agendamiento puedes seleccionar múltiples servicios haciendo clic en varias tarjetas. El sistema suma los precios automáticamente.

**¿Puedo pagar después de confirmar la reserva?**  
Sí. En la pantalla de pago puedes hacer clic en "Después" y pagar más tarde desde "Mis Reservas" usando el botón 💳 Pagar.

**¿Cómo sé que mi pago fue registrado?**  
La reserva cambiará de estado "Pendiente" a "Confirmada" y verás el mensaje "✅ Pago registrado exitosamente".

**¿Puedo cancelar una cita ya pagada?**  
Solo se pueden cancelar reservas en estado "Pendiente". Una vez confirmada, contacta al salón directamente.

**¿Qué pasa si el horario que quiero ya está ocupado?**  
El sistema te mostrará un mensaje de error. Elige otra hora o fecha disponible.

**¿Cómo contacto al salón?**  
Puedes escribir al chatbot "teléfono" o "contacto" para obtener el número de WhatsApp del salón.

---

*Manual de Usuario — Aleja-Nails · 2026*
