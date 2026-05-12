<?php
/**
 * public/index.php — Router principal de Aleja-Nails
 * URL: http://localhost:8081/Mi-proyecto-formativo/public/index.php?action=login
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Administrador.php';
require_once __DIR__ . '/../models/Cliente.php';
require_once __DIR__ . '/../models/Reserva.php';
require_once __DIR__ . '/../models/Servicio.php';
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/UsuarioController.php';
require_once __DIR__ . '/../controllers/AdminUsuarioController.php';

$action = $_GET['action'] ?? 'login';

// Instanciar solo lo necesario para evitar validaciones prematuras
$auth = new AuthController();

switch ($action) {

    // ── Página de inicio ──────────────────────────────────
    case 'home':
        require __DIR__ . '/../index.php';
        break;

    // ── Auth ──────────────────────────────────────────────
    case 'login':
        $auth->login();
        break;

    case 'procesarLogin':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth->procesarLogin();
        } else {
            header('Location: index.php?action=login');
        }
        break;

    case 'registro':
        $auth->mostrarRegistro();
        break;

    case 'procesarRegistro':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth->procesarRegistro();
        } else {
            header('Location: index.php?action=registro');
        }
        break;

    case 'logout':
        $auth->logout();
        break;

    // ── Cliente ───────────────────────────────────────────
    case 'dashboard':
        (new UsuarioController())->dashboard();
        break;

    case 'agendarCita':
        (new UsuarioController())->agendarCita();
        break;

    case 'misReservas':
        (new UsuarioController())->misReservas();
        break;

    case 'cancelarReserva':
        (new UsuarioController())->cancelarReserva();
        break;

    // ── Admin ─────────────────────────────────────────────
    case 'adminPanel':
        (new AdminUsuarioController())->dashboard();
        break;

    case 'listarClientes':
        (new AdminUsuarioController())->listarClientes();
        break;

    case 'toggleCliente':
        (new AdminUsuarioController())->toggleCliente();
        break;

    case 'listarReservas':
        (new AdminUsuarioController())->listarReservas();
        break;

    case 'actualizarReserva':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            (new AdminUsuarioController())->actualizarReserva();
        } else {
            header('Location: index.php?action=listarReservas');
        }
        break;

    case 'listarServicios':
        (new AdminUsuarioController())->listarServicios();
        break;

    case 'actualizarServicio':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            (new AdminUsuarioController())->actualizarServicio();
        } else {
            header('Location: index.php?action=listarServicios');
        }
        break;

    case 'verPagos':
        (new AdminUsuarioController())->verPagos();
        break;

    // ── 404 ───────────────────────────────────────────────
    default:
        http_response_code(404);
        echo '<div style="text-align:center;margin-top:4rem;font-family:sans-serif">
                <h2>404 — Página no encontrada</h2>
                <a href="index.php?action=home">Volver al inicio</a>
              </div>';
}
?>
