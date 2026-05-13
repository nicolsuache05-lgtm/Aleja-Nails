<?php
/**
 * instalar.php — Adapta la BD existente al proyecto PHP
 * Accede a: http://localhost:8081/Mi-proyecto-formativo/instalar.php
 * ELIMINA este archivo después de ejecutarlo.
 */

$pdo = new PDO(
    "mysql:host=127.0.0.1;port=3320;dbname=aleja-nails;charset=utf8mb4",
    "root", "",
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
);

function ok($m)  { echo "<p style='color:#2e7d32'>✅ $m</p>"; }
function err($m) { echo "<p style='color:#c62828'>❌ $m</p>"; }
function info($m){ echo "<p style='color:#555'>ℹ️ $m</p>"; }

echo "<!DOCTYPE html><html><head><meta charset='UTF-8'>
<style>body{font-family:sans-serif;max-width:700px;margin:40px auto;padding:20px}
h2{color:#c0375a} hr{margin:20px 0} table{border-collapse:collapse;width:100%}
th,td{border:1px solid #ddd;padding:8px} th{background:#f5f5f5}</style></head><body>
<h2>💅 Instalador — Aleja-Nails</h2>";

// 1. Agregar columna password a cliente
try {
    $cols = $pdo->query("DESCRIBE cliente")->fetchAll(PDO::FETCH_COLUMN);
    if (!in_array('password', $cols)) {
        $pdo->exec("ALTER TABLE cliente ADD COLUMN password VARCHAR(255) NOT NULL DEFAULT '' AFTER correo");
        ok("Columna <strong>password</strong> agregada a la tabla <strong>cliente</strong>");
    } else {
        info("Columna password ya existe en cliente");
    }
} catch (PDOException $e) {
    err("Error agregando password: " . htmlspecialchars($e->getMessage()));
}

// 2. Agregar columna activo a cliente
try {
    $cols = $pdo->query("DESCRIBE cliente")->fetchAll(PDO::FETCH_COLUMN);
    if (!in_array('activo', $cols)) {
        $pdo->exec("ALTER TABLE cliente ADD COLUMN activo TINYINT(1) NOT NULL DEFAULT 1 AFTER password");
        ok("Columna <strong>activo</strong> agregada a la tabla <strong>cliente</strong>");
    } else {
        info("Columna activo ya existe en cliente");
    }
} catch (PDOException $e) {
    err("Error agregando activo: " . htmlspecialchars($e->getMessage()));
}

// 3. Crear administrador de prueba si no existe
try {
    // Agregar columna correo si no existe
    $cols = $pdo->query("DESCRIBE administrador")->fetchAll(PDO::FETCH_COLUMN);
    if (!in_array('correo', $cols)) {
        $pdo->exec("ALTER TABLE administrador ADD COLUMN correo VARCHAR(100) DEFAULT NULL AFTER nombre");
        ok("Columna <strong>correo</strong> agregada a <strong>administrador</strong>");
    } else {
        info("Columna correo ya existe en administrador");
    }

    $count = $pdo->query("SELECT COUNT(*) FROM administrador")->fetchColumn();
    if ($count == 0) {
        $pdo->exec("INSERT INTO administrador (nombre, correo, usuario, contraseña) VALUES
            ('Alejandra Gómez', 'admin@alejaNails.com', 'admin', 'admin123')");
        ok("Administrador creado — usuario: <strong>admin</strong> | correo: <strong>admin@alejaNails.com</strong> | contraseña: <strong>admin123</strong>");
    } else {
        // Actualizar correo si está vacío
        $pdo->exec("UPDATE administrador SET correo='admin@alejaNails.com' WHERE correo IS NULL OR correo='' LIMIT 1");
        info("Ya existe un administrador — correo actualizado si estaba vacío");
        $admin = $pdo->query("SELECT nombre, usuario, correo FROM administrador LIMIT 1")->fetch();
        info("Admin: <strong>{$admin['nombre']}</strong> | usuario: <strong>{$admin['usuario']}</strong> | correo: <strong>{$admin['correo']}</strong>");
    }
} catch (PDOException $e) {
    err("Error con administrador: " . htmlspecialchars($e->getMessage()));
}

// 4. Crear cliente de prueba si no existe
try {
    $count = $pdo->query("SELECT COUNT(*) FROM cliente")->fetchColumn();
    if ($count == 0) {
        $hash = password_hash('cliente123', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare(
            "INSERT INTO cliente (nombre, telefono, correo, password) VALUES (?,?,?,?)"
        );
        $stmt->execute(['Laura Martínez', '3109876543', 'laura@correo.com', $hash]);
        ok("Cliente de prueba creado: correo=<strong>laura@correo.com</strong> / password=<strong>cliente123</strong>");
    } else {
        info("Ya existen clientes en la BD");
    }
} catch (PDOException $e) {
    err("Error creando cliente: " . htmlspecialchars($e->getMessage()));
}

// 5. Insertar servicios si no existen
try {
    $count = $pdo->query("SELECT COUNT(*) FROM servicio")->fetchColumn();
    if ($count == 0) {
        $adminId = $pdo->query("SELECT id_administrador FROM administrador LIMIT 1")->fetchColumn();
        $stmt = $pdo->prepare(
            "INSERT INTO servicio (nombre_servicio, descripcion, precio, id_administrador) VALUES (?,?,?,?)"
        );
        $servicios = [
            ['Manicure clásica',  'Limpieza, corte y esmaltado tradicional',           25000],
            ['Pedicure spa',      'Exfoliación, hidratación y esmaltado de pies',      35000],
            ['Uñas acrílicas',    'Extensión de uñas en acrílico con diseño incluido', 60000],
            ['Semipermanente',    'Esmaltado de larga duración con lámpara UV',        40000],
            ['Retiro acrílico',   'Retiro seguro de uñas acrílicas o gel',             20000],
        ];
        foreach ($servicios as $s) {
            $stmt->execute([$s[0], $s[1], $s[2], $adminId]);
        }
        ok("Catálogo de servicios insertado (" . count($servicios) . " servicios)");
    } else {
        info("Servicios ya existen ($count registros)");
    }
} catch (PDOException $e) {
    err("Error insertando servicios: " . htmlspecialchars($e->getMessage()));
}

// 6. Resumen
echo "<hr><h3>Credenciales de acceso</h3>
<table>
<tr><th>Rol</th><th>Correo / Usuario</th><th>Contraseña</th></tr>
<tr><td><strong>Admin</strong></td><td>admin@alejaNails.com <br><small>(o simplemente: admin)</small></td><td>admin123</td></tr>
<tr><td><strong>Cliente</strong></td><td>laura@correo.com</td><td>cliente123</td></tr>
</table>
<br>
<a href='/Mi-proyecto-formativo/public/index.php?action=login'
   style='background:#c0375a;color:white;padding:12px 28px;border-radius:8px;
          text-decoration:none;font-family:sans-serif'>
   Ir al Login →
</a>
<br><br>
<p style='color:orange'><strong>⚠️ Elimina instalar.php y ver_estructura.php cuando termines.</strong></p>
</body></html>";
?>
