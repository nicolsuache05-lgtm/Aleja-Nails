<?php
require_once __DIR__ . '/../config/database.php';

class Reserva
{
    private $db;

    public function __construct()
    {
        $this->db = Database::conectar();
        // Asegurar que id_empleados pueda ser NULL para evitar errores al agendar
        try {
            $this->db->exec("ALTER TABLE reserva MODIFY id_empleados INT(11) NULL");
        } catch (PDOException $e) {
            // Ignorar
        }
    }

    public function crear($datos)
    {
        $sql = "INSERT INTO reserva
                (fecha, hora, id_cliente, id_servicio, id_empleados, estado)
                VALUES (?, ?, ?, ?, ?, 'pendiente')";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute(array(
            $datos['fecha'],
            $datos['hora'],
            $datos['id_cliente'],
            $datos['id_servicio'],
            $datos['id_empleados']
        ));
    }

    public function existeConflicto($fecha, $hora)
    {
        $sql = "SELECT COUNT(*)
                FROM reserva
                WHERE fecha = ?
                AND hora = ?
                AND estado != 'cancelada'";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(array($fecha, $hora));

        return $stmt->fetchColumn() > 0;
    }

    public function obtenerPorCliente($id_cliente)
    {
        $sql = "SELECT reserva.*,
                       servicio.nombre_servicio,
                       servicio.precio
                FROM reserva
                INNER JOIN servicio
                    ON reserva.id_servicio = servicio.id_servicio
                WHERE reserva.id_cliente = ?
                ORDER BY reserva.fecha DESC, reserva.hora DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(array($id_cliente));

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerTodas()
    {
        $sql = "SELECT reserva.*,
                       cliente.nombre AS nombre_cliente,
                       servicio.nombre_servicio,
                       servicio.precio,
                       empleados.nombre AS nombre_empleado
                FROM reserva
                INNER JOIN cliente
                    ON reserva.id_cliente = cliente.id_cliente
                INNER JOIN servicio
                    ON reserva.id_servicio = servicio.id_servicio
                LEFT JOIN empleados
                    ON reserva.id_empleados = empleados.id_empleados
                ORDER BY reserva.fecha DESC, reserva.hora DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId($id)
    {
        $sql = "SELECT *
                FROM reserva
                WHERE id_reserva = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(array($id));

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function actualizarEstado($id, $estado)
    {
        $sql = "UPDATE reserva
                SET estado = ?
                WHERE id_reserva = ?";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute(array($estado, $id));
    }
}
?>