# Documentación Técnica — Sistema Aleja-Nails
### Salón de Belleza · PHP 8.1 · MySQL · Arquitectura MVC

---

## Índice de Módulos

1. [Módulo de Autenticación](#módulo-1-autenticación)
2. [Módulo de Registro de Clientes](#módulo-2-registro-de-clientes)
3. [Módulo Dashboard Cliente](#módulo-3-dashboard-cliente)
4. [Módulo de Reservas (Cliente)](#módulo-4-reservas-cliente)
5. [Módulo Dashboard Administrador](#módulo-5-dashboard-administrador)
6. [Módulo de Gestión de Clientes (Admin)](#módulo-6-gestión-de-clientes-admin)
7. [Módulo de Gestión de Reservas (Admin)](#módulo-7-gestión-de-reservas-admin)
8. [Módulo de Gestión de Servicios (Admin)](#módulo-8-gestión-de-servicios-admin)
9. [Módulo de Pagos (Admin)](#módulo-9-pagos-admin)
10. [Router Principal](#módulo-10-router-principal)

---

## Módulo 1: Autenticación

**Archivos involucrados:**
- Vista: `views/usuarios/login.php`
- Controlador: `controllers/AuthController.php`
- Modelo Admin: `models/Administrador.php`
- Modelo Cliente: `models/Cliente.php`
- Tablas BD: `administrador`, `cliente`

---

### 1. ¿Cómo funciona la pantalla de login?

Al acceder a la URL `?action=login`, el router instancia `AuthController` y llama al método `login()`. Este método primero verifica si ya existe una sesión activa comprobando `$_SESSION['usuario_id']`. Si existe, redirige automáticamente según el rol: los administradores van a `adminPanel` y los clientes van a `dashboard`. Si no hay sesión, carga la vista `views/usuarios/login.php`.

El formulario de login tiene el campo `correo` con `type="text"` (no `type="email"`) para permitir que el administrador ingrese su nombre de usuario sin el símbolo `@`.

---

### 2. ¿Cómo funciona el proceso de inicio de sesión?

Cuando el usuario envía el formulario, los datos viajan por `POST` a `?action=procesarLogin`. El método `procesarLogin()` del controlador sigue este orden:

**Validación de campos vacíos:** Si el campo `correo` o `password` están vacíos, guarda el mensaje `"Completa todos los campos."` en `$_SESSION['flash_error']` y redirige de vuelta al login.

**Intento como administrador:** Llama a `Administrador::buscarPorUsuario($usuario)`, que busca en la tabla `administrador` por el campo `usuario` o por el campo `correo` (usando `OR`). Si encuentra un registro y la `contraseña` coincide en texto plano con lo ingresado, guarda en sesión: `usuario_id`, `nombre`, `usuario` y `rol = 'admin'`. Luego redirige a `adminPanel`.

**Intento como cliente:** Si no coincidió como admin, llama a `Cliente::buscarPorCorreo($usuario)`, que busca en la tabla `cliente` por el campo `correo`. Si encuentra el registro pero el campo `password` está vacío, muestra el mensaje `"Tu cuenta no tiene contraseña."`. Si el campo tiene valor, usa `password_verify()` para comparar la contraseña ingresada con el hash almacenado. Si coincide, verifica que `activo = 1`. Si la cuenta está desactivada, muestra `"Tu cuenta está desactivada."`. Si todo es correcto, guarda en sesión: `usuario_id`, `nombre`, `correo` y `rol = 'cliente'`. Luego redirige a `dashboard`.

**Credenciales incorrectas:** Si ninguno de los dos intentos tuvo éxito, guarda `"Usuario o contraseña incorrectos."` en sesión y redirige al login.

---

### 3. ¿Cómo funciona cerrar sesión?

Al hacer clic en "Cerrar sesión", el router llama a `AuthController::logout()`. Este método ejecuta `session_unset()` para eliminar todas las variables de sesión y `session_destroy()` para destruir la sesión completamente. Luego redirige al login. Esto garantiza que no queden datos de sesión accesibles después de salir.

---

### 4. ¿Cómo funcionan los guards de seguridad?

El controlador tiene dos métodos de protección:

- `requerirLogin()`: verifica que exista `$_SESSION['usuario_id']`. Si no existe, redirige al login. Se usa en todos los métodos que requieren sesión activa.
- `requerirAdmin()`: llama primero a `requerirLogin()` y luego verifica que `$_SESSION['rol'] === 'admin'`. Si el rol no es admin, responde con código HTTP 403 y detiene la ejecución.

---

## Módulo 2: Registro de Clientes

**Archivos involucrados:**
- Vista: `views/usuarios/registro.php`
- Controlador: `controllers/AuthController.php`
- Modelo: `models/Cliente.php`
- Tabla BD: `cliente`

---

### 1. ¿Cómo funciona la pantalla de registro?

Al acceder a `?action=registro`, el método `mostrarRegistro()` verifica si ya hay sesión activa. Si la hay, redirige según el rol. Si no, carga la vista `views/usuarios/registro.php`.

El formulario solicita: nombre, apellido (solo visual, no se guarda en BD), teléfono, correo electrónico, contraseña y confirmación de contraseña. El `action` del formulario apunta a `/Mi-proyecto-formativo/public/index.php?action=procesarRegistro` con método `POST`.

---

### 2. ¿Cómo funciona el proceso de registro?

El método `procesarRegistro()` aplica las siguientes validaciones en orden:

1. **Campos obligatorios:** Verifica que `nombre`, `correo` y `password` no estén vacíos.
2. **Formato de correo:** Usa `filter_var($correo, FILTER_VALIDATE_EMAIL)` para validar el formato.
3. **Longitud de contraseña:** Verifica que `strlen($password) >= 8`.
4. **Confirmación:** Compara `$password` con `$confirmar`. Si no coinciden, muestra error.
5. **Correo duplicado:** Llama a `Cliente::correoExiste($correo)`, que ejecuta `SELECT id_cliente FROM cliente WHERE correo=? LIMIT 1`. Si ya existe, muestra `"Ese correo ya está registrado."`.

Si todas las validaciones pasan, llama a `Cliente::crear()`.

---

### 3. ¿Cómo funciona el método crear() del modelo Cliente?

El método `crear()` primero ejecuta `DESCRIBE cliente` para verificar si las columnas `password` y `activo` existen en la tabla. Si no existen, las agrega automáticamente con `ALTER TABLE`. Esto garantiza compatibilidad con la estructura original de la BD que no tenía esas columnas.

Luego ejecuta el `INSERT` con los campos: `nombre`, `telefono`, `correo` y `password`. La contraseña se encripta con `password_hash($datos['password'], PASSWORD_DEFAULT)`, que usa el algoritmo bcrypt. Si ocurre un error de base de datos, lo registra con `error_log()` y guarda el mensaje real en `$_SESSION['flash_error']` para mostrarlo en pantalla.

Si el registro es exitoso, guarda `"Cuenta creada. Ya puedes iniciar sesión."` en `$_SESSION['flash_ok']` y redirige al login.

---

## Módulo 3: Dashboard Cliente

**Archivos involucrados:**
- Vista: `views/dashboard/cliente.php`
- Controlador: `controllers/UsuarioController.php`
- Modelos: `models/Reserva.php`, `models/Servicio.php`
- Tablas BD: `reserva`, `servicio`, `cliente`

---

### 1. ¿Cómo funciona el dashboard del cliente?

Al acceder a `?action=dashboard`, el método `UsuarioController::dashboard()` llama primero a `validarSesion()`, que verifica `$_SESSION['usuario_id']`. Si no hay sesión, redirige al login.

Una vez validado, ejecuta dos consultas en paralelo:
- `Reserva::obtenerPorCliente($_SESSION['usuario_id'])`: trae todas las reservas del cliente con un `INNER JOIN` a la tabla `servicio` para obtener `nombre_servicio` y `precio`.
- `Servicio::obtenerTodos()`: trae el catálogo completo de servicios ordenado por categoría.

Ambos resultados se pasan a la vista como `$reservas` y `$servicios`.

---

### 2. ¿Qué muestra el dashboard?

La vista calcula tres estadísticas usando `array_filter()` sobre el arreglo `$reservas`:
- **Total de reservas:** `count($reservas)`
- **Pendientes:** filtra por `estado === 'pendiente'`
- **Completadas:** filtra por `estado === 'completada'`

Estas cifras se muestran en tarjetas de resumen en la parte superior. Debajo aparecen las últimas 5 reservas usando `array_slice($reservas, 0, 5)`, y al final el catálogo de servicios disponibles.

---

## Módulo 4: Reservas (Cliente)

**Archivos involucrados:**
- Vistas: `views/usuarios/agendar.php`, `views/usuarios/mis-reservas.php`
- Controlador: `controllers/UsuarioController.php`
- Modelo: `models/Reserva.php`
- Tablas BD: `reserva`, `servicio`

---

### 1. ¿Cómo funciona agendar una cita?

Al acceder a `?action=agendarCita` por `GET`, el controlador carga `$servicios` y renderiza el formulario `agendar.php`. La vista organiza los servicios en tres pestañas (tabs): 💅 Manicure, 👣 Pedicure y 💆🏽‍♀️ Capilar. Cada servicio se muestra como una tarjeta seleccionable con radio button oculto. Al seleccionar una tarjeta, la función JavaScript `seleccionarServicio()` la resalta con borde rosado.

La hora se selecciona desde un `<select>` con intervalos de 30 minutos entre las 8:00 y las 19:00, evitando que el cliente ingrese horas fuera del horario de atención.

---

### 2. ¿Cómo se procesa la reserva?

Cuando el formulario se envía por `POST`, el método `agendarCita()` aplica dos validaciones:

1. **Fecha pasada:** Compara `$fecha` con `date('Y-m-d')`. Si la fecha es anterior al día actual, muestra `"No puedes agendar fechas pasadas."` y redirige de vuelta al formulario.

2. **Conflicto de horario:** Llama a `Reserva::existeConflicto($fecha, $hora)`, que ejecuta `SELECT id_reserva FROM reserva WHERE fecha=? AND hora=? AND estado != 'cancelada' LIMIT 1`. Si ya existe una reserva activa en ese horario, muestra `"Ese horario ya está reservado."`.

Si pasa las validaciones, llama a `Reserva::crear()` con los datos: `fecha`, `hora`, `id_cliente` (de la sesión), `id_servicio` e `id_empleados = null`. El estado se inserta siempre como `'pendiente'`. Si la creación es exitosa, redirige a `misReservas`.

---

### 3. ¿Cómo funciona cancelar una reserva?

Al hacer clic en "Cancelar" en la lista de reservas, se envía una petición `GET` a `?action=cancelarReserva&id=[ID]`. El método `cancelarReserva()` primero valida que el `id` sea un entero positivo. Luego llama a `Reserva::buscarPorId($id)` y verifica que `id_cliente` del registro coincida con `$_SESSION['usuario_id']`. Esta verificación impide que un cliente cancele reservas de otro cliente. Si la verificación pasa, llama a `Reserva::actualizarEstado($id, 'cancelada')`, que ejecuta `UPDATE reserva SET estado='cancelada' WHERE id_reserva=?`.

---

## Módulo 5: Dashboard Administrador

**Archivos involucrados:**
- Vista: `views/dashboard/admin.php`
- Controlador: `controllers/AdminUsuarioController.php`
- Tablas BD: `cliente`, `reserva`, `pago`, `servicio`

---

### 1. ¿Cómo funciona el panel de administración?

Al acceder a `?action=adminPanel`, el constructor de `AdminUsuarioController` llama automáticamente a `validarAdmin()`, que verifica dos condiciones: que exista `$_SESSION['usuario_id']` y que `$_SESSION['rol'] === 'admin'`. Si alguna falla, redirige al login o responde con HTTP 403.

El método `dashboard()` ejecuta cuatro consultas `COUNT(*)` directas sobre las tablas principales:
- `SELECT COUNT(*) FROM cliente` → `$totalClientes`
- `SELECT COUNT(*) FROM reserva` → `$totalReservas`
- `SELECT COUNT(*) FROM pago` → `$totalPagos`
- `SELECT COUNT(*) FROM servicio` → `$totalServicios`

Estos valores se pasan a la vista y se muestran en cuatro tarjetas de estadísticas. La vista también incluye botones de acceso rápido a cada sección del panel.

---

## Módulo 6: Gestión de Clientes (Admin)

**Archivos involucrados:**
- Vista: `views/admin/clientes.php`
- Controlador: `controllers/AdminUsuarioController.php`
- Modelo: `models/Cliente.php`
- Tabla BD: `cliente`

---

### 1. ¿Cómo se lista a los clientes?

El método `listarClientes()` llama a `Cliente::obtenerTodos()`, que ejecuta `SELECT * FROM cliente ORDER BY id_cliente DESC`. El resultado se pasa a la vista como `$clientes`. La tabla muestra: ID, nombre, correo, teléfono, estado (activo/inactivo) y un botón de acción.

El badge de estado usa la columna `activo`: si `activo = 1` muestra "Activo" en verde, si `activo = 0` muestra "Inactivo" en rojo.

---

### 2. ¿Cómo funciona activar/desactivar un cliente?

Al hacer clic en el botón de acción, se envía `GET` a `?action=toggleCliente&id=[ID]`. El método `toggleCliente()` llama a `Cliente::buscarPorId($id)` para obtener el estado actual. Luego calcula el nuevo estado con una expresión ternaria: si `activo == 1` el nuevo estado es `0`, si es `0` el nuevo estado es `1`. Finalmente llama a `Cliente::cambiarEstado($id, $nuevoEstado)`, que ejecuta `UPDATE cliente SET activo=? WHERE id_cliente=?`. Después redirige a `listarClientes`.

Un cliente desactivado (`activo = 0`) no puede iniciar sesión porque `AuthController::procesarLogin()` verifica el campo `activo` antes de crear la sesión.

---

## Módulo 7: Gestión de Reservas (Admin)

**Archivos involucrados:**
- Vista: `views/admin/reservas.php`
- Controlador: `controllers/AdminUsuarioController.php`
- Modelo: `models/Reserva.php`
- Tablas BD: `reserva`, `cliente`, `servicio`

---

### 1. ¿Cómo se listan todas las reservas?

El método `listarReservas()` llama a `Reserva::obtenerTodas()`, que ejecuta una consulta con dos `INNER JOIN`:
- `INNER JOIN cliente c ON r.id_cliente = c.id_cliente` → para obtener `nombre_cliente`
- `INNER JOIN servicio s ON r.id_servicio = s.id_servicio` → para obtener `nombre_servicio`

Los resultados se ordenan por `fecha DESC, hora DESC` para mostrar las más recientes primero.

---

### 2. ¿Cómo funciona cambiar el estado de una reserva?

Cada fila de la tabla tiene un formulario `POST` con un `<select>` que muestra los cinco estados posibles: `pendiente`, `confirmada`, `en_curso`, `completada`, `cancelada`. El estado actual aparece preseleccionado con `selected`.

Al hacer clic en "Guardar", los datos viajan a `?action=actualizarReserva`. El método `actualizarReserva()` valida que el estado recibido esté dentro del arreglo `$estadosValidos` usando `in_array()`. Si es válido, llama a `Reserva::actualizarEstado($id, $estado)`, que ejecuta `UPDATE reserva SET estado=? WHERE id_reserva=?`.

---

## Módulo 8: Gestión de Servicios (Admin)

**Archivos involucrados:**
- Vista: `views/admin/servicios.php`
- Controlador: `controllers/AdminUsuarioController.php`
- Modelo: `models/Servicio.php`
- Tabla BD: `servicio`

---

### 1. ¿Cómo se listan los servicios?

El método `listarServicios()` llama a `Servicio::obtenerTodos()`. Este método primero verifica si la columna `categoria` existe en la tabla ejecutando `DESCRIBE servicio`. Si no existe, la agrega con `ALTER TABLE servicio ADD COLUMN categoria VARCHAR(50) NOT NULL DEFAULT 'Manicure'`. Luego ejecuta `SELECT * FROM servicio ORDER BY categoria ASC, nombre_servicio ASC`.

La vista agrupa los servicios por categoría usando un `foreach` sobre el arreglo `$grupos`. Cada categoría tiene su propio bloque con ícono: 💅 Manicure, 👣 Pedicure, 💆🏽‍♀️ Capilar.

---

### 2. ¿Cómo funciona editar el precio de un servicio?

Cada fila de la tabla tiene un formulario `POST` independiente con `input[type="number"]` para el precio e `input[type="text"]` para la descripción. Al hacer clic en "Guardar", los datos van a `?action=actualizarServicio`.

El método `actualizarServicio()` recibe `id_servicio`, `descripcion` y `precio`. Valida que el `id` sea positivo y el `precio` sea mayor o igual a cero. Luego llama a `Servicio::editar()`. Como el nombre del servicio no se envía en este formulario, el método `editar()` detecta que `nombre_servicio` está vacío y ejecuta solo `UPDATE servicio SET descripcion=?, precio=? WHERE id_servicio=?`, sin tocar el nombre. Guarda `"Servicio actualizado correctamente."` en sesión y redirige a `listarServicios`.

---

## Módulo 9: Pagos (Admin)

**Archivos involucrados:**
- Vista: `views/admin/pagos.php`
- Controlador: `controllers/AdminUsuarioController.php`
- Tablas BD: `pago`, `reserva`, `cliente`, `servicio`

---

### 1. ¿Cómo funciona el reporte de pagos?

El método `verPagos()` ejecuta una consulta con tres `JOIN`:

```sql
SELECT p.*, c.nombre AS nombre_cliente, s.nombre_servicio
FROM pago p
INNER JOIN reserva r ON p.id_reserva = r.id_reserva
INNER JOIN cliente  c ON r.id_cliente  = c.id_cliente
INNER JOIN servicio s ON r.id_servicio = s.id_servicio
ORDER BY p.id_pago DESC
```

Esta consulta cruza la tabla `pago` con `reserva` para obtener el cliente y el servicio asociados a cada pago. La vista muestra: ID del pago, nombre del cliente, nombre del servicio, fecha de pago, método de pago y valor pagado.

---

## Módulo 10: Router Principal

**Archivo:** `public/index.php`

---

### 1. ¿Cómo funciona el enrutador?

El archivo `public/index.php` es el único punto de entrada del sistema. Todas las URLs del proyecto pasan por él con el parámetro `?action=`. Al inicio carga todos los modelos y controladores con `require_once`. Luego lee `$_GET['action']` con valor por defecto `'login'`.

Un `switch` mapea cada acción a su método correspondiente. Para las acciones que procesan formularios (`procesarLogin`, `procesarRegistro`, `actualizarReserva`, `actualizarServicio`), verifica que el método HTTP sea `POST` antes de ejecutar el controlador. Si no es `POST`, redirige a la vista correspondiente.

Las acciones de admin (`adminPanel`, `listarClientes`, `listarReservas`, `listarServicios`, `verPagos`) instancian `AdminUsuarioController` directamente. El constructor de ese controlador llama automáticamente a `validarAdmin()`, por lo que la protección es automática sin necesidad de verificarla en el router.

---

## Estructura de la Base de Datos

| Tabla | Columnas principales | Descripción |
|---|---|---|
| `administrador` | `id_administrador`, `nombre`, `correo`, `usuario`, `contraseña` | Usuarios con rol admin. Contraseña en texto plano. |
| `cliente` | `id_cliente`, `nombre`, `telefono`, `correo`, `password`, `activo` | Clientes registrados. Contraseña con bcrypt. |
| `servicio` | `id_servicio`, `nombre_servicio`, `descripcion`, `categoria`, `precio`, `id_administrador` | Catálogo de servicios del salón. |
| `reserva` | `id_reserva`, `fecha`, `hora`, `estado`, `id_cliente`, `id_servicio`, `id_empleados` | Citas agendadas. Estado: pendiente/confirmada/en_curso/completada/cancelada. |
| `pago` | `id_pago`, `fecha_pago`, `metodo_pago`, `valor_pagado`, `id_reserva` | Registro de pagos vinculados a reservas. |
| `empleados` | `id_empleados`, `nombre`, `telefono`, `id_administrador` | Personal del salón. |
| `detalle_servicio` | `id_detalle_servicio`, `cantidad`, `precio_unitario`, `subtotal`, `id_reserva`, `id_servicio` | Detalle de servicios por reserva. |

---

## Flujo General del Sistema

```
Usuario accede a la URL
        ↓
public/index.php lee ?action=
        ↓
Router instancia el controlador correspondiente
        ↓
Controlador valida sesión / rol
        ↓
Controlador llama al modelo (consulta BD)
        ↓
Modelo retorna datos al controlador
        ↓
Controlador pasa variables a la vista
        ↓
Vista renderiza HTML con layouts (header + sidebar + footer)
```

---

*Documentación generada para el proyecto formativo Aleja-Nails · Mayo 2026*
