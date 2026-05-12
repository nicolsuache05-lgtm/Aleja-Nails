<?php
// views/layouts/sidebar.php
$rol    = $_SESSION['rol'] ?? $_SESSION['usuario_rol'] ?? '';
$action = $_GET['action'] ?? '';
$links  = $rol === 'admin'
    ? [
        ['action' => 'adminPanel',      'icon' => '📊', 'label' => 'Dashboard'],
        ['action' => 'listarReservas',  'icon' => '📅', 'label' => 'Reservas'],
        ['action' => 'listarClientes',  'icon' => '👥', 'label' => 'Clientes'],
        ['action' => 'listarServicios', 'icon' => '💅', 'label' => 'Servicios'],
        ['action' => 'verPagos',        'icon' => '💰', 'label' => 'Pagos'],
        ['action' => 'logout',          'icon' => '🚪', 'label' => 'Salir'],
      ]
    : [
        ['action' => 'dashboard',       'icon' => '🏠', 'label' => 'Inicio'],
        ['action' => 'agendarCita',     'icon' => '📒', 'label' => 'Agendar cita'],
        ['action' => 'misReservas',     'icon' => '📅', 'label' => 'Mis reservas'],
        ['action' => 'logout',          'icon' => '🚪', 'label' => 'Salir'],
      ];
?>
<aside style="width:220px;background:white;border-right:1px solid #de7ea9ff;padding:1.5rem 1rem;flex-shrink:0;">
  <nav style="display:flex;flex-direction:column;gap:6px;">
    <?php foreach ($links as $link): ?>
      <a href="index.php?action=<?= $link['action'] ?>"
         style="display:flex;align-items:center;gap:10px;padding:10px 14px;border-radius:12px;
                text-decoration:none;font-size:13px;font-weight:500;
                color:<?= $action === $link['action'] ? '#c0375a' : '#8a5068' ?>;
                background:<?= $action === $link['action'] ? '#eb6ba5ff' : 'transparent' ?>;
                transition:background .2s;"
         onmouseover="this.style.background='#ed99b9ff'"
         onmouseout="this.style.background='<?= $action === $link['action'] ? '#f092bcff' : 'transparent' ?>'">
        <span style="font-size:16px;"><?= $link['icon'] ?></span>
        <?= $link['label'] ?>
      </a>
    <?php endforeach; ?>
  </nav>
</aside>
