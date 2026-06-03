<?php

require_once __DIR__ . '/../models/Administrador.php';
require_once __DIR__ . '/../models/Cliente.php';

class AuthController
{
    private Administrador $adminModel;
    private Cliente       $clienteModel;

    private const BASE = '/Mi-proyecto-formativo/public/index.php';

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->adminModel   = new Administrador();
        $this->clienteModel = new Cliente();
    }

    // ── MOSTRAR LOGIN ────────────────────────────────────────
    public function login(): void
    {
        if (isset($_SESSION['usuario_id'])) {
            $this->redirigirSegunRol();
        }
        require __DIR__ . '/../views/usuarios/login.php';
    }

    // ── PROCESAR LOGIN ───────────────────────────────────────
    public function procesarLogin(): void
    {
        $usuario  = trim($_POST['correo']   ?? '');
        $password = trim($_POST['password'] ?? '');

        if ($usuario === '' || $password === '') {
            $_SESSION['flash_error'] = "Completa todos los campos.";
            $this->redirigir('login');
            return;
        }

        // — Buscar administrador —
        $admin = $this->adminModel->buscarPorUsuario($usuario);
        if ($admin && isset($admin['contraseña']) && $admin['contraseña'] === $password) {
            $_SESSION['usuario_id'] = $admin['id_administrador'];
            $_SESSION['nombre']     = $admin['nombre'];
            $_SESSION['usuario']    = $admin['usuario'];
            $_SESSION['rol']        = 'admin';
            $this->redirigir('adminPanel');
            return;
        }

        // — Buscar cliente —
        $cliente = $this->clienteModel->buscarPorCorreo($usuario);
        if ($cliente) {
            if (empty($cliente['password'])) {
                $_SESSION['flash_error'] = "Tu cuenta no tiene contraseña configurada.";
                $this->redirigir('login');
                return;
            }

            if (password_verify($password, $cliente['password'])) {
                if (isset($cliente['activo']) && !$cliente['activo']) {
                    $_SESSION['flash_error'] = "Tu cuenta está desactivada. Contacta al salón.";
                    $this->redirigir('login');
                    return;
                }

                $_SESSION['usuario_id'] = $cliente['id_cliente'];
                $_SESSION['nombre']     = $cliente['nombre'];
                $_SESSION['correo']     = $cliente['correo'];
                $_SESSION['rol']        = 'cliente';
                $this->redirigir('dashboard');
                return;
            }
        }

        $_SESSION['flash_error'] = "Usuario o contraseña incorrectos.";
        $this->redirigir('login');
    }

    // ── REGISTRO ─────────────────────────────────────────────
    public function mostrarRegistro(): void
    {
        if (isset($_SESSION['usuario_id'])) {
            $this->redirigirSegunRol();
        }
        require __DIR__ . '/../views/usuarios/registro.php';
    }

    public function procesarRegistro(): void
    {
        $nombre    = trim($_POST['nombre']    ?? '');
        $telefono  = trim($_POST['telefono']  ?? '');
        $correo    = trim($_POST['correo']    ?? '');
        $password  = $_POST['password']  ?? '';
        $confirmar = $_POST['confirmar'] ?? '';

        if ($nombre === '' || $correo === '' || $password === '') {
            $_SESSION['flash_error'] = "Nombre, correo y contraseña son obligatorios.";
            $this->redirigir('registro');
            return;
        }

        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['flash_error'] = "El correo no tiene un formato válido.";
            $this->redirigir('registro');
            return;
        }

        if (strlen($password) < 8) {
            $_SESSION['flash_error'] = "La contraseña debe tener al menos 8 caracteres.";
            $this->redirigir('registro');
            return;
        }

        if ($password !== $confirmar) {
            $_SESSION['flash_error'] = "Las contraseñas no coinciden.";
            $this->redirigir('registro');
            return;
        }

        if ($this->clienteModel->correoExiste($correo)) {
            $_SESSION['flash_error'] = "Ese correo ya está registrado.";
            $this->redirigir('registro');
            return;
        }

        $ok = $this->clienteModel->crear([
            'nombre'   => $nombre,
            'telefono' => $telefono,
            'correo'   => $correo,
            'password' => $password,
        ]);

        if ($ok) {
            $_SESSION['flash_ok'] = "¡Registro exitoso! Ya puedes iniciar sesión.";
            $this->redirigir('login');
        } else {
            $_SESSION['flash_error'] = "Error al crear la cuenta. Intenta de nuevo.";
            $this->redirigir('registro');
        }
    }

    // ── LOGOUT ───────────────────────────────────────────────
    public function logout(): void
    {
        session_destroy();
        header("Location: " . self::BASE);
        exit;
    }

    // ── HELPERS ──────────────────────────────────────────────
    private function redirigir(string $action): void
    {
        header("Location: " . self::BASE . "?action=" . $action);
        exit;
    }

    private function redirigirSegunRol(): void
    {
        if (($_SESSION['rol'] ?? '') === 'admin') {
            $this->redirigir('adminPanel');
        } else {
            $this->redirigir('dashboard');
        }
    }
}
?>
