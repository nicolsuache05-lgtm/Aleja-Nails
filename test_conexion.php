<?php
/**
 * test_conexion.php — Verificar conexión a la BD
 * Accede a: http://localhost:8081/Mi-proyecto-formativo/test_conexion.php
 * ELIMINA este archivo cuando confirmes que todo funciona.
 */

require_once __DIR__ . '/config/database.php';

try {
    $db = Database::getConnection();

    // Verificar tablas existentes
    $tablas = $db->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);

    echo "<h2 style='color:green'>✅ Conexión exitosa a la BD <strong>aleja-nails</strong></h2>";
    echo "<p>Tablas encontradas:</p><ul>";
    foreach ($tablas as $tabla) {
        $count = $db->query("SELECT COUNT(*) FROM `$tabla`")->fetchColumn();
        echo "<li><strong>$tabla</strong> — $count registros</li>";
    }
    echo "</ul>";

    // Verificar usuario admin
    $admin = $db->query("SELECT nombre, correo, rol FROM usuarios WHERE rol='admin' LIMIT 1")->fetch();
    if ($admin) {
        echo "<p style='color:green'>✅ Usuario admin encontrado: <strong>{$admin['nombre']}</strong> ({$admin['correo']})</p>";
    } else {
        echo "<p style='color:orange'>⚠️ No hay usuario admin. Ejecuta el SQL de datos de prueba.</p>";
    }

} catch (Exception $e) {
    echo "<h2 style='color:red'>❌ Error de conexión</h2>";
    echo "<pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
}
?>
