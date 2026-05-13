<?php
$titulo = 'Mis Reservas';
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';
?>

<main>

  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem">
    <h1 style="font-size:22px;font-weight:600;color:#c0375a">📅 Mis Reservas</h1>
    <a href="index.php?action=agendarCita" class="btn btn-primary">+ Nueva reserva</a>
  </div>

  <div class="card">
    <?php if (empty($reservas)): ?>
      <p style="color:#b07090;text-align:center;padding:2rem">
        No tienes reservas aún. <a href="index.php?action=agendarCita" style="color:#c0375a">¡Agenda tu primera cita!</a>
      </p>
    <?php else: ?>
      <div class="tabla-wrap">
        <table>
          <thead>
            <tr>
              <th>#</th>
              <th>Servicio</th>
              <th>Fecha</th>
              <th>Hora</th>
              <th>Estado</th>
              <th>Precio</th>
              <th>Acción</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($reservas as $r): ?>
            <tr>
              <td><?= $r['id_reserva'] ?></td>
              <td><?= htmlspecialchars($r['nombre_servicio']) ?></td>
              <td><?= htmlspecialchars($r['fecha']) ?></td>
              <td><?= htmlspecialchars($r['hora']) ?></td>
              <td><span class="badge badge-<?= $r['estado'] ?>"><?= ucfirst($r['estado']) ?></span></td>
              <td>$<?= number_format($r['precio'], 0, ',', '.') ?></td>
              <td>
                <?php if ($r['estado'] === 'pendiente'): ?>
                  <a href="index.php?action=cancelarReserva&id=<?= $r['id_reserva'] ?>"
                     class="btn btn-danger btn-sm"
                     onclick="return confirm('¿Segura que deseas cancelar esta reserva?')">
                    Cancelar
                  </a>
                <?php else: ?>
                  <span style="color:#b07090;font-size:12px">—</span>
                <?php endif; ?>
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