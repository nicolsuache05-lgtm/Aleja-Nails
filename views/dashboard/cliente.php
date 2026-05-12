<?php
$titulo = 'Mi Panel';
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';
?>

<main>

  <h1 style="font-size:22px;font-weight:600;color:#c0375a;margin-bottom:1.5rem">
    Bienvenida, <?= htmlspecialchars($_SESSION['nombre'] ?? '') ?> 💅
  </h1>

  <!-- Stats rápidas -->
  <div class="stats-grid">
    <div class="stat-card">
      <div class="label">Mis reservas</div>
      <div class="value"><?= count($reservas ?? []) ?></div>
    </div>
    <div class="stat-card">
      <div class="label">Pendientes</div>
      <div class="value"><?= count(array_filter($reservas ?? [], fn($r) => $r['estado'] === 'pendiente')) ?></div>
    </div>
    <div class="stat-card">
      <div class="label">Completadas</div>
      <div class="value"><?= count(array_filter($reservas ?? [], fn($r) => $r['estado'] === 'completada')) ?></div>
    </div>
  </div>

  <!-- Acciones rápidas -->
  <div class="card">
    <h2>¿Qué deseas hacer?</h2>
    <div style="display:flex;gap:12px;flex-wrap:wrap">
      <a href="index.php?action=agendarCita" class="btn btn-primary">📅 Agendar nueva cita</a>
      <a href="index.php?action=misReservas" class="btn btn-outline">📋 Ver mis reservas</a>
    </div>
  </div>

  <!-- Últimas reservas -->
  <div class="card">
    <h2>📅 Mis últimas reservas</h2>

    <?php if (empty($reservas)): ?>
      <p style="color:#b07090">Aún no tienes reservas. <a href="index.php?action=agendarCita" style="color:#c0375a">¡Agenda tu primera cita!</a></p>
    <?php else: ?>
      <div class="tabla-wrap">
        <table>
          <thead>
            <tr>
              <th>Servicio</th>
              <th>Fecha</th>
              <th>Hora</th>
              <th>Estado</th>
              <th>Precio</th>
              <th>Acción</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach (array_slice($reservas, 0, 5) as $r): ?>
            <tr>
              <td><?= htmlspecialchars($r['nombre_servicio']) ?></td>
              <td><?= htmlspecialchars($r['fecha']) ?></td>
              <td><?= htmlspecialchars($r['hora']) ?></td>
              <td><span class="badge badge-<?= $r['estado'] ?>"><?= ucfirst($r['estado']) ?></span></td>
              <td>$<?= number_format($r['precio'], 0, ',', '.') ?></td>
              <td>
                <?php if ($r['estado'] === 'pendiente'): ?>
                  <a href="index.php?action=cancelarReserva&id=<?= $r['id_reserva'] ?>"
                     class="btn btn-danger btn-sm"
                     onclick="return confirm('¿Cancelar esta reserva?')">Cancelar</a>
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

  <!-- Catálogo de servicios -->
  <?php if (!empty($servicios)): ?>
  <div class="card">
    <h2>💅 Nuestros servicios</h2>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:14px">
      <?php foreach ($servicios as $s): ?>
      <div style="background:#fdf0f5;border-radius:14px;padding:16px">
        <div style="font-weight:600;color:#c0375a;margin-bottom:4px"><?= htmlspecialchars($s['nombre_servicio']) ?></div>
        <div style="font-size:12px;color:#8a5068;margin-bottom:8px"><?= htmlspecialchars($s['descripcion'] ?? '') ?></div>
        <div style="font-weight:600;color:#4a2030">$<?= number_format($s['precio'], 0, ',', '.') ?></div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endif; ?>

</main>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
