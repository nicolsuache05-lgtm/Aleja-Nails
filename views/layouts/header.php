<?php
$titulo = $titulo ?? 'Aleja-Nails';
$rol    = $_SESSION['rol']    ?? $_SESSION['usuario_rol']    ?? '';
$nombre = $_SESSION['nombre'] ?? $_SESSION['usuario_nombre'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($titulo) ?> — Aleja-Nails</title>
  <link rel="icon" type="image/png" href="/Mi-proyecto-formativo/img/favicon.png">
  <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    /* ══ RESET & BASE ══════════════════════════════════════════ */
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --pink:        #e8527a;
      --pink-dark:   #c93060;
      --pink-light:  #fce4ef;
      --pink-bg:     #fdf5f8;
      --pink-border: #f4c0d1;
      --text:        #3d1a28;
      --text-soft:   #7a4a5e;
      --text-muted:  #b07090;
      --white:       #ffffff;
      --radius-sm:   10px;
      --radius-md:   16px;
      --radius-lg:   22px;
      --shadow-sm:   0 1px 6px rgba(200,50,90,.08);
      --shadow-md:   0 4px 20px rgba(200,50,90,.12);
      --shadow-lg:   0 8px 40px rgba(200,50,90,.16);
    }

    body {
      font-family: 'Poppins', sans-serif;
      background: var(--pink-bg);
      color: var(--text);
      min-height: 100vh;
      font-size: 14px;
      line-height: 1.5;
    }

    /* ══ TOPBAR ════════════════════════════════════════════════ */
    .topbar {
      background: linear-gradient(135deg, #f06292 0%, #c2185b 100%);
      height: 62px;
      padding: 0 2rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
      box-shadow: 0 2px 16px rgba(180,30,80,.3);
      position: sticky;
      top: 0;
      z-index: 100;
    }

    .topbar-brand {
      font-family: 'Great Vibes', cursive;
      font-size: 30px;
      color: white;
      text-decoration: none;
      letter-spacing: .5px;
      text-shadow: 0 1px 4px rgba(0,0,0,.15);
    }

    .topbar-right {
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .topbar-user {
      display: flex;
      align-items: center;
      gap: 8px;
      background: rgba(255,255,255,.15);
      border-radius: 50px;
      padding: 6px 14px 6px 8px;
    }

    .topbar-avatar {
      width: 28px;
      height: 28px;
      border-radius: 50%;
      background: rgba(255,255,255,.3);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 14px;
    }

    .topbar-user-name {
      font-size: 13px;
      font-weight: 500;
      color: white;
    }

    .topbar-nav a {
      color: rgba(255,255,255,.9);
      text-decoration: none;
      font-size: 13px;
      font-weight: 500;
      padding: 7px 16px;
      border-radius: 50px;
      transition: background .18s;
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }
    .topbar-nav a:hover { background: rgba(255,255,255,.2); }
    .topbar-nav a.logout {
      background: rgba(255,255,255,.15);
      border: 1px solid rgba(255,255,255,.25);
    }
    .topbar-nav a.logout:hover { background: rgba(255,255,255,.25); }

    /* ══ FLASH MESSAGES ════════════════════════════════════════ */
    .flash {
      padding: 13px 2rem;
      font-size: 13px;
      font-weight: 500;
      text-align: center;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
    }
    .flash.ok    { background: #f0faf0; color: #276730; border-bottom: 1px solid #c3e6cb; }
    .flash.error { background: #fff3f3; color: #a32d2d; border-bottom: 1px solid #f5c6cb; }

    /* ══ LAYOUT ════════════════════════════════════════════════ */
    .layout { display: flex; min-height: calc(100vh - 62px); }
    main { flex: 1; padding: 2rem 2.25rem; overflow-x: hidden; }

    /* ══ CARDS ═════════════════════════════════════════════════ */
    .card {
      background: var(--white);
      border-radius: var(--radius-lg);
      padding: 1.75rem;
      box-shadow: var(--shadow-sm);
      margin-bottom: 1.5rem;
      border: 1px solid #fde8f0;
    }
    .card h2 {
      font-size: 16px;
      font-weight: 600;
      color: var(--pink-dark);
      margin-bottom: 1.25rem;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    /* ══ BOTONES ═══════════════════════════════════════════════ */
    .btn {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 9px 20px;
      border-radius: 50px;
      border: none;
      font-family: 'Poppins', sans-serif;
      font-size: 13px;
      font-weight: 500;
      cursor: pointer;
      text-decoration: none;
      transition: all .2s;
      white-space: nowrap;
    }
    .btn:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }
    .btn:active { transform: translateY(0); }

    .btn-primary {
      background: linear-gradient(135deg, var(--pink), var(--pink-dark));
      color: white;
      box-shadow: 0 3px 12px rgba(200,48,96,.3);
    }
    .btn-primary:hover { box-shadow: 0 6px 20px rgba(200,48,96,.4); }

    .btn-outline {
      background: transparent;
      border: 1.5px solid var(--pink);
      color: var(--pink-dark);
    }
    .btn-outline:hover { background: var(--pink-light); }

    .btn-danger { background: #fff0f0; color: #c0392b; border: 1.5px solid #f5c6cb; }
    .btn-danger:hover { background: #ffe0e0; }

    .btn-success { background: #f0faf0; color: #276730; border: 1.5px solid #c3e6cb; }

    .btn-sm { padding: 5px 14px; font-size: 12px; }

    /* ══ TABLAS ════════════════════════════════════════════════ */
    .tabla-wrap { overflow-x: auto; border-radius: var(--radius-md); }

    table { width: 100%; border-collapse: collapse; font-size: 13px; }

    thead tr {
      background: linear-gradient(135deg, #fce4ef, #fdf0f5);
    }
    th {
      color: var(--pink-dark);
      font-weight: 600;
      font-size: 11px;
      text-transform: uppercase;
      letter-spacing: .6px;
      padding: 12px 16px;
      text-align: left;
      white-space: nowrap;
    }
    td {
      padding: 12px 16px;
      border-bottom: 1px solid #fce4ef;
      color: var(--text);
      vertical-align: middle;
    }
    tr:last-child td { border-bottom: none; }
    tbody tr { transition: background .15s; }
    tbody tr:hover td { background: #fdf5f8; }

    /* ══ BADGES ════════════════════════════════════════════════ */
    .badge {
      display: inline-flex;
      align-items: center;
      gap: 4px;
      padding: 3px 10px;
      border-radius: 20px;
      font-size: 11px;
      font-weight: 600;
      white-space: nowrap;
    }
    .badge::before { content: ''; width: 5px; height: 5px; border-radius: 50%; display: inline-block; }
    .badge-pendiente  { background: #fff8e1; color: #795548; }
    .badge-pendiente::before  { background: #ffa726; }
    .badge-confirmada { background: #fce4ef; color: var(--pink-dark); }
    .badge-confirmada::before { background: var(--pink); }
    .badge-en_curso   { background: #e3f2fd; color: #1565c0; }
    .badge-en_curso::before   { background: #42a5f5; }
    .badge-completada { background: #e8f5e9; color: #2e7d32; }
    .badge-completada::before { background: #66bb6a; }
    .badge-cancelada  { background: #f5f5f5; color: #616161; }
    .badge-cancelada::before  { background: #9e9e9e; }
    .badge-activo     { background: #e8f5e9; color: #2e7d32; }
    .badge-activo::before     { background: #66bb6a; }
    .badge-inactivo   { background: #fff0f0; color: #c0392b; }
    .badge-inactivo::before   { background: #ef5350; }

    /* ══ FORMS ═════════════════════════════════════════════════ */
    .form-group { margin-bottom: 18px; }
    .form-group label {
      display: block;
      font-size: 11px;
      font-weight: 600;
      color: var(--text-soft);
      text-transform: uppercase;
      letter-spacing: .8px;
      margin-bottom: 7px;
    }
    .form-group input,
    .form-group select,
    .form-group textarea {
      width: 100%;
      padding: 11px 16px;
      border: 1.5px solid var(--pink-border);
      border-radius: var(--radius-sm);
      font-family: 'Poppins', sans-serif;
      font-size: 14px;
      color: var(--text);
      background: var(--pink-bg);
      outline: none;
      transition: border-color .2s, background .2s, box-shadow .2s;
    }
    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
      border-color: var(--pink);
      background: white;
      box-shadow: 0 0 0 3px rgba(232,82,122,.1);
    }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }

    /* ══ STAT CARDS ════════════════════════════════════════════ */
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
      gap: 16px;
      margin-bottom: 1.75rem;
    }

    .stat-card {
      background: var(--white);
      border-radius: var(--radius-lg);
      padding: 1.35rem 1.5rem;
      box-shadow: var(--shadow-sm);
      border: 1px solid #fde8f0;
      display: flex;
      align-items: center;
      gap: 16px;
      transition: transform .2s, box-shadow .2s;
      position: relative;
      overflow: hidden;
    }
    .stat-card::after {
      content: '';
      position: absolute;
      top: 0; left: 0; right: 0;
      height: 3px;
      border-radius: var(--radius-lg) var(--radius-lg) 0 0;
    }
    .stat-card:hover { transform: translateY(-3px); box-shadow: var(--shadow-md); }

    .stat-icon {
      width: 48px;
      height: 48px;
      border-radius: var(--radius-md);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 22px;
      flex-shrink: 0;
    }
    .stat-info { min-width: 0; }
    .stat-card .label {
      font-size: 11px;
      color: var(--text-muted);
      text-transform: uppercase;
      letter-spacing: .7px;
      margin-bottom: 4px;
      white-space: nowrap;
    }
    .stat-card .value {
      font-size: 28px;
      font-weight: 700;
      line-height: 1;
      color: var(--text);
    }

    /* Colores por tipo de stat */
    .stat-pink   .stat-icon { background: #fce4ef; }
    .stat-pink::after       { background: linear-gradient(90deg, var(--pink), var(--pink-dark)); }
    .stat-pink   .value     { color: var(--pink-dark); }

    .stat-purple .stat-icon { background: #f3e5f5; }
    .stat-purple::after     { background: linear-gradient(90deg, #ab47bc, #6a1b9a); }
    .stat-purple .value     { color: #6a1b9a; }

    .stat-green  .stat-icon { background: #e8f5e9; }
    .stat-green::after      { background: linear-gradient(90deg, #66bb6a, #2e7d32); }
    .stat-green  .value     { color: #2e7d32; }

    .stat-blue   .stat-icon { background: #e3f2fd; }
    .stat-blue::after       { background: linear-gradient(90deg, #42a5f5, #1565c0); }
    .stat-blue   .value     { color: #1565c0; }

    .stat-orange .stat-icon { background: #fff3e0; }
    .stat-orange::after     { background: linear-gradient(90deg, #ffa726, #e65100); }
    .stat-orange .value     { color: #e65100; }

    /* ══ PAGE TITLE ════════════════════════════════════════════ */
    .page-title {
      font-size: 22px;
      font-weight: 700;
      color: var(--text);
      margin-bottom: 1.75rem;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .page-title span {
      font-size: 26px;
    }

    /* ══ EMPTY STATE ═══════════════════════════════════════════ */
    .empty-state {
      text-align: center;
      padding: 3rem 1rem;
      color: var(--text-muted);
    }
    .empty-state .empty-icon { font-size: 48px; margin-bottom: 12px; }
    .empty-state p { font-size: 14px; margin-bottom: 16px; }
  </style>
</head>
<body>

<header class="topbar">
  <a class="topbar-brand" href="index.php?action=<?= $rol === 'admin' ? 'adminPanel' : 'dashboard' ?>">
    Aleja-Nails
  </a>

  <div class="topbar-right">
    <?php if ($rol): ?>
      <div class="topbar-user">
        <div class="topbar-avatar">
          <?= $rol === 'admin' ? '👑' : '💅' ?>
        </div>
        <span class="topbar-user-name"><?= htmlspecialchars($nombre) ?></span>
      </div>
      <nav class="topbar-nav" style="display:flex;gap:4px">
        <?php if ($rol === 'admin'): ?>
          <a href="index.php?action=adminPanel">📊 Panel</a>
        <?php else: ?>
          <a href="index.php?action=dashboard">🏠 Inicio</a>
        <?php endif; ?>
        <a href="index.php?action=logout" class="logout">🚪 Salir</a>
      </nav>
    <?php endif; ?>
  </div>
</header>

<?php if (!empty($_SESSION['flash_ok'])): ?>
  <div class="flash ok">✅ <?= htmlspecialchars($_SESSION['flash_ok']) ?></div>
  <?php unset($_SESSION['flash_ok']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['flash_error'])): ?>
  <div class="flash error">⚠️ <?= htmlspecialchars($_SESSION['flash_error']) ?></div>
  <?php unset($_SESSION['flash_error']); ?>
<?php endif; ?>

<div class="layout">
