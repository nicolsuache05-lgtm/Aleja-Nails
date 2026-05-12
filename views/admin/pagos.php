<?php
$titulo = 'Pagos';
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';
?>

<main>

  <h1 style="font-size:22px;font-weight:600;color:#c0375a;margin-bottom:1.5rem">💰 Pagos</h1>

  <div class="card">
    <?php if (empty($pagos)): ?>
      <p style="color:#b07090">No hay pagos registrados.</p>
    <?php else: ?>
      <div class="tabla-wrap">
        <table>
          <thead>
            <tr>
              <th>#</th>
              <th>Cliente</th>
              <th>Servicio</th>
              <th>Fecha pago</th>
              <th>Método</th>
              <th>Valor</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($pagos as $p): ?>
            <tr>
              <td><?= $p['id_pago'] ?></td>
              <td><?= htmlspecialchars($p['nombre_cliente']) ?></td>
              <td><?= htmlspecialchars($p['nombre_servicio']) ?></td>
              <td><?= htmlspecialchars($p['fecha_pago']) ?></td>
              <td><?= htmlspecialchars($p['metodo_pago']) ?></td>
              <td>$<?= number_format($p['valor_pagado'] ?? 0, 0, ',', '.') ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>

</main>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
