<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Reserva.php';
require_once __DIR__ . '/../models/Servicio.php';
require_once __DIR__ . '/../models/Cliente.php';

class UsuarioController {

    private PDO      $db;
    private Reserva  $reservaModel;
    private Servicio $servicioModel;

    private const BASE = '/Mi-proyecto-formativo/public/index.php';

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
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

            $fecha    = trim($_POST['fecha'] ?? '');
            $hora     = trim($_POST['hora']  ?? '');
            $ids      = $_POST['servicios']  ?? [];

            // Validaciones
            if (empty($ids)) {
                $_SESSION['flash_error'] = "Debes seleccionar al menos un servicio.";
                header("Location: " . self::BASE . "?action=agendarCita");
                exit;
            }

            if ($fecha < date('Y-m-d')) {
                $_SESSION['flash_error'] = "No puedes agendar en fechas pasadas.";
                header("Location: " . self::BASE . "?action=agendarCita");
                exit;
            }

            if ($this->reservaModel->existeConflicto($fecha, $hora)) {
                $_SESSION['flash_error'] = "Ese horario ya está reservado. Elige otro.";
                header("Location: " . self::BASE . "?action=agendarCita");
                exit;
            }

            // Obtener precios reales desde BD
            $serviciosSeleccionados = [];
            foreach ($ids as $idS) {
                $idS = (int)$idS;
                $srv = $this->servicioModel->buscarPorId($idS);
                if ($srv) {
                    $serviciosSeleccionados[] = [
                        'id_servicio' => $idS,
                        'precio'      => (float)$srv['precio'],
                    ];
                }
            }

            if (empty($serviciosSeleccionados)) {
                $_SESSION['flash_error'] = "Los servicios seleccionados no son válidos.";
                header("Location: " . self::BASE . "?action=agendarCita");
                exit;
            }

            $idReserva = $this->reservaModel->crearConDetalles([
                'fecha'        => $fecha,
                'hora'         => $hora,
                'id_cliente'   => $_SESSION['usuario_id'],
                'id_empleados' => null,
            ], $serviciosSeleccionados);

            if ($idReserva) {
                $_SESSION['flash_ok'] = "¡Reserva creada! Ahora realiza tu pago.";
                // Redirigir directo al pago
                header("Location: " . self::BASE . "?action=pagar&id=" . $idReserva);
            } else {
                $_SESSION['flash_error'] = "Error al crear la reserva. Intenta de nuevo.";
                header("Location: " . self::BASE . "?action=agendarCita");
            }
            exit;
        }

        $servicios = $this->servicioModel->obtenerTodos();
        require __DIR__ . '/../views/usuarios/agendar.php';
    }

    // ── PAGAR RESERVA ────────────────────────────────────────

    public function pagar(): void
    {
        $this->validarSesion();

        $idReserva = (int)($_GET['id'] ?? 0);
        if (!$idReserva) {
            header("Location: " . self::BASE . "?action=misReservas");
            exit;
        }

        $reserva = $this->reservaModel->buscarPorId($idReserva);

        // Verificar que la reserva pertenece al cliente
        if (!$reserva || $reserva['id_cliente'] != $_SESSION['usuario_id']) {
            die("No autorizado");
        }

        // Obtener detalles de servicios y total
        $total    = $this->reservaModel->obtenerTotal($idReserva);
        $detalles = $this->obtenerDetallesReserva($idReserva);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $metodo = trim($_POST['metodo_pago'] ?? '');
            $metodos_validos = ['efectivo', 'transferencia'];

            if (!in_array($metodo, $metodos_validos)) {
                $_SESSION['flash_error'] = "Selecciona un método de pago válido.";
                header("Location: " . self::BASE . "?action=pagar&id=" . $idReserva);
                exit;
            }

            $ok = $this->reservaModel->registrarPago($idReserva, $metodo, $total);

            if ($ok) {
                $_SESSION['flash_ok'] = "✅ Pago registrado exitosamente. ¡Tu cita está confirmada!";
            } else {
                $_SESSION['flash_error'] = "Error al registrar el pago. Intenta de nuevo.";
            }

            header("Location: " . self::BASE . "?action=misReservas");
            exit;
        }

        require __DIR__ . '/../views/usuarios/pagar.php';
    }

    // Helper privado para obtener detalles de una reserva
    private function obtenerDetallesReserva(int $idReserva): array
    {
        $db   = Database::conectar();
        $stmt = $db->prepare(
            "SELECT s.nombre_servicio, ds.precio_unitario AS precio
             FROM detalle_servicio ds
             INNER JOIN servicio s ON ds.id_servicio = s.id_servicio
             WHERE ds.id_reserva = ?"
        );
        $stmt->execute([$idReserva]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
        if (!$id) die("ID inválido");

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
