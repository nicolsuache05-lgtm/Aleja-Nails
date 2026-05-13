<?php
// views/dashboard/usuario_crear.php

$titulo = 'Mis Citas';

require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';

$servicios = $servicios ?? [];
$citas = $citas ?? [];
?>

<main>

  <h1>Mis Citas 💅</h1>

  <div class="card">
    <h2>Reservar nueva cita</h2>

    <form action="index.php?action=agendarCita" method="POST">
      <div class="form-row">

        <div class="form-group">
          <label>Servicio</label>

          <select name="id_servicio" required>
            <option value="">— Selecciona —</option>

            <?php foreach ($servicios as $s): ?>
              <option value="<?= htmlspecialchars($s['id_servicio'] ?? '') ?>">
                <?= htmlspecialchars($s['nombre_servicio'] ?? 'Sin nombre') ?>
                —
                $<?= number_format((float)($s['precio'] ?? 0), 0, ',', '.') ?>
              </option>
            <?php endforeach; ?>

          </select>
        </div>

        <div class="form-group">
          <label>Técnica</label>
          <select name="tecnica" required>
            <option value="Alejandra">Alejandra (dueña)</option>
            <option value="Valentina">Valentina</option>
            <option value="Daniela">Daniela</option>
          </select>
        </div>

      </div>

      <div class="form-row">

        <div class="form-group">
          <label>Fecha</label>
          <input type="date" name="fecha" required min="<?= date('Y-m-d') ?>">
        </div>

        <div class="form-group">
          <label>Hora</label>
          <select name="hora" required>
            <?php
              $horas = [
                '08:00', '08:30', '09:00', '09:30',
                '10:00', '10:30', '11:00', '11:30',
                '14:00', '14:30', '15:00', '15:30',
                '16:00', '16:30'
              ];

              foreach ($horas as $h):
            ?>
              <option value="<?= $h ?>"><?= $h ?></option>
            <?php endforeach; ?>
          </select>
        </div>

      </div>

      <div class="form-group">
        <label>Notas adicionales</label>
        <textarea 
          name="notas" 
          rows="2" 
          placeholder="Diseño especial, alergias, etc." 
          style="resize:none;"
        ></textarea>
      </div>

      <button type="submit" class="btn btn-primary">
        Reservar cita ✦
      </button>
    </form>
  </div>

  <div class="card">
    <h2>Historial de citas</h2>

    <?php if (empty($citas)): ?>

      <p style="color:#b07090;font-size:14px;">
        Aún no tienes citas reservadas. ¡Agenda la primera! 🌸
      </p>

    <?php else: ?>

      <div class="tabla-wrap">
        <table>
          <thead>
            <tr>
              <th>Servicio</th>
              <th>Fecha</th>
              <th>Hora</th>
              <th>Técnica</th>
              <th>Precio</th>
              <th>Estado</th>
              <th>Acción</th>
            </tr>
          </thead>

          <tbody>
            <?php foreach ($citas as $c): ?>
              <tr>
                <td><?= htmlspecialchars($c['servicio'] ?? '') ?></td>

                <td>
                  <?= !empty($c['fecha']) ? date('d/m/Y', strtotime($c['fecha'])) : '' ?>
                </td>

                <td><?= htmlspecialchars(substr($c['hora'] ?? '', 0, 5)) ?></td>

                <td><?= htmlspecialchars($c['tecnica'] ?? '') ?></td>

                <td>
                  $<?= number_format((float)($c['precio'] ?? 0), 0, ',', '.') ?>
                </td>

                <td>
                  <span class="badge badge-<?= htmlspecialchars($c['estado'] ?? 'pendiente') ?>">
                    <?= ucfirst(htmlspecialchars($c['estado'] ?? 'pendiente')) ?>
                  </span>
                </td>

                <td>
                  <?php if (in_array(($c['estado'] ?? ''), ['pendiente', 'confirmada'])): ?>
                    <a 
                      href="index.php?action=cancelarReserva&id=<?= htmlspecialchars($c['id_reserva'] ?? '') ?>"
                      class="btn btn-danger btn-sm"
                      onclick="return confirm('¿Cancelar esta cita?')"
                    >
                      Cancelar
                    </a>
                  <?php else: ?>
                    <span style="color:#c090a8;font-size:12px;">—</span>
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