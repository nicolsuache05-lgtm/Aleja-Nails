<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Iniciar Sesión — Aleja-Nails</title>
  <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      min-height: 100vh;
      background: linear-gradient(160deg, #ffeefaff 0%, #ffeefaff 40%, #ffeefaff 100%);
      display: flex; align-items: center; justify-content: center;
      font-family: 'Poppins', sans-serif; padding: 20px;
    }
    .card {
      background: linear-gradient(170deg, #fce4ef 0%, #fdb4d7ff 100%);
      border-radius: 32px; padding: 48px 40px 40px;
      width: 100%; max-width: 400px;
      box-shadow: 0 8px 40px rgba(200,60,100,.12);
    }
    .title { font-family: 'Great Vibes',cursive; font-size: 52px; color: #c0375a; text-align: center; line-height:1.1; margin-bottom:12px; }
    .subtitle { text-align:center; color:#c06080; font-size:14px; line-height:1.5; margin-bottom:32px; }
    .field-label { font-size:11px; font-weight:600; letter-spacing:1.2px; color:#b05070; text-transform:uppercase; margin-bottom:8px; display:block; }
    .field-wrap { margin-bottom:18px; }
    .input-row { position:relative; display:flex; align-items:center; background:rgba(255,255,255,.7); border-radius:50px; border:1.5px solid rgba(255,255,255,.9); transition:border-color .2s,background .2s; }
    .input-row:focus-within { background:rgba(255,255,255,.9); border-color:#e06090; }
    .input-row input { width:100%; padding:14px 20px; background:transparent; border:none; outline:none; font-family:'Poppins',sans-serif; font-size:14px; color:#7a3050; }
    .input-row input::placeholder { color:#c090a8; font-weight:300; }
    .input-row .arrow { padding-right:18px; color:#d090b0; font-size:16px; }
    .forgot { text-align:right; margin-top:-8px; margin-bottom:28px; }
    .forgot a { font-size:12px; color:#b05070; text-decoration:none; }
    .forgot a:hover { text-decoration:underline; }
    .btn-login {
      width:100%; padding:16px; background:linear-gradient(135deg,#e8527a 0%,#d03868 100%);
      border:none; border-radius:50px; font-family:'Poppins',sans-serif;
      font-size:16px; font-weight:600; color:white; cursor:pointer;
      display:flex; align-items:center; justify-content:center; gap:10px;
      transition:opacity .2s,transform .15s; box-shadow:0 4px 20px rgba(200,50,90,.35);
      margin-bottom:28px; letter-spacing:.3px;
    }
    .btn-login:hover { opacity:.92; transform:translateY(-1px); }
    .btn-login:active { transform:scale(.98); }
    .divider { display:flex; align-items:center; gap:12px; margin-bottom:20px; }
    .divider-line { flex:1; height:1px; background:rgba(190,100,130,.25); }
    .divider-text { font-size:12px; color:#b07090; white-space:nowrap; }
    .social-row { display:flex; justify-content:center; gap:16px; margin-bottom:28px; }
    .social-btn { width:52px; height:52px; border-radius:50%; border:none; cursor:pointer; display:flex; align-items:center; justify-content:center; transition:transform .15s,box-shadow .15s; }
    .social-btn:hover { transform:translateY(-2px); box-shadow:0 4px 12px rgba(0,0,0,.12); }
    .social-btn.google,.social-btn.facebook { background:white; }
    .social-btn.tiktok { background:#111; }
    .social-btn svg { width:22px; height:22px; }
    .register-link { text-align:center; font-size:13px; color:#a06070; }
    .register-link a { color:#c03060; font-weight:600; text-decoration:underline; text-underline-offset:2px; }
    .flash-msg { padding:10px 16px; border-radius:12px; font-size:13px; text-align:center; margin-bottom:16px; }
    .flash-error { background:#fcebeb; color:#a32d2d; }
    .flash-ok    { background:#eaf3de; color:#3b6d11; }
  </style>
</head>
<body>
<div class="card">

  <div class="title">Inicia Sesión</div>
  <p class="subtitle">Bienvenida.<br>Inicia sesión para continuar.</p>

  <?php if (!empty($_SESSION['flash_error'])): ?>
    <div class="flash-msg flash-error"><?= htmlspecialchars($_SESSION['flash_error']) ?></div>
    <?php unset($_SESSION['flash_error']); ?>
  <?php endif; ?>
  <?php if (!empty($_SESSION['flash_ok'])): ?>
    <div class="flash-msg flash-ok"><?= htmlspecialchars($_SESSION['flash_ok']) ?></div>
    <?php unset($_SESSION['flash_ok']); ?>
  <?php endif; ?>
  
  <form action="/Mi-proyecto-formativo/public/index.php?action=procesarLogin" method="POST">

    <div class="field-wrap">
      <label class="field-label" for="correo">Usuario o Correo</label>
      <div class="input-row">
        <input id="correo" name="correo" type="text" placeholder="Usuario o correo electrónico" required
               value="<?= htmlspecialchars($_POST['correo'] ?? '') ?>">
        <span class="arrow">›</span>
      </div>
    </div>

    <div class="field-wrap">
      <label class="field-label" for="password">Tu Contraseña</label>
      <div class="input-row">
        <input id="password" name="password" type="password" placeholder="Tu Contraseña" required>
        <span class="arrow">›</span>
      </div>
    </div>

    <div class="forgot"><a href="#">¿Olvidaste tu contraseña?</a></div>

    <button type="submit" class="btn-login">Iniciar Sesión <span>✦</span></button>
  </form>

  <div class="divider">
    <div class="divider-line"></div>
    <span class="divider-text">o contínua con</span>
    <div class="divider-line"></div>
  </div>

  <div class="social-row">
    <button class="social-btn google" title="Google">
      <svg viewBox="0 0 24 24"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
    </button>
    <button class="social-btn facebook" title="Facebook">
      <svg viewBox="0 0 24 24"><path d="M24 12.073C24 5.405 18.627 0 12 0S0 5.405 0 12.073C0 18.1 4.388 23.094 10.125 24v-8.437H7.078v-3.49h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.49h-2.796V24C19.612 23.094 24 18.1 24 12.073z" fill="#1877F2"/></svg>
    </button>
    <button class="social-btn tiktok" title="TikTok">
      <svg viewBox="0 0 24 24" fill="white"><path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1V9.01a6.33 6.33 0 00-.79-.05 6.34 6.34 0 00-6.34 6.34 6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.33-6.34V8.69a8.18 8.18 0 004.79 1.53V6.77a4.85 4.85 0 01-1.02-.08z"/></svg>
    </button>
  </div>

  <p class="register-link">¿No tienes una cuenta? <a href="/Mi-proyecto-formativo/public/index.php?action=registro">Regístrate aquí</a></p>
</div>
</body>
</html> 