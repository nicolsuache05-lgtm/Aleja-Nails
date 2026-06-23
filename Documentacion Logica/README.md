# Documentación Lógica — Aleja-Nails

Bienvenido a la documentación lógica de **Aleja-Nails**, un sistema web para la gestión de citas y catálogo de servicios de un salón de belleza. 

Este documento sirve como **punto de entrada** para desarrolladores. Aquí se detalla la arquitectura de software del sistema, los flujos lógicos clave, la integración con la base de datos y un índice completo con accesos a la especificación detallada de cada vista y componente del proyecto.

---

## 🏗️ Arquitectura del Sistema (MVC)

El sistema está desarrollado en PHP puro sin frameworks pesados, siguiendo un patrón limpio de **Modelo-Vista-Controlador (MVC)** y utilizando un **Front Controller** como enrutador de peticiones.

```
       Solicitud HTTP (e.g. ?action=agendarCita)
                          │
                          ▼
                  public/index.php (Router / Front Controller)
                          │
                          ▼
            Controller correspondiente (e.g. UsuarioController)
              ├── Valida sesión y roles
              ├── Interactúa con el Modelo (e.g. Reserva)
              └── Carga la Vista correspondiente (e.g. agendar.php)
                          │
             ┌────────────┴────────────┐
             ▼                         ▼
      Modelos (PDO/SQL)          Vistas (HTML5 + CSS + JS)
  [Administrador, Cliente,     [layouts/, usuarios/, dashboard/,
   Reserva, Servicio]           admin/]
```

### Flujo de Enrutamiento (`public/index.php`)
Todas las solicitudes son recibidas por `public/index.php`. Mediante el parámetro `?action=` de la URL se invoca el controlador y método correspondiente. 

---

## 📂 Mapa de Documentación Lógica

La documentación técnica detallada está organizada en cuatro módulos principales. Haz clic en cualquiera de los enlaces a continuación para ver su lógica interna:

### 1. Componentes Globales (Layouts)
Elementos comunes e interfaces globales del sistema que se reutilizan en todas las pantallas.

