# Layout: Chatbot Widget

**Archivo:** `views/layouts/chatbot.php`  
**Tipo:** Componente incluido automáticamente en el footer  
**Backend:** `controllers/ChatbotController.php`  
**Endpoint:** `POST ?action=chatbot`

---

## ¿Qué hace este componente?

Agrega un botón flotante 💬 en la esquina inferior derecha de **todas las páginas** del sistema. Al hacer clic abre una ventana de chat donde el usuario puede hacer preguntas sobre el salón.

---

## Estructura visual

```
┌─────────────────────────────┐
│ 💅 Aleja-Nails Asistente    │  ← Header con estado "En línea"
│                    [✕]      │
├─────────────────────────────┤
│                             │
│  [Burbuja bot]              │  ← Área de mensajes
│              [Burbuja user] │
│                             │
├─────────────────────────────┤
│ [💅] [📅] [🕐] [💳] [📞] [📍] │  ← Sugerencias rápidas
├─────────────────────────────┤
│ [Escribe tu pregunta...] [➤]│  ← Input de texto
└─────────────────────────────┘
```

---

## Botón flotante

- Posición: fija, esquina inferior derecha (28px del borde)
- Tamaño: 58×58px, circular
- Color: degradado rosado `#e8527a → #c93060`
- Animación: pulso continuo para llamar la atención
- Badge rojo: aparece a los 3 segundos si el chat no se ha abierto

---

## Sugerencias rápidas

Botones predefinidos que envían mensajes automáticamente:

| Botón | Mensaje enviado |
|-------|----------------|
| 💅 Servicios | `"Servicios y precios"` |
| 📅 Agendar | `"Agendar cita"` |
| 🕐 Horarios | `"Horarios"` |
| 💳 Pagos | `"Métodos de pago"` |
| 📞 Contacto | `"Teléfono y contacto"` |
| 📍 Ubicación | `"Dónde están ubicados"` |

---

## Flujo de comunicación

```
Usuario escribe mensaje
        │
        ▼
fetch() POST → ?action=chatbot
        │       Body: { "mensaje": "texto" }
        │
        ▼
ChatbotController::responder()
        │
        ├── Detecta palabras clave en el mensaje
        ├── Consulta BD si pregunta por servicios/precios
        └── Retorna JSON: { "respuesta": "texto HTML" }
                │
                ▼
        Muestra burbuja de respuesta en el chat
```

---

## Indicador de escritura

Mientras espera la respuesta del servidor, muestra tres puntos animados (●●●) para simular que el bot está "escribiendo". El delay es de 600–1000ms para mayor naturalidad.

---

## Temas que responde el chatbot

| Palabras clave | Respuesta |
|----------------|-----------|
| hola, buenas | Saludo personalizado |
| servicio, precio, cuánto | Lista de servicios desde la BD |
| manicure, pedicure, capilar | Servicios de esa categoría |
| agendar, reservar, cita | Pasos + link al formulario |
| pago, transferencia, nequi | Métodos de pago con número |
| horario, hora, atienden | Horarios de atención |
| ubicación, dónde, dirección | Dirección + WhatsApp |
| teléfono, contacto | Número con link a WhatsApp |
| cancelar | Instrucciones para cancelar |
| registro, crear cuenta | Link al formulario de registro |
| ayuda | Menú de opciones |

---

## Datos de contacto configurables

En `ChatbotController.php` se definen como constantes:

```php
private const TEL_DISPLAY  = '304 408 5465';
private const TEL_WA       = '57 3044085465';   // Formato internacional sin +
private const NEQUI        = '304 408 5465';
private const DAVIPLATA    = '304 408 5465';
private const TITULAR      = 'Alejandra Vanegas';
```

Para actualizar los datos bancarios o de contacto del salón, basta con editar estas constantes en el controlador del Chatbot.
