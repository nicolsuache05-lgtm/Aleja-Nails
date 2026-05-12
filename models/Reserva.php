<?php
require_once __DIR__ . '/../config/database.php';

/**
 * Modelo para la tabla `reserva`
 * Columnas: id_reserva, fecha, hora, estado, id_cliente, id_servicio, id_empleados
 */
class Reserva {

    private PDO $db;

    public function __construct()
    {
        $this->db = Database::conectar();
    }

    /** Crea una nueva reserva */
    public function crear(array $datos): bool
    {
        try {
            $stmt = $this->db->prepare(
                "INSERT INTO reserva (fecha, hora, estado, id_cliente, id_servicio, id_empleados)
                 VALUES (?, ?, 'pendiente', ?, ?, ?)"
            );
            return $stmt->execute([
                $datos['fecha'],
                $datos['hora'],
                $datos['id_cliente'],
                $datos['id_servicio'],
                $datos['id_empleados'] ?? null,
            ]);
        } catch (PDOException $e) {
            error_log("Reserva::crear — " . $e->getMessage());
            return false;
        }
    }

    /** Reservas de un cliente con info de servicio */
    public function obtenerPorCliente(int $id_cliente): array
    {
        $stmt = $this->db->prepare(
            "SELECT r.*, s.nombre_servicio, s.precio
             FROM reserva r
             INNER JOIN servicio s ON r.id_servicio = s.id_servicio
             WHERE r.id_cliente = ?
             ORDER BY r.fecha DESC, r.hora DESC"
        );
        $stmt->execute([$id_cliente]);
        return $stmt->fetchAll();
    }

    /** Todas las reservas (para admin) */
    public function obtenerTodas(): array
    {
        return $this->db->query(
            "SELECT r.*, c.nombre AS nombre_cliente, s.nombre_servicio
             FROM reserva r
             INNER JOIN cliente  c ON r.id_cliente  = c.id_cliente
             INNER JOIN servicio s ON r.id_servicio = s.id_servicio
             ORDER BY r.fecha DESC, r.hora DESC"
        )->fetchAll();
    }

    /** Busca por id */
    public function buscarPorId(int $id): array|false
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM reserva WHERE id_reserva = ? LIMIT 1"
        );
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /** Actualiza el estado de una reserva */
    public function actualizarEstado(int $id, string $estado): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE reserva SET estado=? WHERE id_reserva=?"
        );
        return $stmt->execute([$estado, $id]);
    }

    /** Verifica si ya hay reserva en esa fecha/hora */
    public function existeConflicto(string $fecha, string $hora): bool
    {
        $stmt = $this->db->prepare(
            "SELECT id_reserva FROM reserva
             WHERE fecha=? AND hora=? AND estado != 'cancelada' LIMIT 1"
        );
        $stmt->execute([$fecha, $hora]);
        return $stmt->rowCount() > 0;
    }
}
?>
