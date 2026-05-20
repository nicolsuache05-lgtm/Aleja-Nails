<?php
$titulo = 'Agendar Cita';
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';

$servicios = $servicios ?? [];
$grupos    = [];
foreach ($servicios as $s) {
    $grupos[$s['categoria'] ?? 'Otros'][] = $s;
}
$iconos = ['Manicure'=>'💅','Pedicure'=>'👣','Capilar'=>'💆🏽‍♀️','Otros'=>'✨'];
?>

<main>
  <h1 style="font-size:22px;font-weight:600;color:#c0375a;margin-bottom:1.5rem">
    📅 Agendar nueva cita
  </h1>

  <?php if (!empty($_SESSION['flash_error'])): ?>
    <div style="background:#fde8e8;border:1px solid #f5c6cb;color:#a32d2d;
                padding:12px 16px;border-radius:10px;margin-bottom:1rem">
      <?= htmlspecialchars($_SESSION['flash_error']) ?>
    </div>
    <?php unset($_SESSION['flash_error']); ?>
  <?php endif; ?>

  <div class="card">
    <form action="/Mi-proyecto-formativo/public/index.php?action=agendarCita"
          method="POST" id="form-reserva">

      <!-- ══ SERVICIOS ══════════════════════════════════════ -->
      <div class="form-group">
        <label style="font-weight:600;color:#c0375a;font-size:14px">
          SERVICIO
          <span style="font-weight:400;color:#8a5068;font-size:12px">
            — selecciona uno o varios
          </span>
        </label>

        <?php if (empty($grupos)): ?>
          <p style="color:#b07090;font-size:13px">No hay servicios disponibles.</p>
        <?php else: ?>

          <!-- Tabs categoría -->
          <div style="display:flex;gap:8px;margin-bottom:14px;flex-wrap:wrap" id="tabs">
            <?php foreach (array_keys($grupos) as $i => $cat): ?>
              <button type="button"
                onclick="mostrarCategoria('<?= htmlspecialchars($cat, ENT_QUOTES) ?>')"
                id="tab-<?= htmlspecialchars($cat, ENT_QUOTES) ?>"
                style="padding:8px 18px;border-radius:50px;border:2px solid #e8527a;
                       font-family:'Poppins',sans-serif;font-size:13px;font-weight:500;
                       cursor:pointer;transition:all .2s;
                       background:<?= $i===0 ? 'linear-gradient(135deg,#e8527a,#c93060)' : 'white' ?>;
                       color:<?= $i===0 ? 'white' : '#c93060' ?>">
                <?= htmlspecialchars(($iconos[$cat]??'✨').' '.$cat) ?>
              </button>
            <?php endforeach; ?>
          </div>

          <!-- Paneles de tarjetas -->
          <?php foreach ($grupos as $cat => $items): ?>
            <div id="cat-<?= htmlspecialchars($cat, ENT_QUOTES) ?>" class="cat-panel"
                 style="display:<?= array_key_first($grupos)===$cat ? 'grid' : 'none' ?>;
                        grid-template-columns:repeat(auto-fill,minmax(200px,1fr));
                        gap:12px;margin-bottom:6px">

              <?php foreach ($items as $s):
                $sid   = (int)($s['id_servicio'] ?? 0);
                $precio = (float)($s['precio'] ?? 0);
                $imgSrc = match($cat) {
                    'Manicure' => 'manicure_service.png',
                    'Pedicure' => 'pedicure_service.png',
                    'Capilar'  => 'capilar_service.png',
                    default    => 'otros_service.png',
                };
              ?>
                <!-- Tarjeta clickeable -->
                <div class="srv-card" id="card-<?= $sid ?>"
                     data-id="<?= $sid ?>"
                     data-precio="<?= $precio ?>"
                     data-nombre="<?= htmlspecialchars($s['nombre_servicio']??'', ENT_QUOTES) ?>"
                     onclick="toggleServicio(<?= $sid ?>)"
                     style="border:2px solid #f4c0d1;border-radius:14px;padding:14px;
                            background:#fdf0f5;cursor:pointer;transition:all .2s;
                            display:flex;flex-direction:column;position:relative;
                            box-sizing:border-box">

                  <!-- Checkbox oculto -->
                  <input type="checkbox" name="servicios[]" value="<?= $sid ?>"
                         id="chk-<?= $sid ?>" class="chk-srv" style="display:none">

                  <!-- Marca de selección -->
                  <div id="mark-<?= $sid ?>"
                       style="display:none;position:absolute;top:10px;right:10px;
                              width:22px;height:22px;border-radius:50%;
                              background:#e8527a;color:white;font-size:13px;
                              align-items:center;justify-content:center;font-weight:700">
                    ✓
                  </div>

                  <img src="/Mi-proyecto-formativo/img/<?= $imgSrc ?>"
                       alt="<?= htmlspecialchars($cat) ?>"
                       style="width:100%;height:130px;object-fit:cover;
                              border-radius:8px;margin-bottom:10px">

                  <div style="font-weight:600;color:#c0375a;font-size:13px;margin-bottom:3px">
                    <?= htmlspecialchars($s['nombre_servicio']??'') ?>
                  </div>
                  <div style="font-size:11px;color:#8a5068;margin-bottom:8px;line-height:1.4">
                    <?= htmlspecialchars($s['descripcion']??'') ?>
                  </div>
                  <div style="font-weight:700;color:#4a2030;font-size:14px;margin-top:auto">
                    $<?= number_format($precio,0,',','.') ?>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endforeach; ?>

          <p id="msg-srv" style="color:#a32d2d;font-size:12px;display:none;margin-top:4px">
            ⚠ Selecciona al menos un servicio.
          </p>
        <?php endif; ?>
      </div>

      <!-- ══ RESUMEN TOTAL ═══════════════════════════════════ -->
      <div id="resumen"
           style="display:none;background:linear-gradient(135deg,#fce4ef,#fdf0f5);
                  border:2px solid #e8527a;border-radius:14px;
                  padding:16px 20px;margin:16px 0">
        <div style="font-size:13px;font-weight:600;color:#c0375a;margin-bottom:10px">
          🛒 Resumen de servicios seleccionados
        </div>
        <ul id="lista-srv"
            style="margin:0 0 10px;padding:0;list-style:none;font-size:13px;color:#4a2030">
        </ul>
        <div style="border-top:1px solid #f4c0d1;padding-top:10px;
                    display:flex;justify-content:space-between;align-items:center">
          <span style="font-weight:600;color:#c0375a;font-size:14px">Total a pagar:</span>
          <span id="total-txt"
                style="font-weight:700;color:#4a2030;font-size:20px">$0</span>
        </div>
      </div>

      <!-- ══ FECHA Y HORA ════════════════════════════════════ -->
      <div class="form-row" style="margin-top:4px">
        <div class="form-group">
          <label>Fecha</label>
          <input type="date" name="fecha" required min="<?= date('Y-m-d') ?>">
        </div>
        <div class="form-group">
          <label>Hora</label>
          <select name="hora" required>
            <option value="">— Hora —</option>
            <?php
            $horas=['08:00','08:30','09:00','09:30','10:00','10:30','11:00','11:30',
                    '12:00','12:30','13:00','13:30','14:00','14:30','15:00','15:30',
                    '16:00','16:30','17:00','17:30','18:00','18:30','19:00'];
            foreach ($horas as $h): ?>
              <option value="<?= $h ?>"><?= $h ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <!-- ══ BOTONES ═════════════════════════════════════════ -->
      <div style="display:flex;gap:12px;margin-top:16px">
        <button type="submit" class="btn btn-primary"
                onclick="return validar()">
          Confirmar reserva
        </button>
        <a href="/Mi-proyecto-formativo/public/index.php?action=dashboard"
           class="btn btn-outline">Cancelar</a>
      </div>

    </form>
  </div>
