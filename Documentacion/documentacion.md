# Documentación Técnica — Sistema Aleja-Nails
**Salón de Belleza Profesional**
Desarrollado en PHP 8.1 · MySQL 8.0 · Arquitectura MVC · Laragon

---

## Estructura general del proyecto

```
Mi-proyecto-formativo/
├── config/
│   └── database.php          ← Conexión PDO a MySQL
├── controllers/
│   ├── AuthController.php    ← Login, registro, logout
│   ├── UsuarioController.php ← Acciones del cliente
│   └── AdminUsuarioController.php ← Panel administrador
├── models/
│   ├── Administrador.php     ← Tabla: administrador
│   ├── Cliente.php           ← Tabla: cliente
│   ├── Servicio.php          ← Tabla: servicio
│   └── Reserva.php           ← Tabla: reserva
├── views/
│   ├── layouts/
│   │   ├── header.php        ← Topbar + estilos globales
│   │   ├── sidebar.php       ← Menú lateral dinámico
│   │   └── footer.php        ← Cierre de layout
│   ├── usuarios/
│   │   ├── login.php         ← Formulario de inicio de sesión
│   │   ├── registro.php      ← Formulario de registro de cliente
│   │   ├── agendar.php       ← Formulario de nueva reserva
│   │   └── mis-reservas.php  ← Listado de reservas del cliente
│   └── dashboard/
│       ├── admin.php         ← Panel principal del administrador
│       └── cliente.php       ← Panel principal del cliente
├── public/
│   └── index.php             ← Router principal (único punto de entrada)
├── sql/
│   └── aleja-nails.sql       ← Script de BD
└── img/
    └── ico.png               ← Logo del sistema
```

**Tablas de la base de datos `aleja-nails`:**

| Tabla | Descripción |
|---|---|
| `administrador` | Cuentas del personal administrativo |
| `cliente` | Clientes registrados en el sistema |
| `servicio` | Catálogo de servicios del salón |
| `reserva` | Citas agendadas por los clientes |
| `pago` | Pagos asociados a reservas |
| `empleados` | Personal del salón |
| `detalle_servicio` | Detalle de servicios por reserva |

---

# Módulo 1 — Autenticación

**Archivos involucrados:**
- Vista: `views/usuarios/login.php`
- Controlador: `controllers/AuthController.php`
- Modelos: `models/Administrador.php`, `models/Cliente.php`
- Tablas BD: `administrador`, `cliente`

## 1. ¿Cómo funciona la pantalla de login?

El formulario de login envía los datos por `POST` a `public/index.php?action=procesarLogin`. El campo de usuario acepta tanto el nombre de usuario del administrador como el correo del cliente, ya que el input es `type="text"` para evitar la restricción del navegador con el símbolo `@`.

El controlador `AuthController` recibe los datos y sigue este orden:

**Paso 1 — Validación de campos vacíos:** Si el usuario o la contraseña están vacíos, guarda un mensaje en `$_SESSION['flash_error']` y redirige de vuelta al login.

**Paso 2 — Búsqueda como administrador:** Llama a `Administrador::buscarPorUsuario()`, que ejecuta un `SELECT` en la tabla `administrador` buscando por el campo `usuario` o `correo`. Si encuentra un registro y la contraseña coincide en texto plano, inicia sesión como admin.

**Paso 3 — Búsqueda como cliente:** Si no es admin, llama a `Cliente::buscarPorCorreo()`. Si encuentra el cliente, usa `password_verify()` para comparar la contraseña ingresada con el hash almacenado en la columna `password`.

**Paso 4 — Verificación de estado:** Antes de abrir la sesión, verifica que `activo = 1`. Si la cuenta está desactivada, muestra el mensaje "Tu cuenta está desactivada."

**Paso 5 — Apertura de sesión:** Guarda en `$_SESSION` el `usuario_id`, `nombre`, `correo` y `rol`. Luego redirige según el rol: admin va a `adminPanel`, cliente va a `dashboard`.

## 2. ¿Cómo funciona el registro de clientes?

El formulario de registro envía datos por `POST` a `public/index.php?action=procesarRegistro`. Solo los clientes pueden registrarse desde el formulario público. Los administradores se crean directamente en la base de datos.

El método `AuthController::procesarRegistro()` valida en este orden:

1. Que nombre, correo y contraseña no estén vacíos.
2. Que el correo tenga formato válido usando `filter_var($correo, FILTER_VALIDATE_EMAIL)`.
3. Que la contraseña tenga al menos 8 caracteres.
4. Que las contraseñas coincidan.
5. Que el correo no esté ya registrado con `Cliente::correoExiste()`.

