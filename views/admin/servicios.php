<?php
$titulo = 'Servicios';
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';

$iconos = ['Manicure'=>'💅','Pedicure'=>'👣','Capilar'=>'💆🏽‍♀️','Otros'=>'✨'];
$colores = [
  'Manicure' => ['bg'=>'#fce4ef','border'=>'#f4c0d1','text'=>'#c93060'],
  'Pedicure' => ['bg'=>'#e3f2fd','border'=>'#bbdefb','text'=>'#1565c0'],
  'Capilar'  => ['bg'=>'#f3e5f5','border'=>'#ce93d8','text'=>'#6a1b9a'],
  'Otros'    => ['bg'=>'#e8f5e9','border'=>'#a5d6a7','text'=>'#2e7d32'],
];

$servicios = $servicios ?? [];
$grupos    = [];
foreach ($servicios as $s) {
    $grupos[$s['categoria'] ?? 'Otros'][] = $s;
}
?>

<main>

  <div class="page-title">
    <span>💅</span> Servicios
  </div>

  <?php if (empty($grupos)): ?>
    <div class="card">
      <div class="empty-state">
        <div class="empty-icon">💅</div>
        <p>No hay servicios registrados.</p>
      </div>
    </div>
  <?php else: ?>

    <?php foreach ($grupos as $cat => $items):
      $col = $colores[$cat] ?? $colores['Otros'];
      $ico = $iconos[$cat] ?? '✨';
    ?>
    <div class="card">
      <h2 style="color:<?= $col['text'] ?>">
        <?= $ico . ' ' . htmlspecialchars($cat) ?>
        <span style="font-size:12px;font-weight:400;color:var(--text-muted);margin-left:4px">
          (<?= count($items) ?> servicio<?= count($items) !== 1 ? 's' : '' ?>)
        </span>
      </h2>

      <div class="tabla-wrap">
        <table>
          <thead>
            <tr>
              <th>Nombre</th>
              <th>Descripción</th>
              <th>Precio (COP)</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($items as $s):
              $sid = (int)($s['id_servicio'] ?? 0);
            ?>
            <tr>
              <td>
                <div style="display:flex;align-items:center;gap:8px">
                  <div style="
                    width:30px;height:30px;border-radius:8px;
                    background:<?= $col['bg'] ?>;
                    display:flex;align-items:center;justify-content:center;
                    font-size:14px;flex-shrink:0;
                  "><?= $ico ?></div>
                  <span style="font-weight:500">
                    <?= htmlspecialchars($s['nombre_servicio'] ?? 'Sin nombre') ?>
                  </span>
                </div>
              </td>

              <td>
                <form id="form-<?= $sid ?>"
                      action="/Mi-proyecto-formativo/public/index.php?action=actualizarServicio"
                      method="POST">
                  <input type="hidden" name="id_servicio" value="<?= $sid ?>">
                  <input type="text" name="descripcion"
                         value="<?= htmlspecialchars($s['descripcion'] ?? '') ?>"
                         placeholder="Descripción breve..."
                         style="
                           width:100%;padding:7px 12px;
                           border:1.5px solid var(--pink-border);
                           border-radius:8px;font-family:'Poppins',sans-serif;
                           font-size:12px;color:var(--text);
                           background:var(--pink-bg);outline:none;
                         "
                         onfocus="this.style.borderColor='var(--pink)';this.style.background='white'"
                         onblur="this.style.borderColor='var(--pink-border)';this.style.background='var(--pink-bg)'">
                </form>
              </td>

              <td>
                <input type="number" form="form-<?= $sid ?>"
                       name="precio"
                       value="<?= htmlspecialchars($s['precio'] ?? 0) ?>"
                       min="0" step="500" required
                       style="
                         width:120px;padding:7px 12px;
                         border:1.5px solid var(--pink-border);
                         border-radius:8px;font-family:'Poppins',sans-serif;
                         font-size:13px;font-weight:700;
                         color:<?= $col['text'] ?>;
                         background:<?= $col['bg'] ?>;outline:none;
                       "
                       onfocus="this.style.borderColor='<?= $col['text'] ?>'"
                       onblur="this.style.borderColor='var(--pink-border)'">
              </td>

              <td>
                <div style="display:flex;gap:6px">
                  <button form="form-<?= $sid ?>" type="submit"
                          class="btn btn-primary btn-sm">
                    ✓ Guardar
                  </button>
                  <form action="/Mi-proyecto-formativo/public/index.php?action=eliminarServicio"
                        method="POST"
                        onsubmit="return confirm('¿Eliminar este servicio?')">
                    <input type="hidden" name="id_servicio" value="<?= $sid ?>">
                    <button type="submit" class="btn btn-danger btn-sm">🗑</button>
                  </form>
                </div>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
    <?php endforeach; ?>

  <?php endif; ?>

</main>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
