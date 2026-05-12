<?php
$titulo = 'Agendar Cita';
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';

// Agrupar servicios por categoría
$grupos = [];
foreach ($servicios as $s) {
    $cat = $s['categoria'] ?? 'Otros';
    $grupos[$cat][] = $s;
}

// Iconos por categoría
$iconos = [
    'Manicure' => '💅',
    'Pedicure' => '👣',
    'Capilar'  => '💆🏽‍♀️',
    'Otros'    => '✨',
];
?>

<main>

  <h1 style="font-size:22px;font-weight:600;color:#c0375a;margin-bottom:1.5rem">📅 Agendar nueva cita</h1>

  <div class="card" style="max-width:540px">

    <form action="/Mi-proyecto-formativo/public/index.php?action=agendarCita" method="POST">

      <!-- SERVICIO agrupado por categoría -->
      <div class="form-group">
        <label>Servicio</label>

        <?php if (empty($grupos)): ?>
          <p style="color:#b07090;font-size:13px">No hay servicios disponibles aún.</p>
        <?php else: ?>

          <!-- Tabs de categoría -->
          <div style="display:flex;gap:8px;margin-bottom:12px;flex-wrap:wrap" id="tabs">
            <?php foreach (array_keys($grupos) as $i => $cat): ?>
              <button type="button"
                onclick="mostrarCategoria('<?= $cat ?>')"
                id="tab-<?= $cat ?>"
                style="padding:8px 18px;border-radius:50px;border:2px solid #e8527a;
                       font-family:'Poppins',sans-serif;font-size:13px;font-weight:500;
                       cursor:pointer;transition:all .2s;
                       background:<?= $i === 0 ? 'linear-gradient(135deg,#e8527a,#c93060)' : 'white' ?>;
                       color:<?= $i === 0 ? 'white' : '#c93060' ?>">
                <?= ($iconos[$cat] ?? '✨') . ' ' . $cat ?>
              </button>
            <?php endforeach; ?>
          </div>

          <!-- Opciones por categoría -->
          <?php foreach ($grupos as $cat => $items): ?>
          <div id="cat-<?= $cat ?>" class="cat-panel"
               style="display:<?= array_key_first($grupos) === $cat ? 'grid' : 'none' ?>;
                      grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:10px">
            <?php foreach ($items as $s): ?>
            <label style="cursor:pointer">
              <input type="radio" name="id_servicio" value="<?= $s['id_servicio'] ?>"
                     style="display:none" class="radio-servicio"
                     onchange="seleccionarServicio(this)">
              <div class="servicio-card" id="card-<?= $s['id_servicio'] ?>"
                   style="border:2px solid #f4c0d1;border-radius:14px;padding:14px;
                          background:#fdf0f5;transition:all .2s">
                <div style="font-weight:600;color:#c0375a;font-size:13px;margin-bottom:4px">
                  <?= htmlspecialchars($s['nombre_servicio']) ?>
                </div>
                <div style="font-size:11px;color:#8a5068;margin-bottom:8px;line-height:1.4">
                  <?= htmlspecialchars($s['descripcion'] ?? '') ?>
                </div>
                <div style="font-weight:700;color:#4a2030;font-size:14px">
                  $<?= number_format($s['precio'], 0, ',', '.') ?>
                </div>
              </div>
            </label>
            <?php endforeach; ?>
          </div>
          <?php endforeach; ?>

          <!-- Campo oculto de validación -->
          <p id="msg-servicio" style="color:#a32d2d;font-size:12px;display:none;margin-top:6px">
            Selecciona un servicio para continuar.
          </p>

        <?php endif; ?>
      </div>

      <!-- FECHA Y HORA -->
      <div class="form-row" style="margin-top:8px">
        <div class="form-group">
          <label>Fecha</label>
          <input type="date" name="fecha" required min="<?= date('Y-m-d') ?>">
        </div>
        <div class="form-group">
          <label>Hora</label>
          <select name="hora" required>
            <option value="">— Hora —</option>
            <?php
            $horas = ['08:00','08:30','09:00','09:30','10:00','10:30',
                      '11:00','11:30','12:00','12:30','13:00','13:30',
                      '14:00','14:30','15:00','15:30','16:00','16:30',
                      '17:00','17:30','18:00','18:30','19:00'];
            foreach ($horas as $h): ?>
              <option value="<?= $h ?>"><?= $h ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div style="display:flex;gap:12px;margin-top:12px">
        <button type="submit" class="btn btn-primary" onclick="return validarFormulario()">
          Confirmar reserva
        </button>
        <a href="/Mi-proyecto-formativo/public/index.php?action=dashboard" class="btn btn-outline">
          Cancelar
        </a>
      </div>

    </form>
  </div>

</main>

<script>
// Mostrar panel de categoría al hacer clic en tab
function mostrarCategoria(cat) {
    document.querySelectorAll('.cat-panel').forEach(p => p.style.display = 'none');
    document.getElementById('cat-' + cat).style.display = 'grid';

    document.querySelectorAll('#tabs button').forEach(b => {
        b.style.background = 'white';
        b.style.color = '#c93060';
    });
    const tab = document.getElementById('tab-' + cat);
    if (tab) {
        tab.style.background = 'linear-gradient(135deg,#e8527a,#c93060)';
        tab.style.color = 'white';
    }
}

// Resaltar la tarjeta seleccionada
function seleccionarServicio(radio) {
    document.querySelectorAll('.servicio-card').forEach(c => {
        c.style.border = '2px solid #f4c0d1';
        c.style.background = '#fdf0f5';
    });
    const card = document.getElementById('card-' + radio.value);
    if (card) {
        card.style.border = '2px solid #e8527a';
        card.style.background = '#fce4ef';
    }
    document.getElementById('msg-servicio').style.display = 'none';
}

// Validar que se haya seleccionado un servicio
function validarFormulario() {
    const seleccionado = document.querySelector('input[name="id_servicio"]:checked');
    if (!seleccionado) {
        document.getElementById('msg-servicio').style.display = 'block';
        return false;
    }
    return true;
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