</main>

<script>
// Datos de servicios desde PHP
const SRV = {
<?php foreach ($servicios as $s): ?>
  <?= (int)$s['id_servicio'] ?>: {
    nombre: <?= json_encode($s['nombre_servicio'] ?? '') ?>,
    precio: <?= (float)($s['precio'] ?? 0) ?>
  },
<?php endforeach; ?>
};

const seleccionados = new Set();

function toggleServicio(id) {
  const chk  = document.getElementById('chk-'  + id);
  const card = document.getElementById('card-' + id);
  const mark = document.getElementById('mark-' + id);
  if (!chk) return;

  chk.checked = !chk.checked;

  if (chk.checked) {
    seleccionados.add(id);
    card.style.border     = '2px solid #e8527a';
    card.style.background = '#fce4ef';
    mark.style.display    = 'flex';
  } else {
    seleccionados.delete(id);
    card.style.border     = '2px solid #f4c0d1';
    card.style.background = '#fdf0f5';
    mark.style.display    = 'none';
  }
  actualizarResumen();
}

function actualizarResumen() {
  const resumen = document.getElementById('resumen');
  const lista   = document.getElementById('lista-srv');
  const total   = document.getElementById('total-txt');
  const msg     = document.getElementById('msg-srv');

  if (seleccionados.size === 0) {
    resumen.style.display = 'none';
    return;
  }

  resumen.style.display = 'block';
  if (msg) msg.style.display = 'none';

  let suma = 0;
  let html = '';
  seleccionados.forEach(function(id) {
    const s = SRV[id];
    if (!s) return;
    suma += s.precio;
    html += '<li style="display:flex;justify-content:space-between;'
          + 'padding:5px 0;border-bottom:1px solid #f4c0d1">'
          + '<span>' + s.nombre + '</span>'
          + '<span style="font-weight:600">$' + fmt(s.precio) + '</span>'
          + '</li>';
  });

  lista.innerHTML = html;
  total.textContent = '$' + fmt(suma);
}

function fmt(n) {
  return Math.round(n).toLocaleString('es-CO');
}

function mostrarCategoria(cat) {
  document.querySelectorAll('.cat-panel').forEach(p => p.style.display = 'none');
  const panel = document.getElementById('cat-' + cat);
  if (panel) panel.style.display = 'grid';

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

function validar() {
  const msg = document.getElementById('msg-srv');
  if (seleccionados.size === 0) {
    if (msg) msg.style.display = 'block';
    return false;
  }
  return true;
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