Si todas las validaciones pasan, llama a `Cliente::crear()`, que detecta automáticamente si las columnas `password` y `activo` existen en la tabla (las agrega con `ALTER TABLE` si no existen), y ejecuta el `INSERT` con la contraseña encriptada usando `password_hash($password, PASSWORD_DEFAULT)`.

## 3. ¿Cómo funciona cerrar sesión?

Al hacer clic en "Cerrar sesión", el router llama a `AuthController::logout()`, que ejecuta `session_unset()` para limpiar todas las variables de sesión y `session_destroy()` para destruir la sesión completamente. Luego redirige al login.

---

# Módulo 2 — Gestión de Clientes (Admin)

**Archivos involucrados:**
- Vista: `views/admin/clientes.php`
- Controlador: `controllers/AdminUsuarioController.php`
- Modelo: `models/Cliente.php`
- Tabla BD: `cliente`

## 1. ¿Cómo funciona el listado de clientes?

Al ingresar a `action=listarClientes`, el controlador verifica que la sesión sea de un administrador. Luego llama a `Cliente::obtenerTodos()`, que ejecuta `SELECT * FROM cliente ORDER BY id_cliente DESC`. El resultado se pasa a la vista en la variable `$clientes`.

La tabla muestra: ID, nombre, correo, teléfono y estado (activo/inactivo). Cada fila tiene un botón para activar o desactivar el cliente.

## 2. ¿Cómo funciona activar/desactivar un cliente?

El botón de cada fila enlaza a `action=toggleCliente&id=[ID]`. El controlador llama a `Cliente::buscarPorId()` para obtener el estado actual, calcula el nuevo estado (`1 - estado_actual`) y ejecuta `Cliente::cambiarEstado()`, que hace un `UPDATE cliente SET activo=? WHERE id_cliente=?`.

Un cliente desactivado no puede iniciar sesión porque `AuthController` verifica el campo `activo` antes de abrir la sesión.

---

# Módulo 3 — Gestión de Reservas

**Archivos involucrados:**
- Vistas: `views/usuarios/agendar.php`, `views/usuarios/mis-reservas.php`, `views/admin/reservas.php`
- Controladores: `controllers/UsuarioController.php`, `controllers/AdminUsuarioController.php`
- Modelo: `models/Reserva.php`
- Tablas BD: `reserva`, `servicio`, `cliente`

## 1. ¿Cómo funciona agendar una cita?

El formulario de `agendar.php` muestra los servicios agrupados en tres categorías mediante tabs: 💅 Manicure, 👣 Pedicure y 💆🏽‍♀️ Capilar. Cada servicio se muestra como una tarjeta seleccionable con `<input type="radio">` oculto. Al seleccionar una tarjeta, JavaScript la resalta con borde rosado.

Al enviar el formulario, `UsuarioController::agendarCita()` realiza estas validaciones:

1. Que la fecha no sea anterior a hoy.
2. Que no exista ya una reserva en esa misma fecha y hora con `Reserva::existeConflicto()`, que ejecuta `SELECT id_reserva FROM reserva WHERE fecha=? AND hora=? AND estado != 'cancelada'`.

Si pasa las validaciones, llama a `Reserva::crear()` con el `id_cliente` tomado de `$_SESSION['usuario_id']`.

## 2. ¿Cómo funciona el listado de reservas del cliente?

`UsuarioController::misReservas()` llama a `Reserva::obtenerPorCliente($id_cliente)`, que ejecuta:

```sql
SELECT r.*, s.nombre_servicio, s.precio
FROM reserva r
INNER JOIN servicio s ON r.id_servicio = s.id_servicio
WHERE r.id_cliente = ?
ORDER BY r.fecha DESC, r.hora DESC
```

El resultado se muestra en una tabla con el estado de cada reserva como badge de color. Las reservas en estado `pendiente` muestran un botón "Cancelar".

## 3. ¿Cómo funciona cancelar una reserva?

El botón "Cancelar" enlaza a `action=cancelarReserva&id=[ID]`. El controlador primero verifica que la reserva pertenezca al cliente en sesión (evita que un cliente cancele reservas ajenas). Luego llama a `Reserva::actualizarEstado($id, 'cancelada')`, que ejecuta `UPDATE reserva SET estado='cancelada' WHERE id_reserva=?`.

## 4. ¿Cómo gestiona el admin las reservas?

El admin accede a `action=listarReservas`, que llama a `Reserva::obtenerTodas()` con un `INNER JOIN` a `cliente` y `servicio`. Cada fila tiene un formulario con un `<select>` de estados: pendiente, confirmada, en_curso, completada, cancelada. Al guardar, envía `POST` a `action=actualizarReserva`.

---

# Módulo 4 — Catálogo de Servicios

