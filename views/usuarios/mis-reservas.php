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

  <?php if (!empty($_SESSION['flash_ok'])): ?>
    <div style="background:#e8f5e9;border:1px solid #a5d6a7;color:#2e7d32;
                padding:12px 16px;border-radius:10px;margin-bottom:1rem">
      <?= htmlspecialchars($_SESSION['flash_ok']) ?>
    </div>
    <?php unset($_SESSION['flash_ok']); ?>
  <?php endif; ?>

  <?php if (!empty($_SESSION['flash_error'])): ?>
    <div style="background:#fde8e8;border:1px solid #f5c6cb;color:#a32d2d;
                padding:12px 16px;border-radius:10px;margin-bottom:1rem">
      <?= htmlspecialchars($_SESSION['flash_error']) ?>
    </div>
    <?php unset($_SESSION['flash_error']); ?>
  <?php endif; ?>

  <div class="card">
    <?php if (empty($reservas)): ?>
      <p style="color:#b07090;text-align:center;padding:2rem">
        No tienes reservas aún.
        <a href="index.php?action=agendarCita" style="color:#c0375a">
          ¡Agenda tu primera cita!
        </a>
      </p>
    <?php else: ?>
      <div class="tabla-wrap">
        <table>
          <thead>
            <tr>
              <th>#</th>
              <th>Servicios</th>
              <th>Fecha</th>
              <th>Hora</th>
              <th>Estado</th>
              <th>Total</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($reservas as $r): ?>
            <tr>
              <td><?= $r['id_reserva'] ?></td>

              <!-- Servicios: lista si hay varios -->
              <td>
                <?php if (!empty($r['servicios']) && count($r['servicios']) > 1): ?>
                  <ul style="margin:0;padding:0 0 0 14px;font-size:12px;color:#4a2030">
                    <?php foreach ($r['servicios'] as $srv): ?>
                      <li style="margin-bottom:2px">
                        <?= htmlspecialchars($srv['nombre_servicio']) ?>
                        <span style="color:#8a5068">
                          — $<?= number_format((float)$srv['precio'], 0, ',', '.') ?>
                        </span>
                      </li>
                    <?php endforeach; ?>
                  </ul>
                <?php else: ?>
                  <span style="font-size:13px">
                    <?= htmlspecialchars($r['nombre_servicio']) ?>
                  </span>
                <?php endif; ?>
              </td>

              <td><?= htmlspecialchars($r['fecha']) ?></td>
              <td><?= htmlspecialchars($r['hora']) ?></td>

              <td>
                <span class="badge badge-<?= $r['estado'] ?>">
                  <?= ucfirst($r['estado']) ?>
                </span>
              </td>

              <!-- Total -->
              <td style="font-weight:700;color:#4a2030;white-space:nowrap">
                $<?= number_format((float)$r['precio'], 0, ',', '.') ?>
              </td>

              <!-- Acciones -->
              <td style="white-space:nowrap">
                <?php if ($r['estado'] === 'pendiente'): ?>

                  <?php if (empty($r['pagado'])): ?>
                    <!-- Botón pagar -->
                    <a href="index.php?action=pagar&id=<?= $r['id_reserva'] ?>"
                       class="btn btn-primary btn-sm"
                       style="margin-bottom:4px;display:inline-block">
                      💳 Pagar
                    </a>
                  <?php else: ?>
                    <span style="color:#2e7d32;font-size:12px;font-weight:600">✅ Pagado</span>
                  <?php endif; ?>

                  <br>
                  <a href="index.php?action=cancelarReserva&id=<?= $r['id_reserva'] ?>"
                     class="btn btn-danger btn-sm"
                     onclick="return confirm('¿Segura que deseas cancelar esta reserva?')">
                    Cancelar
                  </a>

                <?php elseif ($r['estado'] === 'confirmada'): ?>
                  <span style="color:#2e7d32;font-size:12px;font-weight:600">✅ Confirmada</span>

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
