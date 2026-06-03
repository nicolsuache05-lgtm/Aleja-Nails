<?php
$titulo = 'Reservas';
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';
?>

<main>

  <div class="page-title">
    <span>📅</span> Reservas
  </div>

  <div class="card">
    <?php if (empty($reservas)): ?>
      <div class="empty-state">
        <div class="empty-icon">📭</div>
        <p>No hay reservas registradas.</p>
      </div>
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
              <td style="color:var(--text-muted);font-size:12px">#<?= $r['id_reserva'] ?></td>

              <td>
                <div style="display:flex;align-items:center;gap:8px">
                  <div style="width:30px;height:30px;border-radius:50%;
                              background:linear-gradient(135deg,#fce4ef,#f4c0d1);
                              display:flex;align-items:center;justify-content:center;
                              font-size:13px;flex-shrink:0">💅</div>
                  <span style="font-weight:500"><?= htmlspecialchars($r['nombre_cliente']) ?></span>
                </div>
              </td>

              <td>
                <?php if (!empty($r['servicios']) && count($r['servicios']) > 1): ?>
                  <div style="display:flex;flex-direction:column;gap:2px">
                    <?php foreach ($r['servicios'] as $srv): ?>
                      <span style="font-size:12px;color:var(--text-soft)">
                        • <?= htmlspecialchars($srv['nombre_servicio']) ?>
                      </span>
                    <?php endforeach; ?>
                  </div>
                <?php else: ?>
                  <span style="font-size:13px"><?= htmlspecialchars($r['nombre_servicio']) ?></span>
                <?php endif; ?>
              </td>

              <td style="font-weight:700;color:var(--pink-dark)">
                $<?= number_format((float)$r['precio'], 0, ',', '.') ?>
              </td>

              <td><?= htmlspecialchars(date('d/m/Y', strtotime($r['fecha']))) ?></td>
              <td style="color:var(--text-soft)"><?= htmlspecialchars($r['hora']) ?></td>

              <td>
                <span class="badge badge-<?= $r['estado'] ?>">
                  <?= ucfirst(str_replace('_',' ',$r['estado'])) ?>
                </span>
              </td>

              <td>
                <form action="index.php?action=actualizarReserva" method="POST"
                      style="display:flex;gap:6px;align-items:center">
                  <input type="hidden" name="id" value="<?= $r['id_reserva'] ?>">
                  <select name="estado" style="
                    padding: 6px 10px;
                    border-radius: 8px;
                    border: 1.5px solid var(--pink-border);
                    font-family: 'Poppins', sans-serif;
                    font-size: 12px;
                    color: var(--text);
                    background: var(--pink-bg);
                    outline: none;
                    cursor: pointer;
                  ">
                    <?php foreach (['pendiente','confirmada','en_curso','completada','cancelada'] as $e): ?>
                      <option value="<?= $e ?>" <?= $r['estado'] === $e ? 'selected' : '' ?>>
                        <?= ucfirst(str_replace('_',' ',$e)) ?>
                      </option>
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
