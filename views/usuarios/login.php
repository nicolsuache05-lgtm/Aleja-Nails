<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión — Aleja-Nails</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            min-height: 100vh;
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #ffb7d4ff 50%, #feaad0ff 50%);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-card {
            width: 380px;
            background: white;
            border-radius: 8px;
            padding: 35px 30px;
            text-align: center;
            box-shadow: 0 10px 35px rgba(0,0,0,.12);
        }

        .logo {
            width: 75px;
            height: 75px;
            margin: 0 auto 18px;
            border-radius: 50%;
            background: linear-gradient(135deg, #f72585, #ff8ac2);
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 36px;
            box-shadow: 0 5px 15px rgba(247,37,133,.35);
        }

        h1 {
            font-size: 22px;
            color: #333;
            margin-bottom: 8px;
        }

        p {
            font-size: 13px;
            color: #999;
            margin-bottom: 25px;
        }

        input {
            width: 100%;
            padding: 14px;
            margin-bottom: 12px;
            border: none;
            background: #f7f7fb;
            color: #333;
            outline: none;
        }

        input:focus {
            border-bottom: 2px solid #f72585;
        }

        .forgot {
            display: block;
            text-align: left;
            font-size: 11px;
            color: #777;
            margin-bottom: 20px;
            text-decoration: none;
        }

        button {
            width: 100%;
            padding: 14px;
            border: none;
            background: #f72585;
            color: white;
            font-weight: bold;
            cursor: pointer;
            border-radius: 2px;
        }

        button:hover {
            background: #d61f73;
        }

        .flash {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 6px;
            font-size: 13px;
        }

        .flash-error {
            background: #ffe0e0;
            color: #a32d2d;
        }

        .flash-ok {
            background: #e5f6df;
            color: #3b6d11;
        }
    </style>
</head>

<body>

<div class="login-card">

    <div class="logo">💅</div>

    <h1>¡Bienvenida!</h1>
    <p>Inicia sesión con tu usuario o correo</p>

    <?php if (!empty($_SESSION['flash_error'])): ?>
        <div class="flash flash-error">
            <?= htmlspecialchars($_SESSION['flash_error']) ?>
        </div>
        <?php unset($_SESSION['flash_error']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['flash_ok'])): ?>
        <div class="flash flash-ok">
            <?= htmlspecialchars($_SESSION['flash_ok']) ?>
        </div>
        <?php unset($_SESSION['flash_ok']); ?>
    <?php endif; ?>

    <form action="/Mi-proyecto-formativo/public/index.php?action=procesarLogin" method="POST">

        <input 
            type="text" 
            name="correo" 
            placeholder="Usuario o correo"
            required
        >

        <input 
            type="password" 
            name="password" 
            placeholder="Contraseña"
            required
        >

        <a href="#" class="forgot">¿Olvidaste tu contraseña?</a>

        <button type="submit">
            INICIAR SESIÓN
        </button>

    </form>

</div>

</body>
</html>