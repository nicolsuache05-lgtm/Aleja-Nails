<?php
$rol    = $_SESSION['rol'] ?? $_SESSION['usuario_rol'] ?? '';
$action = $_GET['action'] ?? '';

$links = $rol === 'admin'
  ? [
      ['action' => 'adminPanel',      'icon' => '📊', 'label' => 'Dashboard',  'color' => '#f3e5f5'],
      ['action' => 'listarReservas',  'icon' => '📅', 'label' => 'Reservas',   'color' => '#fce4ef'],
      ['action' => 'listarClientes',  'icon' => '👥', 'label' => 'Clientes',   'color' => '#e3f2fd'],
      ['action' => 'listarServicios', 'icon' => '💅', 'label' => 'Servicios',  'color' => '#fce4ef'],
      ['action' => 'verPagos',        'icon' => '💰', 'label' => 'Pagos',      'color' => '#e8f5e9'],
    ]
  : [
      ['action' => 'dashboard',       'icon' => '🏠', 'label' => 'Inicio',      'color' => '#fce4ef'],
      ['action' => 'agendarCita',     'icon' => '📅', 'label' => 'Agendar cita','color' => '#fce4ef'],
      ['action' => 'misReservas',     'icon' => '📋', 'label' => 'Mis reservas','color' => '#fce4ef'],
    ];
?>

<aside style="
  width: 230px;
  flex-shrink: 0;
  background: white;
  border-right: 1px solid #fde8f0;
  padding: 1.5rem 1rem;
  display: flex;
  flex-direction: column;
  gap: 4px;
">

  <!-- Perfil mini -->
  <div style="
    background: linear-gradient(135deg, #fce4ef, #fdf5f8);
    border-radius: 14px;
    padding: 14px;
    margin-bottom: 16px;
    text-align: center;
    border: 1px solid #fde8f0;
  ">
    <div style="
      width: 52px; height: 52px;
      border-radius: 50%;
      background: linear-gradient(135deg, #e8527a, #c93060);
      display: flex; align-items: center; justify-content: center;
      font-size: 24px;
      margin: 0 auto 8px;
      box-shadow: 0 4px 14px rgba(200,48,96,.3);
    ">
      <?= $rol === 'admin' ? '👑' : '💅' ?>
    </div>
    <div style="font-weight:600;font-size:13px;color:#3d1a28;margin-bottom:2px">
      <?= htmlspecialchars($_SESSION['nombre'] ?? 'Usuario') ?>
    </div>
    <div style="
      display: inline-block;
      font-size: 10px;
      font-weight: 600;
      letter-spacing: .5px;
      text-transform: uppercase;
      padding: 2px 10px;
      border-radius: 20px;
      <?= $rol === 'admin'
        ? 'background:#fce4ef;color:#c93060;'
        : 'background:#e8f5e9;color:#2e7d32;' ?>
    ">
      <?= $rol === 'admin' ? 'Administrador' : 'Cliente' ?>
    </div>
  </div>

  <!-- Links de navegación -->
  <nav style="display:flex;flex-direction:column;gap:3px;flex:1">
    <?php foreach ($links as $link):
      $activo = $action === $link['action'];
    ?>
      <a href="index.php?action=<?= $link['action'] ?>"
         style="
           display: flex;
           align-items: center;
           gap: 10px;
           padding: 10px 13px;
           border-radius: 12px;
           text-decoration: none;
           font-size: 13px;
           font-weight: <?= $activo ? '600' : '500' ?>;
           color: <?= $activo ? '#c93060' : '#7a4a5e' ?>;
           background: <?= $activo ? 'linear-gradient(135deg,#fce4ef,#fdf0f5)' : 'transparent' ?>;
           border: <?= $activo ? '1px solid #f4c0d1' : '1px solid transparent' ?>;
           transition: all .18s;
         "
         onmouseover="if(!<?= $activo ? 'true' : 'false' ?>){ this.style.background='#fdf5f8'; this.style.color='#c93060'; }"
         onmouseout="if(!<?= $activo ? 'true' : 'false' ?>){ this.style.background='transparent'; this.style.color='#7a4a5e'; }">

        <span style="
          width: 32px; height: 32px;
          border-radius: 8px;
          background: <?= $activo ? '#fce4ef' : ($link['color'] ?? '#fdf5f8') ?>;
          display: flex; align-items: center; justify-content: center;
          font-size: 15px;
          flex-shrink: 0;
        "><?= $link['icon'] ?></span>

        <?= htmlspecialchars($link['label']) ?>

        <?php if ($activo): ?>
          <span style="margin-left:auto;width:6px;height:6px;border-radius:50%;background:#e8527a;"></span>
        <?php endif; ?>
      </a>
    <?php endforeach; ?>
  </nav>

  <!-- Botón salir -->
  <a href="index.php?action=logout"
     style="
       display: flex;
       align-items: center;
       gap: 10px;
       padding: 10px 13px;
       border-radius: 12px;
       text-decoration: none;
       font-size: 13px;
       font-weight: 500;
       color: #b07090;
       background: transparent;
       border: 1px solid transparent;
       margin-top: 8px;
       transition: all .18s;
     "
     onmouseover="this.style.background='#fff0f0';this.style.color='#c0392b';this.style.borderColor='#f5c6cb';"
     onmouseout="this.style.background='transparent';this.style.color='#b07090';this.style.borderColor='transparent';">
    <span style="width:32px;height:32px;border-radius:8px;background:#fff0f0;display:flex;align-items:center;justify-content:center;font-size:15px;flex-shrink:0;">🚪</span>
    Cerrar sesión
  </a>

</aside>
