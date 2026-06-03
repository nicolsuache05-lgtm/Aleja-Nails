<?php
$titulo = 'Mi Panel';
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';

$totalReservas    = count($reservas ?? []);
$totalPendientes  = count(array_filter($reservas ?? [], fn($r) => $r['estado'] === 'pendiente'));
$totalCompletadas = count(array_filter($reservas ?? [], fn($r) => $r['estado'] === 'completada'));
?>

<main>

  <!-- Saludo -->
  <div style="margin-bottom:1.75rem">
    <div class="page-title" style="margin-bottom:4px">
      <span>💅</span> Bienvenida, <?= htmlspecialchars($_SESSION['nombre'] ?? '') ?>
    </div>
    <p style="color:var(--text-muted);font-size:13px">
      Aquí puedes gestionar tus citas y ver tus servicios favoritos.
    </p>
  </div>

  <!-- Stats -->
  <div class="stats-grid">

    <div class="stat-card stat-pink">
      <div class="stat-icon">📅</div>
      <div class="stat-info">
        <div class="label">Total citas</div>
        <div class="value"><?= $totalReservas ?></div>
      </div>
    </div>

    <div class="stat-card stat-orange">
      <div class="stat-icon">⏳</div>
      <div class="stat-info">
        <div class="label">Pendientes</div>
        <div class="value"><?= $totalPendientes ?></div>
      </div>
    </div>

    <div class="stat-card stat-green">
      <div class="stat-icon">✅</div>
      <div class="stat-info">
        <div class="label">Completadas</div>
        <div class="value"><?= $totalCompletadas ?></div>
      </div>
    </div>

  </div>

  <!-- Acciones rápidas -->
  <div class="card">
    <h2>⚡ ¿Qué deseas hacer?</h2>
    <div style="display:flex;gap:12px;flex-wrap:wrap">
      <a href="index.php?action=agendarCita" class="btn btn-primary" style="font-size:14px;padding:11px 24px">
        📅 Agendar nueva cita
      </a>
      <a href="index.php?action=misReservas" class="btn btn-outline" style="font-size:14px;padding:11px 24px">
        📋 Ver mis reservas
      </a>
    </div>
  </div>

  <!-- Últimas reservas -->
  <div class="card">
    <h2>📅 Mis últimas reservas</h2>

    <?php if (empty($reservas)): ?>
      <div class="empty-state">
        <div class="empty-icon">📭</div>
        <p>Aún no tienes reservas agendadas.</p>
        <a href="index.php?action=agendarCita" class="btn btn-primary">📅 Agendar mi primera cita</a>
      </div>
    <?php else: ?>
      <div class="tabla-wrap">
        <table>
          <thead>
            <tr>
              <th>Servicio</th>
              <th>Fecha</th>
              <th>Hora</th>
              <th>Estado</th>
              <th>Total</th>
              <th>Acción</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach (array_slice($reservas, 0, 5) as $r): ?>
            <tr>
              <td style="font-weight:500"><?= htmlspecialchars($r['nombre_servicio']) ?></td>
              <td><?= htmlspecialchars(date('d/m/Y', strtotime($r['fecha']))) ?></td>
              <td><?= htmlspecialchars($r['hora']) ?></td>
              <td><span class="badge badge-<?= $r['estado'] ?>"><?= ucfirst(str_replace('_',' ',$r['estado'])) ?></span></td>
              <td style="font-weight:600;color:var(--pink-dark)">$<?= number_format((float)$r['precio'], 0, ',', '.') ?></td>
              <td>
                <?php if ($r['estado'] === 'pendiente'): ?>
                  <?php if (empty($r['pagado'])): ?>
                    <a href="index.php?action=pagar&id=<?= $r['id_reserva'] ?>"
                       class="btn btn-primary btn-sm">💳 Pagar</a>
                  <?php else: ?>
                    <span style="font-size:12px;color:#2e7d32;font-weight:600">✅ Pagado</span>
                  <?php endif; ?>
                <?php else: ?>
                  <span style="color:var(--text-muted);font-size:12px">—</span>
                <?php endif; ?>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <?php if (count($reservas) > 5): ?>
        <div style="text-align:center;margin-top:14px">
          <a href="index.php?action=misReservas" class="btn btn-outline btn-sm">Ver todas las reservas →</a>
        </div>
      <?php endif; ?>
    <?php endif; ?>
  </div>

  <!-- Catálogo de servicios -->
  <?php if (!empty($servicios)): ?>
  <div class="card">
    <h2>💅 Nuestros servicios</h2>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(190px,1fr));gap:14px">
      <?php
        $iconos = ['Manicure'=>'💅','Pedicure'=>'👣','Capilar'=>'💆🏽‍♀️','Otros'=>'✨'];
        foreach ($servicios as $s):
          $cat = $s['categoria'] ?? 'Otros';
          $ico = $iconos[$cat] ?? '✨';
      ?>
      <div style="
        background: linear-gradient(160deg, #fdf5f8, white);
        border: 1px solid var(--pink-border);
        border-radius: 16px;
        padding: 16px;
        transition: all .2s;
      "
        onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 8px 24px rgba(200,50,90,.13)'"
        onmouseout="this.style.transform='';this.style.boxShadow=''">
        <div style="font-size:24px;margin-bottom:8px"><?= $ico ?></div>
        <div style="font-weight:600;color:var(--pink-dark);font-size:13px;margin-bottom:3px">
          <?= htmlspecialchars($s['nombre_servicio']) ?>
        </div>
        <div style="font-size:11px;color:var(--text-muted);margin-bottom:10px;line-height:1.4">
          <?= htmlspecialchars($s['descripcion'] ?? '') ?>
        </div>
        <div style="font-weight:700;color:var(--text);font-size:15px">
          $<?= number_format((float)$s['precio'], 0, ',', '.') ?>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endif; ?>

</main>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
