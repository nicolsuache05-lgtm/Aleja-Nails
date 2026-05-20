<?php
$titulo = 'Realizar Pago';
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';
?>

<main>
  <h1 style="font-size:22px;font-weight:600;color:#c0375a;margin-bottom:1.5rem">
    💳 Realizar Pago
  </h1>

  <?php if (!empty($_SESSION['flash_error'])): ?>
    <div style="background:#fde8e8;border:1px solid #f5c6cb;color:#a32d2d;
                padding:12px 16px;border-radius:10px;margin-bottom:1rem">
      <?= htmlspecialchars($_SESSION['flash_error']) ?>
    </div>
    <?php unset($_SESSION['flash_error']); ?>
  <?php endif; ?>

  <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;max-width:860px">

    <!-- ══ RESUMEN DE LA RESERVA ══════════════════════════════ -->
    <div class="card" style="height:fit-content">
      <h2 style="font-size:16px;font-weight:600;color:#c0375a;margin-bottom:16px">
        📋 Resumen de tu reserva
      </h2>

      <!-- Info fecha/hora -->
      <div style="display:flex;gap:16px;margin-bottom:16px">
        <div style="background:#fdf0f5;border-radius:10px;padding:12px 16px;flex:1;text-align:center">
          <div style="font-size:11px;color:#8a5068;margin-bottom:4px">FECHA</div>
          <div style="font-weight:700;color:#4a2030;font-size:15px">
            <?= htmlspecialchars(date('d/m/Y', strtotime($reserva['fecha']))) ?>
          </div>
        </div>
        <div style="background:#fdf0f5;border-radius:10px;padding:12px 16px;flex:1;text-align:center">
          <div style="font-size:11px;color:#8a5068;margin-bottom:4px">HORA</div>
          <div style="font-weight:700;color:#4a2030;font-size:15px">
            <?= htmlspecialchars($reserva['hora']) ?>
          </div>
        </div>
      </div>

      <!-- Lista de servicios -->
      <div style="font-size:13px;font-weight:600;color:#c0375a;margin-bottom:8px">
        Servicios:
      </div>

      <?php if (!empty($detalles)): ?>
        <ul style="margin:0 0 16px;padding:0;list-style:none">
          <?php foreach ($detalles as $d): ?>
            <li style="display:flex;justify-content:space-between;align-items:center;
                       padding:8px 0;border-bottom:1px solid #f4c0d1;font-size:13px">
              <span style="color:#4a2030">
                💅 <?= htmlspecialchars($d['nombre_servicio']) ?>
              </span>
              <span style="font-weight:600;color:#c0375a">
                $<?= number_format((float)$d['precio'], 0, ',', '.') ?>
              </span>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>

      <!-- Total -->
      <div style="background:linear-gradient(135deg,#e8527a,#c93060);
                  border-radius:12px;padding:14px 18px;
                  display:flex;justify-content:space-between;align-items:center">
        <span style="color:white;font-weight:600;font-size:15px">
          Total a pagar
        </span>
        <span style="color:white;font-weight:700;font-size:22px">
          $<?= number_format($total, 0, ',', '.') ?>
        </span>
      </div>
    </div>

    <!-- ══ FORMULARIO DE PAGO ══════════════════════════════════ -->
    <div class="card" style="height:fit-content">
      <h2 style="font-size:16px;font-weight:600;color:#c0375a;margin-bottom:16px">
        💰 Método de pago
      </h2>

      <form action="/Mi-proyecto-formativo/public/index.php?action=pagar&id=<?= (int)$reserva['id_reserva'] ?>"
            method="POST">

        <!-- Opciones de pago -->
        <div style="display:flex;flex-direction:column;gap:12px;margin-bottom:24px">

          <!-- Efectivo -->
          <label id="lbl-efectivo"
                 onclick="seleccionarMetodo('efectivo')"
                 style="display:flex;align-items:center;gap:14px;
                        border:2px solid #f4c0d1;border-radius:12px;
                        padding:14px 16px;cursor:pointer;transition:all .2s;
                        background:#fdf0f5">
            <input type="radio" name="metodo_pago" value="efectivo"
                   id="radio-efectivo" style="display:none">
            <div style="width:44px;height:44px;border-radius:50%;
                        background:linear-gradient(135deg,#4caf50,#388e3c);
                        display:flex;align-items:center;justify-content:center;
                        font-size:20px;flex-shrink:0">
              💵
            </div>
            <div>
              <div style="font-weight:600;color:#4a2030;font-size:14px">Efectivo</div>
              <div style="font-size:12px;color:#8a5068">Paga en el salón el día de tu cita</div>
            </div>
          </label>

          <!-- Transferencia -->
          <div id="lbl-transferencia"
               onclick="seleccionarMetodo('transferencia')"
               style="border:2px solid #f4c0d1;border-radius:12px;
                      cursor:pointer;transition:all .2s;background:#fdf0f5;
                      overflow:hidden">

            <input type="radio" name="metodo_pago" value="transferencia"
                   id="radio-transferencia" style="display:none">

            <!-- Fila principal -->
            <div style="display:flex;align-items:center;gap:14px;padding:14px 16px">
              <div style="width:44px;height:44px;border-radius:50%;
                          background:linear-gradient(135deg,#2196f3,#1565c0);
                          display:flex;align-items:center;justify-content:center;
                          font-size:20px;flex-shrink:0">
                🏦
              </div>
              <div>
                <div style="font-weight:600;color:#4a2030;font-size:14px">Transferencia bancaria</div>
                <div style="font-size:12px;color:#8a5068">Nequi / Daviplata / PSE</div>
              </div>
              <div style="margin-left:auto;font-size:12px;color:#8a5068">▼</div>
            </div>

            <!-- Datos bancarios — ocultos hasta seleccionar -->
            <div id="datos-transferencia"
                 style="display:none;background:#e8f4ff;border-top:2px solid #bbdefb;
                        padding:16px 18px">

              <div style="font-weight:700;color:#1565c0;font-size:13px;margin-bottom:12px">
                📋 Datos para realizar la transferencia:
              </div>

              <!-- Nequi -->
              <div style="background:white;border-radius:10px;padding:12px 14px;
                          margin-bottom:10px;border:1px solid #bbdefb">
                <div style="display:flex;align-items:center;gap:10px">
                  <div style="width:36px;height:36px;border-radius:50%;
                              background:linear-gradient(135deg,#7b1fa2,#4a148c);
                              display:flex;align-items:center;justify-content:center;
                              font-size:16px;flex-shrink:0">💜</div>
                  <div>
                    <div style="font-size:11px;color:#8a5068;font-weight:600;text-transform:uppercase;letter-spacing:.5px">Nequi</div>
                    <div style="font-weight:700;color:#1a1a2e;font-size:16px;letter-spacing:1px">
                      304 408 5465
                    </div>
                  </div>
                  <button type="button" onclick="copiar('304 408 5465', this)"
                          style="margin-left:auto;background:#f0e6ff;border:1px solid #ce93d8;
                                 color:#7b1fa2;border-radius:8px;padding:5px 10px;
                                 font-size:11px;cursor:pointer;font-family:'Poppins',sans-serif">
                    📋 Copiar
                  </button>
                </div>
              </div>

              <!-- Daviplata -->
              <div style="background:white;border-radius:10px;padding:12px 14px;
                          margin-bottom:10px;border:1px solid #bbdefb">
                <div style="display:flex;align-items:center;gap:10px">
                  <div style="width:36px;height:36px;border-radius:50%;
                              background:linear-gradient(135deg,#e53935,#b71c1c);
                              display:flex;align-items:center;justify-content:center;
                              font-size:16px;flex-shrink:0">❤️</div>
                  <div>
                    <div style="font-size:11px;color:#8a5068;font-weight:600;text-transform:uppercase;letter-spacing:.5px">Daviplata</div>
                    <div style="font-weight:700;color:#1a1a2e;font-size:16px;letter-spacing:1px">
                      304 408 5465
                    </div>
                  </div>
                  <button type="button" onclick="copiar('304 408 5465', this)"
                          style="margin-left:auto;background:#ffeaea;border:1px solid #ef9a9a;
                                 color:#b71c1c;border-radius:8px;padding:5px 10px;
                                 font-size:11px;cursor:pointer;font-family:'Poppins',sans-serif">
                    📋 Copiar
                  </button>
                </div>
              </div>

              <!-- Titular -->
              <div style="font-size:12px;color:#1565c0;margin-bottom:10px">
                👤 Titular: <strong>Alejandra Vanegas</strong>
              </div>

              <!-- Aviso comprobante -->
              <div style="background:#fff8e1;border-left:3px solid #ffc107;
                          border-radius:0 8px 8px 0;padding:10px 12px;font-size:12px;color:#7a5800">
                ⚠️ Después de transferir, envía el comprobante por WhatsApp al
                <a href="https://wa.me/573001234567?text=Hola,%20adjunto%20mi%20comprobante%20de%20pago"
                   target="_blank"
                   style="color:#e8527a;font-weight:700;text-decoration:none">
                  📲 304 408 5465
                </a>
              </div>

            </div>
          </div>



        </div>

        <p id="msg-pago" style="color:#a32d2d;font-size:12px;display:none;margin-bottom:10px">
          ⚠ Selecciona un método de pago.
        </p>

        <!-- Total visible antes de confirmar -->
        <div style="background:#fdf0f5;border-radius:10px;padding:12px 16px;
                    display:flex;justify-content:space-between;margin-bottom:16px">
          <span style="font-size:13px;color:#8a5068">Total:</span>
          <span style="font-weight:700;color:#c0375a;font-size:16px">
            $<?= number_format($total, 0, ',', '.') ?>
          </span>
        </div>

        <div style="display:flex;gap:10px">
          <button type="submit" class="btn btn-primary"
                  style="flex:1" onclick="return validarPago()">
            ✅ Confirmar pago
          </button>
          <a href="/Mi-proyecto-formativo/public/index.php?action=misReservas"
             class="btn btn-outline">
            Después
          </a>
        </div>

      </form>
    </div>

  </div>
</main>

<script>
const metodos = ['efectivo', 'transferencia'];
let metodoActivo = null;

function seleccionarMetodo(m) {
  metodoActivo = m;
  metodos.forEach(function(x) {
    const lbl   = document.getElementById('lbl-' + x);
    const radio = document.getElementById('radio-' + x);
    if (x === m) {
      lbl.style.border     = '2px solid #e8527a';
      lbl.style.background = '#fce4ef';
      if (radio) radio.checked = true;
    } else {
      lbl.style.border     = '2px solid #f4c0d1';
      lbl.style.background = '#fdf0f5';
      if (radio) radio.checked = false;
    }
  });

  // Mostrar datos bancarios solo al elegir transferencia
  const datos = document.getElementById('datos-transferencia');
  if (datos) {
    datos.style.display = (m === 'transferencia') ? 'block' : 'none';
  }

  const msg = document.getElementById('msg-pago');
  if (msg) msg.style.display = 'none';
}

function copiar(texto, btn) {
  navigator.clipboard.writeText(texto).then(function() {
    const orig = btn.innerHTML;
    btn.innerHTML = '✅ Copiado';
    btn.style.background   = '#e8f5e9';
    btn.style.color        = '#2e7d32';
    btn.style.borderColor  = '#a5d6a7';
    setTimeout(function() {
      btn.innerHTML          = orig;
      btn.style.background   = '';
      btn.style.color        = '';
      btn.style.borderColor  = '';
    }, 2000);
  });
}

function validarPago() {
  if (!metodoActivo) {
    const msg = document.getElementById('msg-pago');
    if (msg) msg.style.display = 'block';
    return false;
  }
  return true;
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
