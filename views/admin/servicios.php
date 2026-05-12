<?php
$titulo = 'Servicios';
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';

$iconos = ['Manicure' => '💅', 'Pedicure' => '👣', 'Capilar' => '💆🏽‍♀️', 'Otros' => '✨'];

// Agrupar por categoría
$grupos = [];
foreach ($servicios as $s) {
    $grupos[$s['categoria'] ?? 'Otros'][] = $s;
}
?>

<main>

  <h1 style="font-size:22px;font-weight:600;color:#c0375a;margin-bottom:1.5rem">💅 Servicios</h1>

  <?php foreach ($grupos as $cat => $items): ?>
  <div class="card">
    <h2><?= ($iconos[$cat] ?? '✨') . ' ' . $cat ?></h2>

    <div class="tabla-wrap">
      <table>
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Descripción</th>
            <th style="width:160px">Precio (COP)</th>
            <th style="width:100px">Acción</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($items as $s): ?>
          <tr>
            <form action="/Mi-proyecto-formativo/public/index.php?action=actualizarServicio" method="POST">
              <input type="hidden" name="id_servicio" value="<?= $s['id_servicio'] ?>">

              <td><?= htmlspecialchars($s['nombre_servicio']) ?></td>

              <td>
                <input type="text" name="descripcion"
                       value="<?= htmlspecialchars($s['descripcion'] ?? '') ?>"
                       style="width:100%;padding:6px 10px;border:1.5px solid #f4c0d1;
                              border-radius:8px;font-family:'Poppins',sans-serif;font-size:13px;
                              background:#fdf0f5;color:#4a2030">
              </td>

              <td>
                <input type="number" name="precio"
                       value="<?= $s['precio'] ?>"
                       min="0" step="500" required
                       style="width:130px;padding:6px 10px;border:1.5px solid #f4c0d1;
                              border-radius:8px;font-family:'Poppins',sans-serif;font-size:13px;
                              background:#fdf0f5;color:#4a2030;font-weight:600">
              </td>

              <td>
                <button type="submit" class="btn btn-primary btn-sm">Guardar</button>
              </td>
            </form>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
  <?php endforeach; ?>

</main>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
