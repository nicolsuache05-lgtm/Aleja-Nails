<?php
/**
 * insertar_servicios.php — Inserta el catálogo de servicios
 * Accede a: http://localhost:8081/Mi-proyecto-formativo/insertar_servicios.php
 * ELIMINA este archivo después de ejecutarlo.
 */

$pdo = new PDO(
    "mysql:host=127.0.0.1;port=3320;dbname=aleja-nails;charset=utf8mb4",
    "root", "",
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
);

echo "<!DOCTYPE html><html><head><meta charset='UTF-8'>
<style>body{font-family:sans-serif;max-width:600px;margin:40px auto;padding:20px}
h2{color:#c0375a}</style></head><body><h2>💅 Insertar Servicios</h2>";

// Verificar si ya hay servicios
$count = $pdo->query("SELECT COUNT(*) FROM servicio")->fetchColumn();
if ($count > 0) {
    echo "<p style='color:orange'>⚠️ Ya existen $count servicios. Se omite la inserción.</p>";
} else {
    // Obtener id del primer admin
    $adminId = $pdo->query("SELECT id_administrador FROM administrador LIMIT 1")->fetchColumn();
    if (!$adminId) {
        echo "<p style='color:red'>❌ No hay administrador. Ejecuta primero instalar.php</p>";
        echo "</body></html>"; exit;    
    }

    $servicios = [
        // Manicure 
        ['Manicure clásica',       'Manicure', 'Corte y esmaltado tradicional',      20000, $adminId],
        ['Manicure semipermanente', 'Manicure', 'Esmaltado con lámpara UV',           45000, $adminId],
        ['Uñas acrílicas',         'Manicure', 'Extensión en acrílico',               60000, $adminId],
        ['Retiro acrílico',        'Manicure', 'Retiro seguro de acrílico',           20000, $adminId],
        // Pedicure
        ['Pedicure clásico',       'Pedicure', 'Corte y esmaltado de pies',           20000, $adminId],
        ['Pedicure spa',           'Pedicure', 'Exfoliación e hidratación',           40000, $adminId],
        ['Pedicure semipermanente','Pedicure', 'Esmaltado permanente en pies',        50000, $adminId],
        // Capilar
        ['Corte de cabello',       'Capilar',  'Corte personalizado',                 15000, $adminId],
        ['Tinte',                  'Capilar',  'Coloración completa',                 25000, $adminId],
        ['Keratina',               'Capilar',  'Tratamiento alisador',               120000, $adminId],
        ['Hidratación capilar',    'Capilar',  'Mascarilla nutritiva',                30000, $adminId],
    ];

    // Ampliar columna descripcion si sigue siendo varchar(30)
    $pdo->exec("ALTER TABLE servicio MODIFY COLUMN descripcion VARCHAR(120) DEFAULT NULL");

    // Agregar columna categoria si no existe
    $cols = $pdo->query("DESCRIBE servicio")->fetchAll(PDO::FETCH_COLUMN);
    if (!in_array('categoria', $cols)) {
        $pdo->exec("ALTER TABLE servicio ADD COLUMN categoria VARCHAR(50) DEFAULT 'Manicure' AFTER descripcion");
        echo "<p style='color:green'>✅ Columna <strong>categoria</strong> agregada a servicio</p>";
    }

    $stmt = $pdo->prepare(
        "INSERT INTO servicio (nombre_servicio, categoria, descripcion, precio, id_administrador) VALUES (?,?,?,?,?)"
    );

    foreach ($servicios as $s) {
        $stmt->execute($s);
        echo "<p style='color:green'>✅ Insertado: <strong>[{$s[1]}]</strong> {$s[0]}</p>";
    }
}

echo "<br><a href='/Mi-proyecto-formativo/public/index.php?action=agendarCita'
   style='background:#c0375a;color:white;padding:12px 24px;border-radius:8px;text-decoration:none'>
   Ver formulario de cita →
</a>
<p style='color:orange;margin-top:16px'><strong>⚠️ Elimina este archivo después de usarlo.</strong></p>
</body></html>";
?>
