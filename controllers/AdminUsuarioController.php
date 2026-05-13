<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Cliente.php';
require_once __DIR__ . '/../models/Reserva.php';
require_once __DIR__ . '/../models/Servicio.php';

class AdminUsuarioController
{
    private PDO $db;
    private Cliente $clienteModel;
    private Reserva $reservaModel;
    private Servicio $servicioModel;

    private const BASE = '/Mi-proyecto-formativo/public/index.php';

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->db = Database::conectar();
        $this->clienteModel = new Cliente();
        $this->reservaModel = new Reserva();
        $this->servicioModel = new Servicio();

        $this->validarAdmin();
    }

    public function dashboard(): void
    {
        $totalClientes = $this->db->query("SELECT COUNT(*) FROM cliente")->fetchColumn();
        $totalReservas = $this->db->query("SELECT COUNT(*) FROM reserva")->fetchColumn();
        $totalPagos = $this->db->query("SELECT COUNT(*) FROM pago")->fetchColumn();
        $totalServicios = $this->db->query("SELECT COUNT(*) FROM servicio")->fetchColumn();

        require __DIR__ . '/../views/dashboard/admin.php';
    }

    public function listarClientes(): void
    {
        $clientes = $this->clienteModel->obtenerTodos();

        require __DIR__ . '/../views/admin/clientes.php';
    }

    public function toggleCliente(): void
    {
        $id = (int)($_GET['id'] ?? 0);

        if ($id <= 0) {
            die("ID inválido");
        }

        $cliente = $this->clienteModel->buscarPorId($id);

        if (!$cliente) {
            die("Cliente no existe");
        }

        $nuevoEstado = isset($cliente['activo']) && $cliente['activo'] == 1 ? 0 : 1;

        $this->clienteModel->cambiarEstado($id, $nuevoEstado);

        header("Location: " . self::BASE . "?action=listarClientes");
        exit;
    }

    public function listarReservas(): void
    {
        $reservas = $this->reservaModel->obtenerTodas();

        require __DIR__ . '/../views/admin/reservas.php';
    }

    public function actualizarReserva(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . self::BASE . "?action=listarReservas");
            exit;
        }

        $id = (int)($_POST['id'] ?? 0);
        $estado = $_POST['estado'] ?? '';

        $estadosValidos = [
            'pendiente',
            'confirmada',
            'en_curso',
            'completada',
            'cancelada'
        ];

        if ($id > 0 && in_array($estado, $estadosValidos, true)) {
            $this->reservaModel->actualizarEstado($id, $estado);
        }

        header("Location: " . self::BASE . "?action=listarReservas");
        exit;
    }

    public function listarServicios(): void
    {
        $servicios = $this->servicioModel->obtenerTodos();

        require __DIR__ . '/../views/admin/servicios.php';
    }

    public function actualizarServicio(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . self::BASE . "?action=listarServicios");
            exit;
        }

        $id = (int)($_POST['id_servicio'] ?? 0);
        $descripcion = trim($_POST['descripcion'] ?? '');
        $precio = (float)($_POST['precio'] ?? 0);

        if ($id > 0 && $precio >= 0) {
            $this->servicioModel->editar($id, [
                'descripcion' => $descripcion,
                'precio'      => $precio,
            ]);

            $_SESSION['flash_ok'] = "Servicio actualizado correctamente.";
        }

        header("Location: " . self::BASE . "?action=listarServicios");
        exit;
    }

    public function eliminarServicio(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . self::BASE . "?action=listarServicios");
            exit;
        }

        $id = (int)($_POST['id_servicio'] ?? 0);

        if ($id > 0) {
            $this->servicioModel->eliminar($id);
            $_SESSION['flash_ok'] = "Servicio eliminado correctamente.";
        }

        header("Location: " . self::BASE . "?action=listarServicios");
        exit;
    }

    public function verPagos(): void
    {
        $sql = "
            SELECT 
                p.*, 
                c.nombre AS nombre_cliente, 
                s.nombre_servicio
            FROM pago p
            INNER JOIN reserva r 
                ON p.id_reserva = r.id_reserva
            INNER JOIN cliente c 
                ON r.id_cliente = c.id_cliente
            INNER JOIN servicio s 
                ON r.id_servicio = s.id_servicio
            ORDER BY p.id_pago DESC
        ";

        $pagos = $this->db->query($sql)->fetchAll();

        require __DIR__ . '/../views/admin/pagos.php';
    }

    private function validarAdmin(): void
    {
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: " . self::BASE . "?action=login");
            exit;
        }

        if (($_SESSION['rol'] ?? '') !== 'admin') {
            http_response_code(403);
            die("Acceso denegado.");
        }
    }
}