# Vista: Registro de Cliente

**Archivo:** `views/usuarios/registro.php`  
**Ruta de acceso:** `?action=registro`  
**Rol requerido:** Ninguno (pública)  
**Controlador:** `AuthController::mostrarRegistro()` / `AuthController::procesarRegistro()`

---

## ¿Qué hace esta vista?

Permite a un nuevo usuario crear una cuenta de cliente para poder agendar citas en el sistema.

---

## Campos del formulario

| Campo | Tipo | Nombre HTML | Obligatorio | Validación |
|-------|------|-------------|-------------|------------|
| Nombre | text | `nombre` | ✅ | No vacío |
| Apellido | text | `apellido` | ✅ | No vacío |
| Correo electrónico | email | `correo` | ✅ | Formato válido, no duplicado |
| Teléfono | tel | `telefono` | ❌ | Ninguna |
| Contraseña | password | `password` | ✅ | Mínimo 8 caracteres |
| Confirmar contraseña | password | `confirmar` | ✅ | Debe coincidir con `password` |

- **Acción del formulario:** `POST ?action=procesarRegistro`

---

## Validaciones del controlador

```
procesarRegistro() valida en este orden:

1. Nombre, correo y contraseña no vacíos
   → Error: "Nombre, correo y contraseña son obligatorios."

2. Formato de correo válido (filter_var FILTER_VALIDATE_EMAIL)
   → Error: "El correo no tiene un formato válido."

3. Contraseña mínimo 8 caracteres
   → Error: "La contraseña debe tener al menos 8 caracteres."

4. Contraseña y confirmación coinciden
   → Error: "Las contraseñas no coinciden."

5. Correo no registrado previamente
   → Error: "Ese correo ya está registrado."

6. Si todo es válido → INSERT en tabla cliente
   → Éxito: "Registro exitoso. Ya puedes iniciar sesión."
   → Redirige a ?action=login
```

---

## Almacenamiento de la contraseña

La contraseña **nunca se guarda en texto plano**. Se usa `password_hash()` con el algoritmo `PASSWORD_DEFAULT` (bcrypt):

```php
password_hash($datos['password'], PASSWORD_DEFAULT)
```

Para verificarla al login se usa `password_verify($password, $hash)`.

---

## Diseño de la vista

- Fondo con degradado rosado
- Tarjeta centrada con bordes redondeados
- Tipografía "Great Vibes" para el título "Regístrate"
- Campos con fondo semitransparente y bordes suaves
- Link al login para usuarios que ya tienen cuenta

---

## Tablas de base de datos involucradas

| Tabla | Operación |
|-------|-----------|
| `cliente` | `SELECT` para verificar correo duplicado |
| `cliente` | `INSERT` con nombre, teléfono, correo, password_hash |

---

## Columnas que se agregan automáticamente

Si la tabla `cliente` no tiene las columnas `password` o `activo`, el modelo las agrega al primer registro:

```sql
ALTER TABLE cliente ADD COLUMN password VARCHAR(255) NOT NULL DEFAULT ''
ALTER TABLE cliente ADD COLUMN activo TINYINT(1) NOT NULL DEFAULT 1
```
