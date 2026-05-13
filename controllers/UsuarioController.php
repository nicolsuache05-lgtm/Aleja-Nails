<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Reserva.php';
require_once __DIR__ . '/../models/Servicio.php';
require_once __DIR__ . '/../models/Cliente.php';

class UsuarioController {

    private PDO     $db;
    private Reserva $reservaModel;
    private Servicio $servicioModel;

    private const BASE = '/Mi-proyecto-formativo/public/index.php';

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->db            = Database::conectar();
        $this->reservaModel  = new Reserva();
        $this->servicioModel = new Servicio();
    }

    // ── DASHBOARD CLIENTE ────────────────────────────────────

    public function dashboard(): void
    {
        $this->validarSesion();
        $reservas  = $this->reservaModel->obtenerPorCliente($_SESSION['usuario_id']);
        $servicios = $this->servicioModel->obtenerTodos();
        require __DIR__ . '/../views/dashboard/cliente.php';
    }

    // ── AGENDAR RESERVA ──────────────────────────────────────

    public function agendarCita(): void
    {
        $this->validarSesion();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $fecha       = $_POST['fecha']       ?? '';
            $hora        = $_POST['hora']        ?? '';
            $id_servicio = (int)($_POST['id_servicio'] ?? 0);

            if ($fecha < date('Y-m-d')) {
                $_SESSION['flash_error'] = "No puedes agendar fechas pasadas.";
                header("Location: " . self::BASE . "?action=agendarCita");
                exit;
            }

            if ($this->reservaModel->existeConflicto($fecha, $hora)) {
                $_SESSION['flash_error'] = "Ese horario ya está reservado.";
                header("Location: " . self::BASE . "?action=agendarCita");
                exit;
            }

            $ok = $this->reservaModel->crear([
                'fecha'        => $fecha,
                'hora'         => $hora,
                'id_cliente'   => $_SESSION['usuario_id'],
                'id_servicio'  => $id_servicio,
                'id_empleados' => null,
            ]);

            if ($ok) {
                $_SESSION['flash_ok'] = "Reserva creada exitosamente.";
            } else {
                $_SESSION['flash_error'] = "Error al crear la reserva.";
            }

            header("Location: " . self::BASE . "?action=misReservas");
            exit;
        }

        $servicios = $this->servicioModel->obtenerTodos();
        require __DIR__ . '/../views/usuarios/agendar.php';
    }

    // ── MIS RESERVAS ─────────────────────────────────────────

    public function misReservas(): void
    {
        $this->validarSesion();
        $reservas = $this->reservaModel->obtenerPorCliente($_SESSION['usuario_id']);
        require __DIR__ . '/../views/usuarios/mis-reservas.php';
    }

    // ── CANCELAR RESERVA ─────────────────────────────────────

    public function cancelarReserva(): void
    {
        $this->validarSesion();

        $id = (int)($_GET['id'] ?? 0);
        if (!$id) { die("ID inválido"); }

        $reserva = $this->reservaModel->buscarPorId($id);

        if (!$reserva || $reserva['id_cliente'] != $_SESSION['usuario_id']) {
            die("No autorizado");
        }

        $this->reservaModel->actualizarEstado($id, 'cancelada');
        header("Location: " . self::BASE . "?action=misReservas");
        exit;
    }

    // ── GUARD ────────────────────────────────────────────────

    private function validarSesion(): void
    {
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: " . self::BASE . "?action=login");
            exit;
        }
    }
}
?>