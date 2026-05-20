<?php
$titulo = 'Reservas';
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';
?>

<main>

  <h1 style="font-size:22px;font-weight:600;color:#c0375a;margin-bottom:1.5rem">📅 Reservas</h1>

  <div class="card">
    <?php if (empty($reservas)): ?>
      <p style="color:#b07090">No hay reservas registradas.</p>
    <?php else: ?>
      <div class="tabla-wrap">
        <table>
          <thead>
            <tr>
              <th>#</th>
              <th>Cliente</th>
              <th>Servicios</th>
              <th>Total</th>
              <th>Fecha</th>
              <th>Hora</th>
              <th>Estado</th>
              <th>Cambiar estado</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($reservas as $r): ?>
            <tr>
              <td><?= $r['id_reserva'] ?></td>
              <td><?= htmlspecialchars($r['nombre_cliente']) ?></td>
              <td>
                <?php if (!empty($r['servicios']) && count($r['servicios']) > 1): ?>
                  <ul style="margin:0;padding:0 0 0 14px;font-size:12px;color:#4a2030">
                    <?php foreach ($r['servicios'] as $srv): ?>
                      <li><?= htmlspecialchars($srv['nombre_servicio']) ?></li>
                    <?php endforeach; ?>
                  </ul>
                <?php else: ?>
                  <?= htmlspecialchars($r['nombre_servicio']) ?>
                <?php endif; ?>
              </td>
              <td style="font-weight:700;color:#4a2030">
                $<?= number_format((float)$r['precio'], 0, ',', '.') ?>
              </td>
              <td><?= htmlspecialchars($r['fecha']) ?></td>
              <td><?= htmlspecialchars($r['hora']) ?></td>
              <td><span class="badge badge-<?= $r['estado'] ?>"><?= ucfirst($r['estado']) ?></span></td>
              <td>
                <form action="index.php?action=actualizarReserva" method="POST" style="display:flex;gap:6px">
                  <input type="hidden" name="id" value="<?= $r['id_reserva'] ?>">
                  <select name="estado" style="padding:5px 8px;border-radius:8px;border:1px solid #f4c0d1;font-size:12px">
                    <?php foreach (['pendiente','confirmada','en_curso','completada','cancelada'] as $e): ?>
                      <option value="<?= $e ?>" <?= $r['estado'] === $e ? 'selected' : '' ?>><?= ucfirst($e) ?></option>
                    <?php endforeach; ?>
                  </select>
                  <button type="submit" class="btn btn-primary btn-sm">Guardar</button>
                </form>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>

</main>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>