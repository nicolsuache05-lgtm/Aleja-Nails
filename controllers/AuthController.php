<?php
require_once __DIR__ . '/../models/Administrador.php';
require_once __DIR__ . '/../models/Cliente.php';

class AuthController {

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
        $usuario  = trim($_POST['correo']   ?? '');   // campo "correo" del form
        $password = trim($_POST['password'] ?? '');

        if (empty($usuario) || empty($password)) {
            $_SESSION['flash_error'] = "Completa todos los campos.";
            $this->redirigir('login');
        }

        // 1. Intentar como administrador (usuario + contraseña en texto plano)
        $admin = $this->adminModel->buscarPorUsuario($usuario);
        if ($admin && $admin['contraseña'] === $password) {
            $_SESSION['usuario_id'] = $admin['id_administrador'];
            $_SESSION['nombre']     = $admin['nombre'];
            $_SESSION['usuario']    = $admin['usuario'];
            $_SESSION['rol']        = 'admin';
            $this->redirigir('adminPanel');
        }

        // 2. Intentar como cliente (correo + password con hash)
        $cliente = $this->clienteModel->buscarPorCorreo($usuario);
        if ($cliente) {
            // Soporte para cuentas sin password aún (campo vacío)
            if (empty($cliente['password'])) {
                $_SESSION['flash_error'] = "Tu cuenta no tiene contraseña. Contacta al administrador.";
                $this->redirigir('login');
            }
            if (password_verify($password, $cliente['password'])) {
                if (isset($cliente['activo']) && !$cliente['activo']) {
                    $_SESSION['flash_error'] = "Tu cuenta está desactivada.";
                    $this->redirigir('login');
                }
                $_SESSION['usuario_id'] = $cliente['id_cliente'];
                $_SESSION['nombre']     = $cliente['nombre'];
                $_SESSION['correo']     = $cliente['correo'];
                $_SESSION['rol']        = 'cliente';
                $this->redirigir('dashboard');
            }
        }

        // 3. Credenciales incorrectas
        $_SESSION['flash_error'] = "Usuario o contraseña incorrectos.";
        $this->redirigir('login');
    }

    // ── MOSTRAR REGISTRO ─────────────────────────────────────

    public function mostrarRegistro(): void
    {
        if (isset($_SESSION['usuario_id'])) {
            $this->redirigirSegunRol();
        }
        require __DIR__ . '/../views/usuarios/registro.php';
    }

    // ── PROCESAR REGISTRO (solo clientes) ────────────────────

    public function procesarRegistro(): void
    {
        $nombre   = trim($_POST['nombre']    ?? '');
        $telefono = trim($_POST['telefono']  ?? '');
        $correo   = trim($_POST['correo']    ?? '');
        $password = $_POST['password']       ?? '';
        $confirmar= $_POST['confirmar']      ?? '';

        if (empty($nombre) || empty($correo) || empty($password)) {
            $_SESSION['flash_error'] = "Nombre, correo y contraseña son obligatorios.";
            $this->redirigir('registro');
        }

        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['flash_error'] = "El correo no tiene un formato válido.";
            $this->redirigir('registro');
        }

        if (strlen($password) < 8) {
            $_SESSION['flash_error'] = "La contraseña debe tener al menos 8 caracteres.";
            $this->redirigir('registro');
        }

        if ($password !== $confirmar) {
            $_SESSION['flash_error'] = "Las contraseñas no coinciden.";
            $this->redirigir('registro');
        }

        if ($this->clienteModel->correoExiste($correo)) {
            $_SESSION['flash_error'] = "Ese correo ya está registrado.";
            $this->redirigir('registro');
        }

        $ok = $this->clienteModel->crear([
            'nombre'   => $nombre,
            'telefono' => $telefono,
            'correo'   => $correo,
            'password' => $password,
        ]);

        if ($ok) {
            $_SESSION['flash_ok'] = "Cuenta creada. Ya puedes iniciar sesión.";
            $this->redirigir('login');
        } else {
            // Si el modelo ya puso un mensaje específico, no lo sobreescribimos
            if (empty($_SESSION['flash_error'])) {
                $_SESSION['flash_error'] = "Error al crear la cuenta. Intenta de nuevo.";
            }
            $this->redirigir('registro');
        }
    }

    // ── LOGOUT ───────────────────────────────────────────────

    public function logout(): void
    {
        session_unset();
        session_destroy();
        $this->redirigir('login');
    }

    // ── GUARDS ───────────────────────────────────────────────

    public function requerirLogin(): void
    {
        if (!isset($_SESSION['usuario_id'])) {
            $this->redirigir('login');
        }
    }

    public function requerirAdmin(): void
    {
        $this->requerirLogin();
        if ($_SESSION['rol'] !== 'admin') {
            http_response_code(403);
            die("Acceso denegado.");
        }
    }

    public function estaLogueado(): bool
    {
        return isset($_SESSION['usuario_id']);
    }

    // ── HELPERS PRIVADOS ─────────────────────────────────────

    private function redirigir(string $action): never
    {
        header("Location: " . self::BASE . "?action=" . $action);
        exit;
    }

    private function redirigirSegunRol(): never
    {
        $this->redirigir(
            $_SESSION['rol'] === 'admin' ? 'adminPanel' : 'dashboard'
        );
    }
}
?>