**Archivos involucrados:**
- Vista: `views/admin/servicios.php`
- Controlador: `controllers/AdminUsuarioController.php`
- Modelo: `models/Servicio.php`
- Tabla BD: `servicio`

## 1. ¿Cómo funciona el catálogo?

Los servicios están organizados en tres categorías almacenadas en la columna `categoria` de la tabla `servicio`: Manicure, Pedicure y Capilar. El modelo `Servicio::obtenerTodos()` detecta automáticamente si la columna `categoria` existe; si no, la agrega con `ALTER TABLE`.

## 2. ¿Cómo se edita el precio de un servicio?

En `views/admin/servicios.php`, cada fila de la tabla es un formulario independiente. El administrador puede modificar el precio (campo `number`) y la descripción (campo `text`) directamente en la tabla y presionar "Guardar". El formulario envía `POST` a `action=actualizarServicio`.

El controlador llama a `Servicio::editar()`, que ejecuta:

```sql
UPDATE servicio SET descripcion=?, precio=? WHERE id_servicio=?
```

---

# Módulo 5 — Panel de Administración

**Archivos involucrados:**
- Vista: `views/dashboard/admin.php`
- Controlador: `controllers/AdminUsuarioController.php`
- Tablas BD: `cliente`, `reserva`, `pago`, `servicio`

## 1. ¿Cómo funciona el panel principal?

Al ingresar a `action=adminPanel`, el controlador ejecuta cuatro consultas de conteo:

```sql
SELECT COUNT(*) FROM cliente
SELECT COUNT(*) FROM reserva
SELECT COUNT(*) FROM pago
SELECT COUNT(*) FROM servicio
```

Los resultados se pasan a la vista como `$totalClientes`, `$totalReservas`, `$totalPagos` y `$totalServicios`, y se muestran en tarjetas de estadísticas.

## 2. ¿Cómo funciona la protección de rutas del admin?

El constructor de `AdminUsuarioController` llama a `validarAdmin()` en cada instancia. Este método verifica que `$_SESSION['usuario_id']` exista y que `$_SESSION['rol'] === 'admin'`. Si alguna condición falla, redirige al login o devuelve un error 403.

---

# Módulo 6 — Configuración de Base de Datos

**Archivo:** `config/database.php`

## ¿Cómo funciona la conexión?

La clase `Database` implementa el patrón **Singleton**: mantiene una única instancia de `PDO` en la propiedad estática `$conexion`. Si ya existe una conexión, la reutiliza en vez de crear una nueva.

El DSN (Data Source Name) se construye así:

```php
"mysql:host=127.0.0.1;port=3320;dbname=aleja-nails;charset=utf8mb4"
```

Los atributos configurados son:
- `ATTR_ERRMODE = ERRMODE_EXCEPTION` — lanza excepciones en errores SQL.
- `ATTR_DEFAULT_FETCH_MODE = FETCH_ASSOC` — devuelve arrays asociativos.
- `ATTR_EMULATE_PREPARES = false` — usa prepared statements nativos del servidor.

El método `conectar()` es un alias de `getConnection()` para compatibilidad con código existente.

---

# Módulo 7 — Router (Punto de entrada único)

**Archivo:** `public/index.php`

## ¿Cómo funciona el enrutamiento?

Todo el tráfico del sistema pasa por `public/index.php`. La acción se lee del parámetro GET: `$action = $_GET['action'] ?? 'login'`. Un `switch` mapea cada acción al método del controlador correspondiente.

Las rutas disponibles son:

| Acción | Controlador | Método |
|---|---|---|
| `login` | AuthController | `login()` |
| `procesarLogin` | AuthController | `procesarLogin()` |
| `registro` | AuthController | `mostrarRegistro()` |
| `procesarRegistro` | AuthController | `procesarRegistro()` |
| `logout` | AuthController | `logout()` |
| `dashboard` | UsuarioController | `dashboard()` |
| `agendarCita` | UsuarioController | `agendarCita()` |
| `misReservas` | UsuarioController | `misReservas()` |
| `cancelarReserva` | UsuarioController | `cancelarReserva()` |
| `adminPanel` | AdminUsuarioController | `dashboard()` |
| `listarClientes` | AdminUsuarioController | `listarClientes()` |
| `toggleCliente` | AdminUsuarioController | `toggleCliente()` |
| `listarReservas` | AdminUsuarioController | `listarReservas()` |
| `actualizarReserva` | AdminUsuarioController | `actualizarReserva()` |
| `listarServicios` | AdminUsuarioController | `listarServicios()` |
| `actualizarServicio` | AdminUsuarioController | `actualizarServicio()` |
| `verPagos` | AdminUsuarioController | `verPagos()` |
