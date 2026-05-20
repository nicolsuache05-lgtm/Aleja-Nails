# Vista: Iniciar Sesión

**Archivo:** `views/usuarios/login.php`  
**Ruta de acceso:** `?action=login`  
**Rol requerido:** Ninguno (pública)  
**Controlador:** `AuthController::login()` / `AuthController::procesarLogin()`

---

## ¿Qué hace esta vista?

Muestra el formulario de inicio de sesión. Permite acceder tanto a clientes como a administradores con el mismo formulario.

---

## Campos del formulario

| Campo | Tipo | Nombre HTML | Descripción |
|-------|------|-------------|-------------|
| Usuario o correo | text | `correo` | Acepta el usuario del admin o el correo del cliente |
| Contraseña | password | `password` | Contraseña de la cuenta |

- **Acción del formulario:** `POST ?action=procesarLogin`
- Todos los campos son obligatorios (`required`)

---

## Lógica de autenticación (en el controlador)

```
procesarLogin() recibe correo + password

1. Busca en tabla `administrador` por usuario o correo
   → Si existe y contraseña coincide (texto plano):
       Crea sesión con rol='admin'
       Redirige a ?action=adminPanel

2. Busca en tabla `cliente` por correo
   → Si existe y password_verify() es true:
       Si cuenta está inactiva → error "cuenta desactivada"
       Crea sesión con rol='cliente'
       Redirige a ?action=dashboard

3. Si ninguno coincide → flash error "Usuario o contraseña incorrectos"
```

---

## Variables de sesión que se crean

### Para cliente:
```php
$_SESSION['usuario_id'] = $cliente['id_cliente']
$_SESSION['nombre']     = $cliente['nombre']
$_SESSION['correo']     = $cliente['correo']
$_SESSION['rol']        = 'cliente'
```

### Para administrador:
```php
$_SESSION['usuario_id'] = $admin['id_administrador']
$_SESSION['nombre']     = $admin['nombre']
$_SESSION['usuario']    = $admin['usuario']
$_SESSION['rol']        = 'admin'
```

---

## Mensajes flash que puede mostrar

| Tipo | Mensaje | Cuándo aparece |
|------|---------|----------------|
| Error | "Completa todos los campos." | Campos vacíos |
| Error | "Usuario o contraseña incorrectos." | Credenciales inválidas |
| Error | "Tu cuenta está desactivada." | Cliente inactivo |
| Error | "Tu cuenta no tiene contraseña." | Cliente sin password |
| Éxito | "Registro exitoso. Ya puedes iniciar sesión." | Viene del registro |

---

## Redirección si ya hay sesión

Si el usuario ya está logueado y accede a esta vista, es redirigido automáticamente:
- Admin → `?action=adminPanel`
- Cliente → `?action=dashboard`

---

## Tablas de base de datos involucradas

| Tabla | Consulta |
|-------|---------|
| `administrador` | `SELECT * WHERE usuario = ? OR correo = ?` |
| `cliente` | `SELECT * WHERE correo = ?` |