| Componente | Archivo de Código | Enlace a Documentación Lógica |
|:---|:---|:---|
| **Estructura y Estilos** | `views/layouts/header.php` | [Header.md](file:///C:/laragon/www/Mi-proyecto-formativo/Documentacion%20Logica/Layouts/Header.md) |
| **Menú de Navegación** | `views/layouts/sidebar.php` | [Sidebar.md](file:///C:/laragon/www/Mi-proyecto-formativo/Documentacion%20Logica/Layouts/Sidebar.md) |
| **Pie de Página** | `views/layouts/footer.php` | [Footer.md](file:///C:/laragon/www/Mi-proyecto-formativo/Documentacion%20Logica/Layouts/Footer.md) |
| **Asistente Virtual** | `views/layouts/chatbot.php` | [Chatbot.md](file:///C:/laragon/www/Mi-proyecto-formativo/Documentacion%20Logica/Layouts/Chatbot.md) |

### 2. Módulo de Usuarios (Clientes)
Vistas del flujo del cliente para el control de su cuenta y reservas de citas.

| Pantalla | Archivo de Código | Enlace a Documentación Lógica |
|:---|:---|:---|
| **Registro de Cuentas** | `views/usuarios/registro.php` | [Registro.md](file:///C:/laragon/www/Mi-proyecto-formativo/Documentacion%20Logica/Usuarios/Registro.md) |
| **Inicio de Sesión** | `views/usuarios/login.php` | [Login.md](file:///C:/laragon/www/Mi-proyecto-formativo/Documentacion%20Logica/Usuarios/Login.md) |
| **Agendar Nueva Cita** | `views/usuarios/agendar.php` | [Agendar.md](file:///C:/laragon/www/Mi-proyecto-formativo/Documentacion%20Logica/Usuarios/Agendar.md) |
| **Pantalla de Pago** | `views/usuarios/pagar.php` | [Pagar.md](file:///C:/laragon/www/Mi-proyecto-formativo/Documentacion%20Logica/Usuarios/Pagar.md) |
| **Historial de Reservas** | `views/usuarios/mis-reservas.php` | [Mis Reservas.md](file:///C:/laragon/www/Mi-proyecto-formativo/Documentacion%20Logica/Usuarios/Mis%20Reservas.md) |

### 3. Paneles de Dashboard
Pantallas de inicio que resumen las opciones generales según el tipo de usuario.

| Dashboard | Archivo de Código | Enlace a Documentación Lógica |
|:---|:---|:---|
| **Panel de Cliente** | `views/dashboard/cliente.php` | [Cliente.md](file:///C:/laragon/www/Mi-proyecto-formativo/Documentacion%20Logica/Dashboard/Cliente.md) |
| **Catálogo Público** | `views/dashboard/catalogo.php` | [Catalogo.md](file:///C:/laragon/www/Mi-proyecto-formativo/Documentacion%20Logica/Dashboard/Catalogo.md) |
| **Edición de Perfil** | `views/dashboard/usuario_editar.php` | [Usuario_Editar.md](file:///C:/laragon/www/Mi-proyecto-formativo/Documentacion%20Logica/Dashboard/Usuario_Editar.md) |
| **Panel de Administrador** | `views/dashboard/admin.php` | [Admin.md](file:///C:/laragon/www/Mi-proyecto-formativo/Documentacion%20Logica/Dashboard/Admin.md) |

### 4. Módulo de Administración (Panel de Gestión)
Vistas internas exclusivas del administrador para el control del negocio.

| Gestión | Archivo de Código | Enlace a Documentación Lógica |
|:---|:---|:---|
| **Control de Citas** | `views/admin/reservas.php` | [Reservas.md](file:///C:/laragon/www/Mi-proyecto-formativo/Documentacion%20Logica/Admin/Reservas.md) |
| **Control de Clientes** | `views/admin/clientes.php` | [Clientes.md](file:///C:/laragon/www/Mi-proyecto-formativo/Documentacion%20Logica/Admin/Clientes.md) |
| **Catálogo Editable** | `views/admin/servicios.php` | [Servicios.md](file:///C:/laragon/www/Mi-proyecto-formativo/Documentacion%20Logica/Admin/Servicios.md) |
| **Historial de Pagos** | `views/admin/pagos.php` | [Pagos.md](file:///C:/laragon/www/Mi-proyecto-formativo/Documentacion%20Logica/Admin/Pagos.md) |

---

## 🌟 Información y Mejoras Recientes del Proyecto

El sistema ha recibido un rediseño completo de interfaz, lógica y seguridad en su última versión 1.1. A continuación, se detallan las mejoras implementadas y su impacto lógico:

### 1. Gestión de Reservas Multiservicio (Relación Muchos a Muchos)
- **Mejora:** Anteriormente, una cita solo se podía agendar para un único servicio. Ahora el sistema permite agrupar múltiples servicios en una sola reserva.
- **Implementación Lógica:**
  - Se introdujo la tabla asociativa `detalle_servicio` vinculada mediante clave foránea a `reserva` y `servicio`.
  - El modelo [Reserva](file:///C:/laragon/www/Mi-proyecto-formativo/models/Reserva.php) maneja transacciones PDO completas (`beginTransaction()`, `commit()`, `rollBack()`) para insertar en `reserva` y luego realizar un bucle de inserción en `detalle_servicio` de forma atómica.
  - Se incorporó soporte de *fallback* para reservas históricas que apuntaban directamente a un único servicio.

### 2. Panel de Visualización y Cards Interactivos
- **Mejora:** Transiciones suaves, micro-animaciones en hover para las tarjetas de servicios y una grilla adaptativa.
- **Implementación Lógica:**
  - Se definieron variables globales en CSS `:root` para tipografías, colores de acento rosa y sombras.
  - Las tarjetas aumentan su tamaño ligeramente y proyectan sombras más notorias en el navegador mediante Javascript dinámico y efectos CSS (`onmouseover`/`onmouseout` con transiciones de `transform` y `box-shadow`).

### 3. Sistema Dinámico de Pago con Copiado Directo
- **Mejora:** Integración de cuentas de transferencia (Nequi/Daviplata) con botones que copian automáticamente al portapapeles.
- **Implementación Lógica:**
  - En [pagar.php](file:///C:/laragon/www/Mi-proyecto-formativo/views/usuarios/pagar.php), Javascript controla la visibilidad del bloque de transferencia utilizando la función `seleccionarMetodo(metodo)`.
  - Se implementó la API del portapapeles `navigator.clipboard.writeText()` para copiar los números sin esfuerzo. El botón de copia realiza una transición de estado temporal mostrando "✅ Copiado" por dos segundos antes de restaurar su estado original.

### 4. Chatbot Autónomo Conectado a la Base de Datos
- **Mejora:** El chatbot responde preguntas del usuario en tiempo real con datos dinámicos extraídos de la BD (como nombres y precios de servicios activos).
- **Implementación Lógica:**
  - [ChatbotController](file:///C:/laragon/www/Mi-proyecto-formativo/controllers/ChatbotController.php) procesa solicitudes enviadas vía `fetch()` en formato JSON.
  - Si el usuario consulta sobre "servicios" o categorías específicas (como capilar o manicure), el chatbot consulta dinámicamente la tabla `servicio` mediante PDO y devuelve la lista formateada en HTML para renderizar dentro de la burbuja del chat.
  - La información de contacto y cuentas del chatbot se centraliza en constantes de clase PHP (`TEL_DISPLAY`, `TEL_WA`, `NEQUI`, `DAVIPLATA`, `TITULAR`) facilitando su mantenimiento a futuro.
