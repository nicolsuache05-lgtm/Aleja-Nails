<?php
// views/layouts/header.php
// Uso: include con $titulo definido antes de llamarlo
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
  <link rel="icon" type="image/png" href="/Mi-proyecto-formativo/img/uñasss.jpg">
  <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Poppins', sans-serif; background: #fdf0f5; color: #4a2030; min-height: 100vh; }

    /* ── Topbar ── */
    .topbar {
      background: linear-gradient(135deg, #fba0c6ff 0%, #dd6088ff 100%);
      color: white;
      padding: 0 2rem;
      height: 60px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      box-shadow: 0 2px 12px rgba(200,50,90,.25);
    }
    .topbar-brand {
      font-family: 'Great Vibes', cursive;
      font-size: 28px;
      letter-spacing: .5px;
      color: white;
      text-decoration: none;
    }
    .topbar-nav { display: flex; align-items: center; gap: 1.25rem; }
    .topbar-nav a {
      color: rgba(255,255,255,.88);
      text-decoration: none;
      font-size: 13px;
      font-weight: 500;
      padding: 6px 14px;
      border-radius: 20px;
      transition: background .2s;
    }
    .topbar-nav a:hover { background: rgba(255,255,255,.18); }
    .topbar-nav a.logout {
      background: rgba(255,255,255,.18);
      color: white;
    }
    .topbar-user {
      font-size: 13px;
      color: rgba(255,255,255,.85);
    }

    /* ── Flash messages ── */
    .flash {
      padding: 12px 2rem;
      font-size: 14px;
      font-weight: 500;
      text-align: center;
    }
    .flash.ok    { background: #eaf3de; color: #3b6d11; }
    .flash.error { background: #fcebeb; color: #a32d2d; }

    /* ── Layout principal ── */
    .layout { display: flex; min-height: calc(100vh - 60px); }

    /* ── Main content ── */
    main { flex: 1; padding: 2rem; }

    /* ── Cards generales ── */
    .card {
      background: white;
      border-radius: 20px;
      padding: 1.75rem;
      box-shadow: 0 2px 16px rgba(200,60,100,.07);
      margin-bottom: 1.5rem;
    }
    .card h2 {
      font-size: 18px;
      font-weight: 600;
      color: #c0375a;
      margin-bottom: 1rem;
    }

    /* ── Botones ── */
    .btn {
      display: inline-block;
      padding: 9px 20px;
      border-radius: 50px;
      border: none;
      font-family: 'Poppins', sans-serif;
      font-size: 13px;
      font-weight: 500;
      cursor: pointer;
      text-decoration: none;
      transition: opacity .2s, transform .15s;
    }
    .btn:hover { opacity: .88; transform: translateY(-1px); }
    .btn-primary { background: linear-gradient(135deg,#e8527a,#c93060); color: white; }
    .btn-outline  { background: transparent; border: 1.5px solid #e8527a; color: #c93060; }
    .btn-danger   { background: #fcebeb; color: #a32d2d; }
    .btn-sm { padding: 6px 14px; font-size: 12px; }

    /* ── Tabla ── */
    .tabla-wrap { overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; font-size: 13px; }
    th { background: #fce4ef; color: #993556; font-weight: 600; padding: 10px 14px; text-align: left; }
    td { padding: 10px 14px; border-bottom: 1px solid #fce4ef; color: #4a2030; }
    tr:last-child td { border-bottom: none; }
    tr:hover td { background: #fdf0f5; }

    /* ── Badges de estado ── */
    .badge {
      display: inline-block;
      padding: 3px 10px;
      border-radius: 20px;
      font-size: 11px;
      font-weight: 600;
    }
    .badge-pendiente  { background: #faeeda; color: #854f0b; }
    .badge-confirmada { background: #fbeaf0; color: #993556; }
    .badge-en_curso   { background: #e6f1fb; color: #185fa5; }
    .badge-completada { background: #eaf3de; color: #3b6d11; }
    .badge-cancelada  { background: #f1efe8; color: #5f5e5a; }
    .badge-admin      { background: #fbeaf0; color: #72243e; }
    .badge-cliente    { background: #e1f5ee; color: #0f6e56; }
    .badge-activo     { background: #eaf3de; color: #3b6d11; }
    .badge-inactivo   { background: #fcebeb; color: #a32d2d; }

    /* ── Forms ── */
    .form-group { margin-bottom: 16px; }
    .form-group label { display: block; font-size: 11px; font-weight: 600; color: #b05070; text-transform: uppercase; letter-spacing: .8px; margin-bottom: 6px; }
    .form-group input,
    .form-group select,
    .form-group textarea {
      width: 100%;
      padding: 11px 16px;
      border: 1.5px solid #f4c0d1;
      border-radius: 12px;
      font-family: 'Poppins', sans-serif;
      font-size: 14px;
      color: #4a2030;
      background: #fdf0f5;
      outline: none;
      transition: border-color .2s;
    }
    .form-group input:focus,
    .form-group select:focus { border-color: #df7390ff; background: white; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }

    /* ── Stats cards ── */
    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px,1fr)); gap: 16px; margin-bottom: 1.5rem; }
    .stat-card { background: white; border-radius: 16px; padding: 1.25rem; box-shadow: 0 2px 12px rgba(200,60,100,.07); }
    .stat-card .label { font-size: 11px; color: #b07090; text-transform: uppercase; letter-spacing: .8px; margin-bottom: 6px; }
    .stat-card .value { font-size: 26px; font-weight: 600; color: #c0375a; }
    .stat-card .change { font-size: 11px; color: #3b6d11; margin-top: 2px; }
  </style>
</head>
<body>

<header class="topbar">
  <a class="topbar-brand" href="index.php?action=<?= $rol === 'admin' ? 'adminPanel' : 'dashboard' ?>">Aleja-Nails</a>
  <nav class="topbar-nav">
    <?php if ($rol): ?>
      <span class="topbar-user">Hola, <?= htmlspecialchars($nombre) ?></span>
      <?php if ($rol === 'admin'): ?>
        <a href="index.php?action=adminPanel">Panel admin</a>
      <?php else: ?>
        <a href="index.php?action=dashboard">Mis citas</a>
      <?php endif; ?>
      <a href="index.php?action=logout" class="logout">Cerrar sesión</a>
    <?php endif; ?>
  </nav>
</header>

<?php if (!empty($_SESSION['flash_ok'])): ?>
  <div class="flash ok"><?= htmlspecialchars($_SESSION['flash_ok']) ?></div>
  <?php unset($_SESSION['flash_ok']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['flash_error'])): ?>
  <div class="flash error"><?= htmlspecialchars($_SESSION['flash_error']) ?></div>
  <?php unset($_SESSION['flash_error']); ?>
<?php endif; ?>

<div class="layout">
