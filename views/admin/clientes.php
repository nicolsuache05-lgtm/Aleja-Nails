<?php
$titulo = 'Clientes';
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';
?>

<main>

  <h1 style="font-size:22px;font-weight:600;color:#c0375a;margin-bottom:1.5rem">👥 Clientes</h1>

  <div class="card">
    <?php if (empty($clientes)): ?>
      <p style="color:#b07090">No hay clientes registrados.</p>
    <?php else: ?>
      <div class="tabla-wrap">
        <table>
          <thead>
            <tr>
              <th>#</th>
              <th>Nombre</th>
              <th>Correo</th>
              <th>Teléfono</th>
              <th>Estado</th>
              <th>Acción</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($clientes as $c): ?>
            <tr>
              <td><?= $c['id_cliente'] ?></td>
              <td><?= htmlspecialchars($c['nombre']) ?></td>
              <td><?= htmlspecialchars($c['correo']) ?></td>
              <td><?= htmlspecialchars($c['telefono'] ?? '—') ?></td>
              <td>
                <?php $activo = $c['activo'] ?? 1; ?>
                <span class="badge badge-<?= $activo ? 'activo' : 'inactivo' ?>">
                  <?= $activo ? 'Activo' : 'Inactivo' ?>
                </span>
              </td>
              <td>
                <a href="index.php?action=toggleCliente&id=<?= $c['id_cliente'] ?>"
                   class="btn btn-sm <?= ($c['activo'] ?? 1) ? 'btn-danger' : 'btn-outline' ?>"
                   onclick="return confirm('¿Cambiar estado del cliente?')">
                  <?= ($c['activo'] ?? 1) ? 'Desactivar' : 'Activar' ?>
                </a>
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