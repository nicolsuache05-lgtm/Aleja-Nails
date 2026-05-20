# Aleja-Nails — Sistema de Gestión de Salón de Belleza

**Versión:** 1.0  
**Tecnología:** PHP 8.2 · MariaDB 10.4 · Laragon  
**Autor:** Proyecto Formativo SENA  

---

## Tabla de Contenidos

1. [Descripción General](#1-descripción-general)
2. [Requisitos del Sistema](#2-requisitos-del-sistema)
3. [Instalación y Configuración](#3-instalación-y-configuración)
4. [Estructura del Proyecto](#4-estructura-del-proyecto)
5. [Base de Datos](#5-base-de-datos)
6. [Arquitectura MVC](#6-arquitectura-mvc)
7. [Módulos del Sistema](#7-módulos-del-sistema)
8. [Flujos Principales](#8-flujos-principales)
9. [Roles y Permisos](#9-roles-y-permisos)
10. [Chatbot](#10-chatbot)
11. [Seguridad](#11-seguridad)
12. [Guía de Uso](#12-guía-de-uso)

---

## 1. Descripción General

**Aleja-Nails** es un sistema web de gestión para un salón de belleza. Permite a los clientes registrarse, consultar servicios, agendar citas con múltiples servicios, ver el total a pagar y realizar el pago en línea. Los administradores gestionan reservas, clientes, servicios y pagos desde un panel dedicado.

### Funcionalidades principales

| Módulo | Cliente | Administrador |
|--------|---------|---------------|
| Registro e inicio de sesión | ✅ | ✅ |
| Ver catálogo de servicios | ✅ | ✅ |
| Agendar cita (múltiples servicios) | ✅ | — |
| Ver total de servicios en tiempo real | ✅ | — |
| Realizar pago (efectivo / transferencia) | ✅ | — |
| Ver y cancelar mis reservas | ✅ | — |
| Gestionar clientes | — | ✅ |
| Gestionar reservas y estados | — | ✅ |
| Gestionar servicios y precios | — | ✅ |
| Ver historial de pagos | — | ✅ |
| Chatbot de atención | ✅ | ✅ |

---

## 2. Requisitos del Sistema

- **PHP** 8.1 o superior
- **MariaDB** 10.4 o superior (o MySQL 8+)
- **Laragon** (recomendado) o XAMPP / WAMP
- Navegador moderno (Chrome, Firefox, Edge)
- Conexión a internet (para cargar Tailwind CSS y Google Fonts desde CDN)

---

## 3. Instalación y Configuración

### 3.1 Clonar o copiar el proyecto

Colocar la carpeta `Mi-proyecto-formativo` dentro de:
```
C:\laragon\www\
```

### 3.2 Crear la base de datos

1. Abrir **phpMyAdmin** en `http://localhost/phpmyadmin`
2. Crear una base de datos llamada `aleja-nails`
3. Importar el archivo:
   ```
   Mi-proyecto-formativo/sql/aleja-nails (1).sql
   ```

### 3.3 Configurar la conexión

Editar `config/database.php`:

```php
private static string $host   = "127.0.0.1";
private static int    $port   = 3320;      // Puerto de Laragon (3306 en XAMPP)
private static string $dbname = "aleja-nails";
private static string $user   = "root";
private static string $pass   = "";
```

> **Nota:** Laragon usa el puerto **3320** por defecto. XAMPP usa **3306**.

### 3.4 Acceder al sistema

```
http://localhost/Mi-proyecto-formativo/public/index.php
```

### 3.5 Crear administrador

Insertar manualmente en la tabla `administrador`:

```sql
INSERT INTO administrador (nombre, usuario, contraseña)
VALUES ('Admin', 'admin', '12345678');
```

---

## 4. Estructura del Proyecto

```
Mi-proyecto-formativo/
│
├── config/
│   └── database.php              # Conexión PDO (singleton)
│
├── controllers/
│   ├── AuthController.php        # Login, registro, logout
│   ├── UsuarioController.php     # Dashboard cliente, agendar, pagar, reservas
│   ├── AdminUsuarioController.php# Panel admin, CRUD servicios/clientes/reservas
│   └── ChatbotController.php     # Respuestas automáticas del chatbot
│
├── models/
│   ├── Administrador.php         # Tabla administrador
│   ├── Cliente.php               # Tabla cliente
│   ├── Reserva.php               # Tablas reserva + detalle_servicio + pago
│   ├── Servicio.php              # Tabla servicio
│   └── Usuario.php               # Modelo genérico (no usado activamente)
│
├── views/
│   ├── layouts/
│   │   ├── header.php            # HTML head + topbar + estilos globales
│   │   ├── sidebar.php           # Menú lateral (admin / cliente)
│   │   ├── footer.php            # Cierre HTML + chatbot
│   │   └── chatbot.php           # Widget flotante del chatbot
│   │
│   ├── usuarios/
│   │   ├── login.php             # Formulario de inicio de sesión
│   │   ├── registro.php          # Formulario de registro de cliente
│   │   ├── agendar.php           # Selección de servicios + fecha/hora
│   │   ├── mis-reservas.php      # Listado de reservas del cliente
│   │   └── pagar.php             # Pantalla de pago de reserva
│   │
│   ├── dashboard/
│   │   ├── admin.php             # Panel principal del administrador
│   │   ├── cliente.php           # Panel principal del cliente
│   │   ├── catalogo.php          # Catálogo público de servicios
│   │   └── usuario_editar.php    # Edición de perfil
│   │
│   └── admin/
│       ├── clientes.php          # Gestión de clientes
│       ├── reservas.php          # Gestión de reservas
│       ├── servicios.php         # Gestión de servicios y precios
│       └── pagos.php             # Historial de pagos
│
├── public/
│   └── index.php                 # Router principal (front controller)
│
├── css/
│   └── estilos.css               # Estilos adicionales
│
├── img/                          # Imágenes del sistema
├── sql/                          # Script SQL de la base de datos
├── Documentacion/                # Esta documentación
└── README.md
```

---

## 5. Base de Datos

### Diagrama de tablas

```
administrador          cliente
─────────────          ───────────────────
id_administrador PK    id_cliente PK
nombre                 nombre
usuario                telefono
contraseña             correo
correo                 password (hash)
                       activo (0/1)

servicio               empleados
────────────────       ──────────────────
id_servicio PK         id_empleados PK
nombre_servicio        nombre
descripcion            telefono
precio                 id_administrador FK
categoria
id_administrador FK

reserva                detalle_servicio
───────────────        ─────────────────────────
id_reserva PK          id_detalle_servicio PK
fecha                  cantidad
hora                   precio_unitario
estado                 subtotal
id_cliente FK          id_reserva FK
id_servicio FK         id_servicio FK
id_empleados FK

pago
────────────────
id_pago PK
fecha_pago
metodo_pago
valor_pagado
id_reserva FK
```

### Descripción de tablas

| Tabla | Descripción |
|-------|-------------|
| `administrador` | Usuarios con rol de administrador del sistema |
| `cliente` | Clientes registrados que pueden agendar citas |
| `servicio` | Catálogo de servicios con precio y categoría |
| `empleados` | Personal del salón (asignación futura) |
| `reserva` | Citas agendadas por los clientes |
| `detalle_servicio` | Servicios incluidos en cada reserva (relación N:N) |
| `pago` | Registro de pagos realizados por reserva |

### Estados de una reserva

| Estado | Descripción |
|--------|-------------|
| `pendiente` | Recién creada, sin confirmar |
| `confirmada` | Pago registrado |
| `en_curso` | Cita en progreso |
| `completada` | Servicio finalizado |
| `cancelada` | Cancelada por el cliente o admin |

### Categorías de servicios

- **Manicure** — Servicios de uñas de manos
- **Pedicure** — Servicios de uñas de pies
- **Capilar** — Tratamientos de cabello
- **Otros** — Servicios adicionales

---

## 6. Arquitectura MVC

El sistema sigue el patrón **Modelo-Vista-Controlador (MVC)**:

```
Solicitud HTTP
     │
     ▼
public/index.php  ←── Router (Front Controller)
     │
     ▼
Controller  ──────► Model  ──────► Base de Datos
     │                │
     │                └──────────► Datos
     │
     ▼
   View  ──────────────────────────► HTML al navegador
```

### Router (`public/index.php`)

Todas las peticiones pasan por este archivo. El parámetro `?action=` determina qué controlador y método se ejecuta:

| action | Controlador | Método |
|--------|-------------|--------|
| `inicio` | — | Página de inicio pública |
| `login` | AuthController | login() |
| `procesarLogin` | AuthController | procesarLogin() |
| `registro` | AuthController | mostrarRegistro() |
| `procesarRegistro` | AuthController | procesarRegistro() |
| `logout` | AuthController | logout() |
| `dashboard` | UsuarioController | dashboard() |
| `agendarCita` | UsuarioController | agendarCita() |
| `misReservas` | UsuarioController | misReservas() |
| `cancelarReserva` | UsuarioController | cancelarReserva() |
| `pagar` | UsuarioController | pagar() |
| `chatbot` | ChatbotController | responder() |
| `adminPanel` | AdminUsuarioController | dashboard() |
| `listarClientes` | AdminUsuarioController | listarClientes() |
| `toggleCliente` | AdminUsuarioController | toggleCliente() |
| `listarReservas` | AdminUsuarioController | listarReservas() |
| `actualizarReserva` | AdminUsuarioController | actualizarReserva() |
| `listarServicios` | AdminUsuarioController | listarServicios() |
| `actualizarServicio` | AdminUsuarioController | actualizarServicio() |
| `eliminarServicio` | AdminUsuarioController | eliminarServicio() |
| `verPagos` | AdminUsuarioController | verPagos() |

---

## 7. Módulos del Sistema

### 7.1 Autenticación (`AuthController`)

**Métodos:**

| Método | HTTP | Descripción |
|--------|------|-------------|
| `login()` | GET | Muestra el formulario de login |
| `procesarLogin()` | POST | Valida credenciales y crea sesión |
| `mostrarRegistro()` | GET | Muestra el formulario de registro |
| `procesarRegistro()` | POST | Crea nuevo cliente con validaciones |
| `logout()` | GET | Destruye la sesión y redirige al inicio |

**Validaciones en registro:**
- Nombre, correo y contraseña son obligatorios
- Correo con formato válido (`filter_var`)
- Contraseña mínimo 8 caracteres
- Confirmación de contraseña coincidente
- Correo no duplicado en BD

**Variables de sesión creadas al login:**

```php
$_SESSION['usuario_id']  // ID del cliente o admin
$_SESSION['nombre']      // Nombre para mostrar
$_SESSION['rol']         // 'admin' o 'cliente'
$_SESSION['correo']      // Solo para clientes
```

---

### 7.2 Módulo Cliente (`UsuarioController`)

**Métodos:**

| Método | HTTP | Descripción |
|--------|------|-------------|
| `dashboard()` | GET | Panel principal con estadísticas y últimas reservas |
| `agendarCita()` | GET/POST | Formulario de agendamiento con selección múltiple |
| `misReservas()` | GET | Listado completo de reservas del cliente |
| `cancelarReserva()` | GET | Cancela una reserva pendiente |
| `pagar()` | GET/POST | Pantalla de pago y registro del pago |

**Flujo de agendamiento:**
1. GET: carga servicios agrupados por categoría
2. Cliente selecciona uno o varios servicios (checkboxes)
3. JS calcula el total en tiempo real
4. Cliente elige fecha y hora
5. POST: valida datos, consulta precios en BD, crea reserva + detalles
6. Redirige a pantalla de pago

---

### 7.3 Módulo Administrador (`AdminUsuarioController`)

**Métodos:**

| Método | HTTP | Descripción |
|--------|------|-------------|
| `dashboard()` | GET | Panel con contadores: clientes, reservas, pagos, servicios |
| `listarClientes()` | GET | Tabla de todos los clientes con estado activo/inactivo |
| `toggleCliente()` | GET | Activa o desactiva un cliente |
| `listarReservas()` | GET | Todas las reservas con opción de cambiar estado |
| `actualizarReserva()` | POST | Cambia el estado de una reserva |
| `listarServicios()` | GET | Catálogo editable de servicios por categoría |
| `actualizarServicio()` | POST | Edita descripción y precio de un servicio |
| `eliminarServicio()` | POST | Elimina un servicio del catálogo |
| `verPagos()` | GET | Historial de todos los pagos registrados |

> Todos los métodos verifican que el usuario tenga rol `admin`. Si no, retornan HTTP 403.

---

### 7.4 Modelo Reserva (`Reserva`)

Modelo central del sistema. Gestiona reservas y su relación con múltiples servicios.

**Métodos:**

| Método | Descripción |
|--------|-------------|
| `crearConDetalles($datos, $servicios)` | Crea reserva + inserta en `detalle_servicio` (transacción) |
| `registrarPago($idReserva, $metodo, $valor)` | Inserta en `pago` y actualiza estado a `confirmada` |
| `obtenerPorCliente($id)` | Reservas del cliente con servicios y total calculado |
| `obtenerTodas()` | Todas las reservas con cliente y servicios (para admin) |
| `obtenerTotal($idReserva)` | Suma de subtotales en `detalle_servicio` |
| `existeConflicto($fecha, $hora)` | Verifica si el horario ya está ocupado |
| `buscarPorId($id)` | Busca una reserva por ID |
| `actualizarEstado($id, $estado)` | Cambia el estado de una reserva |

**Compatibilidad con reservas antiguas:** Si una reserva no tiene registros en `detalle_servicio`, el modelo hace fallback al `id_servicio` directo de la tabla `reserva`.

---

### 7.5 Modelo Servicio (`Servicio`)

| Método | Descripción |
|--------|-------------|
| `obtenerTodos()` | Todos los servicios ordenados por categoría. Agrega columna `categoria` si no existe |
| `obtenerAgrupados()` | Servicios agrupados por categoría como array asociativo |
| `buscarPorId($id)` | Busca un servicio por ID |
| `crear($datos)` | Crea un nuevo servicio |
| `editar($id, $datos)` | Edita descripción y precio |
| `eliminar($id)` | Elimina un servicio |

---

### 7.6 Modelo Cliente (`Cliente`)

| Método | Descripción |
|--------|-------------|
| `buscarPorCorreo($correo)` | Login: busca cliente por correo |
| `crear($datos)` | Registro: crea cliente con password hasheado |
| `obtenerTodos()` | Lista todos los clientes |
| `buscarPorId($id)` | Busca por ID |
| `cambiarEstado($id, $activo)` | Activa o desactiva un cliente |
| `correoExiste($correo, $id)` | Verifica duplicados de correo |

---

### 7.7 Modelo Administrador (`Administrador`)

| Método | Descripción |
|--------|-------------|
| `buscarPorUsuario($usuario)` | Busca admin por nombre de usuario o correo |
| `obtenerTodos()` | Lista todos los administradores |
| `buscarPorId($id)` | Busca por ID |

> **Nota de seguridad:** La contraseña del administrador se almacena en texto plano según la estructura original de la BD. Se recomienda migrar a `password_hash()` en versiones futuras.

---

## 8. Flujos Principales

### 8.1 Registro de cliente

```
Inicio → Registrarse → Formulario registro
→ Validar datos → Crear en BD → Flash "Registro exitoso"
→ Redirigir a Login
```

### 8.2 Login

```
Login → Ingresar correo + contraseña
→ Buscar en administrador → Si existe y contraseña coincide → Sesión admin → adminPanel
→ Buscar en cliente → Si existe y password_verify → Sesión cliente → dashboard
→ Si no coincide → Flash error → Login
```

### 8.3 Agendar cita con múltiples servicios

```
agendarCita (GET)
→ Cargar servicios agrupados por categoría
→ Mostrar tarjetas con checkboxes
→ Cliente selecciona servicios → JS suma precios en tiempo real
→ Cliente elige fecha y hora
→ Clic "Confirmar reserva" (POST)
→ Validar: al menos 1 servicio, fecha no pasada, horario libre
→ Consultar precios reales en BD
→ crearConDetalles() → INSERT reserva + INSERT detalle_servicio (transacción)
→ Redirigir a pagar?id={id_reserva}
```

### 8.4 Pago de reserva

```
pagar (GET)
→ Cargar reserva + detalles + total
→ Mostrar resumen + opciones de pago
→ Si elige Transferencia → mostrar datos Nequi/Daviplata con botón Copiar
→ Clic "Confirmar pago" (POST)
→ registrarPago() → INSERT pago + UPDATE reserva estado='confirmada'
→ Flash "Pago registrado" → misReservas
```

### 8.5 Gestión de reservas (Admin)

```
listarReservas → Ver todas las reservas con servicios y total
→ Seleccionar nuevo estado en dropdown
→ actualizarReserva (POST) → UPDATE reserva.estado
→ Redirigir a listarReservas
```

---

## 9. Roles y Permisos

### Cliente
- Accede a: `dashboard`, `agendarCita`, `misReservas`, `cancelarReserva`, `pagar`
- Solo puede ver y modificar sus propias reservas
- No puede acceder a rutas de admin (redirige a login)

### Administrador
- Accede a: `adminPanel`, `listarClientes`, `toggleCliente`, `listarReservas`, `actualizarReserva`, `listarServicios`, `actualizarServicio`, `eliminarServicio`, `verPagos`
- Verificación en cada método de `AdminUsuarioController` mediante `validarAdmin()`
- Si el rol no es `admin`, retorna HTTP 403

### Menú lateral según rol

**Cliente:**
- 🏠 Inicio
- 📒 Agendar cita
- 📅 Mis reservas
- 🚪 Salir

**Administrador:**
- 📊 Dashboard
- 📅 Reservas
- 👥 Clientes
- 💅 Servicios
- 💰 Pagos
- 🚪 Salir

---

## 10. Chatbot

El chatbot es un asistente virtual integrado que aparece en **todas las páginas** como un botón flotante rosado (💬) en la esquina inferior derecha.

### Arquitectura

- **Frontend:** `views/layouts/chatbot.php` — widget HTML/CSS/JS puro
- **Backend:** `controllers/ChatbotController.php` — endpoint PHP que responde JSON
- **Comunicación:** `fetch()` POST a `?action=chatbot` con `{ "mensaje": "texto" }`

### Temas que responde

| Pregunta del usuario | Respuesta |
|---------------------|-----------|
| Saludos | Bienvenida personalizada con nombre si está logueado |
| Servicios / precios | Consulta la BD en tiempo real y lista servicios por categoría |
| Manicure / Pedicure / Capilar | Servicios específicos de esa categoría con precios |
| Agendar / reservar | Pasos + link directo al formulario |
| Mis reservas | Link directo (si está logueado) |
| Cancelar reserva | Instrucciones paso a paso |
| Métodos de pago | Efectivo + Transferencia con número de contacto |
| Horarios | Lun–Vie 8am–7pm · Sáb 8am–6pm · Dom 9am–3pm |
| Ubicación | Dirección + número de WhatsApp |
| Teléfono / contacto | Número con link directo a WhatsApp |
| Registro / login | Instrucciones + links directos |
| Ayuda | Menú de opciones disponibles |

### Datos de contacto configurables

En `ChatbotController.php`:

```php
private const TEL_DISPLAY = '300 123 4567';   // Número visible
private const TEL_WA      = '573001234567';    // Formato WhatsApp
private const NEQUI       = '300 123 4567';    // Número Nequi
private const DAVIPLATA   = '300 123 4567';    // Número Daviplata
private const TITULAR     = 'Alejandra García'; // Titular de la cuenta
```

### Sugerencias rápidas

El widget incluye botones de acceso rápido:
- 💅 Servicios · 📅 Agendar · 🕐 Horarios · 💳 Pagos · 📞 Contacto · 📍 Ubicación

---

## 11. Seguridad

| Medida | Implementación |
|--------|----------------|
| Contraseñas hasheadas | `password_hash()` / `password_verify()` para clientes |
| Protección XSS | `htmlspecialchars()` en todas las salidas de datos |
| Consultas parametrizadas | PDO con `prepare()` + `execute()` en todos los modelos |
| Control de sesión | Verificación de `$_SESSION['usuario_id']` y `$_SESSION['rol']` en cada controlador |
| Validación de propiedad | El cliente solo puede cancelar/pagar sus propias reservas |
| Acceso admin | `validarAdmin()` verifica rol en cada método del AdminController |
| Transacciones BD | `crearConDetalles()` y `registrarPago()` usan `beginTransaction()` / `rollBack()` |

### Recomendaciones para producción

1. Migrar contraseñas de administrador a `password_hash()`
2. Usar HTTPS
3. Configurar `session.cookie_secure = true` y `session.cookie_httponly = true`
4. Mover `config/database.php` fuera del webroot o usar variables de entorno
5. Deshabilitar `display_errors` en `php.ini`

---

## 12. Guía de Uso

### Para el cliente

1. **Registrarse** en la página de inicio → botón "Registrarse"
2. **Iniciar sesión** con correo y contraseña
3. Desde el **Dashboard**: ver resumen de citas y catálogo de servicios
4. Clic en **"Agendar nueva cita"**:
   - Seleccionar una o varias tarjetas de servicio (se resaltan en rosado)
   - Ver el total acumulado en el panel de resumen
   - Elegir fecha y hora
   - Clic en "Confirmar reserva"
5. En la **pantalla de pago**:
   - Ver el resumen con servicios y total
   - Elegir método: Efectivo o Transferencia
   - Si elige Transferencia: ver datos Nequi/Daviplata con botón Copiar
   - Clic en "Confirmar pago"
6. En **"Mis Reservas"**: ver historial, estado y opción de cancelar citas pendientes

### Para el administrador

1. **Iniciar sesión** con usuario y contraseña de administrador
2. **Dashboard**: ver contadores generales (clientes, reservas, pagos, servicios)
3. **Reservas**: ver todas las citas, cambiar estado (pendiente → confirmada → en_curso → completada)
4. **Clientes**: ver lista, activar o desactivar cuentas
5. **Servicios**: editar descripción y precio de cada servicio, eliminar servicios
6. **Pagos**: ver historial completo de pagos con cliente, servicio, fecha y método

---

*Documentación generada para el Proyecto Formativo — Aleja-Nails · 2026*
