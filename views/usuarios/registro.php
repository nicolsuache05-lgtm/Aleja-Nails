<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registrarse — Aleja-Nails</title>
  <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body { min-height:100vh; background:linear-gradient(160deg,#f9c4d8 0%,#f5a8c8 40%,#f0c0d8 100%); display:flex; align-items:center; justify-content:center; font-family:'Poppins',sans-serif; padding:20px; }
    .card { background:linear-gradient(170deg,#fce4ef 0%,#f9d0e4 100%); border-radius:32px; padding:44px 40px 36px; width:100%; max-width:420px; box-shadow:0 8px 40px rgba(200,60,100,.12); }
    .title { font-family:'Great Vibes',cursive; font-size:46px; color:#c0375a; text-align:center; margin-bottom:6px; }
    .subtitle { text-align:center; color:#c06080; font-size:13px; margin-bottom:28px; }
    .field-label { font-size:10px; font-weight:600; letter-spacing:1.2px; color:#b05070; text-transform:uppercase; margin-bottom:6px; display:block; }
    .field-wrap { margin-bottom:14px; }
    .input-row { display:flex; align-items:center; background:rgba(255,255,255,.7); border-radius:50px; border:1.5px solid rgba(255,255,255,.9); transition:border-color .2s; }
    .input-row:focus-within { background:rgba(255,255,255,.9); border-color:#e06090; }
    .input-row input { width:100%; padding:12px 18px; background:transparent; border:none; outline:none; font-family:'Poppins',sans-serif; font-size:13px; color:#7a3050; }
    .input-row input::placeholder { color:#c090a8; font-weight:300; }
    .form-row { display:grid; grid-template-columns:1fr 1fr; gap:12px; }
    .btn-reg { width:100%; padding:15px; background:linear-gradient(135deg,#e8527a,#d03868); border:none; border-radius:50px; font-family:'Poppins',sans-serif; font-size:15px; font-weight:600; color:white; cursor:pointer; margin-top:8px; box-shadow:0 4px 20px rgba(200,50,90,.3); transition:opacity .2s,transform .15s; }
    .btn-reg:hover { opacity:.92; transform:translateY(-1px); }
    .login-link { text-align:center; font-size:12px; color:#a06070; margin-top:16px; }
    .login-link a { color:#c03060; font-weight:600; text-decoration:underline; }
    .flash-msg { padding:10px 16px; border-radius:12px; font-size:12px; text-align:center; margin-bottom:14px; }
    .flash-error { background:#fcebeb; color:#a32d2d; }
  </style>
</head>
<body>
<div class="card">
  <div class="title">Regístrate</div>
  <p class="subtitle">Crea tu cuenta y gestiona tus citas</p>

  <?php if (!empty($_SESSION['flash_error'])): ?>
    <div class="flash-msg flash-error"><?= htmlspecialchars($_SESSION['flash_error']) ?></div>
    <?php unset($_SESSION['flash_error']); ?>
  <?php endif; ?>

  <form action="/Mi-proyecto-formativo/public/index.php?action=procesarRegistro" method="POST">

    <div class="form-row">
      <div class="field-wrap">
        <label class="field-label">Nombre</label>
        <div class="input-row"><input name="nombre" type="text" placeholder="Alejandra" required value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>"></div>
      </div>
      <div class="field-wrap">
        <label class="field-label">Apellido</label>
        <div class="input-row"><input name="apellido" type="text" placeholder="García" required value="<?= htmlspecialchars($_POST['apellido'] ?? '') ?>"></div>
      </div>
    </div>

    <div class="field-wrap">
      <label class="field-label">Correo electrónico</label>
      <div class="input-row"><input name="correo" type="email" placeholder="correo@ejemplo.com" required value="<?= htmlspecialchars($_POST['correo'] ?? '') ?>"></div>
    </div>

    <div class="field-wrap">
      <label class="field-label">Teléfono</label>
      <div class="input-row"><input name="telefono" type="tel" placeholder="+57 300 000 0000" value="<?= htmlspecialchars($_POST['telefono'] ?? '') ?>"></div>
    </div>

    <div class="field-wrap">
      <label class="field-label">Contraseña</label>
      <div class="input-row"><input name="password" type="password" placeholder="Mínimo 8 caracteres" required minlength="8"></div>
    </div>

    <div class="field-wrap">
      <label class="field-label">Confirmar contraseña</label>
      <div class="input-row"><input name="confirmar" type="password" placeholder="Repite tu contraseña" required></div>
    </div>

    <button type="submit" class="btn-reg">Crear cuenta ✦</button>
  </form>

  <p class="login-link">¿Ya tienes cuenta? <a href="/Mi-proyecto-formativo/public/index.php?action=login">Inicia sesión</a></p>
</div>
</body>
</html>
