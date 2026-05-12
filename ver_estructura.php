<?php
/**
 * ver_estructura.php — Muestra la estructura real de la BD
 * Accede a: http://localhost:8081/Mi-proyecto-formativo/ver_estructura.php
 * ELIMINA este archivo después de usarlo.
 */

$pdo = new PDO(
    "mysql:host=127.0.0.1;port=3320;dbname=aleja-nails;charset=utf8mb4",
    "root", "",
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
);

echo "<!DOCTYPE html><html><head><meta charset='UTF-8'>
<style>
body{font-family:monospace;padding:20px;background:#1e1e1e;color:#d4d4d4}
h2{color:#f48fb1} h3{color:#80cbc4;margin-top:30px}
table{border-collapse:collapse;margin-bottom:10px;width:100%}
th{background:#333;color:#f48fb1;padding:6px 12px;text-align:left}
td{padding:5px 12px;border-bottom:1px solid #333}
</style></head><body>
<h2>💅 Estructura real de aleja-nails</h2>";

$tablas = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);

foreach ($tablas as $tabla) {
    $count = $pdo->query("SELECT COUNT(*) FROM `$tabla`")->fetchColumn();
    echo "<h3>📋 $tabla &nbsp;<small style='color:#888'>($count filas)</small></h3>";
    echo "<table><tr><th>Campo</th><th>Tipo</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    $cols = $pdo->query("DESCRIBE `$tabla`")->fetchAll();
    foreach ($cols as $col) {
        echo "<tr>
            <td><strong>{$col['Field']}</strong></td>
            <td>{$col['Type']}</td>
            <td>{$col['Null']}</td>
            <td>{$col['Key']}</td>
            <td>{$col['Default']}</td>
            <td>{$col['Extra']}</td>
        </tr>";
    }
    echo "</table>";
}

echo "</body></html>";
?>
