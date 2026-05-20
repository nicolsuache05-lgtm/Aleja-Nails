<?php
require_once __DIR__ . '/../config/database.php';

class Reserva
{
    private $db;

    public function __construct()
    {
        $this->db = Database::conectar();

        // Permitir id_empleados NULL
        try {
            $this->db->exec("ALTER TABLE reserva MODIFY id_empleados INT(11) NULL");
        } catch (PDOException $e) { /* ya es NULL, ignorar */ }

        // Asegurar que detalle_servicio tenga AUTO_INCREMENT
        try {
            $this->db->exec(
                "ALTER TABLE detalle_servicio MODIFY id_detalle_servicio INT(11) NOT NULL AUTO_INCREMENT"
            );
        } catch (PDOException $e) { /* ya existe, ignorar */ }
    }

    // ── CREAR RESERVA CON MÚLTIPLES SERVICIOS ────────────────
    /**
     * @param array $datos     ['fecha','hora','id_cliente','id_empleados']
     * @param array $servicios [ ['id_servicio'=>N,'precio'=>X], ... ]
     * @return int|false  id_reserva o false
     */
    public function crearConDetalles(array $datos, array $servicios)
    {
        if (empty($servicios)) return false;

        try {
            $this->db->beginTransaction();

            // Insertar reserva (id_servicio = primer servicio, por FK existente)
            $stmtR = $this->db->prepare(
                "INSERT INTO reserva (fecha, hora, id_cliente, id_servicio, id_empleados, estado)
                 VALUES (?, ?, ?, ?, ?, 'pendiente')"
            );
            $stmtR->execute([
                $datos['fecha'],
                $datos['hora'],
                $datos['id_cliente'],
                $servicios[0]['id_servicio'],
                $datos['id_empleados'] ?? null,
            ]);
            $idReserva = (int)$this->db->lastInsertId();

            // Insertar cada servicio en detalle_servicio
            $stmtD = $this->db->prepare(
                "INSERT INTO detalle_servicio (cantidad, precio_unitario, subtotal, id_reserva, id_servicio)
                 VALUES (1, ?, ?, ?, ?)"
            );
            foreach ($servicios as $s) {
                $p = (float)$s['precio'];
                $stmtD->execute([$p, $p, $idReserva, (int)$s['id_servicio']]);
            }

            $this->db->commit();
            return $idReserva;

        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Reserva::crearConDetalles — " . $e->getMessage());
            return false;
        }
    }

    // ── REGISTRAR PAGO ───────────────────────────────────────
    /**
     * @param int    $idReserva
     * @param string $metodo     efectivo | transferencia | tarjeta
     * @param float  $valor
     * @return bool
     */
    public function registrarPago(int $idReserva, string $metodo, float $valor): bool
    {
        try {
            $this->db->beginTransaction();

            // Insertar en tabla pago
            $stmtP = $this->db->prepare(
                "INSERT INTO pago (fecha_pago, metodo_pago, valor_pagado, id_reserva)
                 VALUES (?, ?, ?, ?)"
            );
            $stmtP->execute([date('Y-m-d'), $metodo, $valor, $idReserva]);

            // Marcar reserva como confirmada
            $stmtR = $this->db->prepare(
                "UPDATE reserva SET estado = 'confirmada' WHERE id_reserva = ?"
            );
            $stmtR->execute([$idReserva]);

            $this->db->commit();
            return true;

        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Reserva::registrarPago — " . $e->getMessage());
            return false;
        }
    }

    // ── OBTENER RESERVAS DEL CLIENTE ─────────────────────────
    public function obtenerPorCliente($id_cliente): array
    {
        $stmtR = $this->db->prepare(
            "SELECT reserva.*
             FROM reserva
             WHERE reserva.id_cliente = ?
             ORDER BY reserva.fecha DESC, reserva.hora DESC"
        );
        $stmtR->execute([$id_cliente]);
        $reservas = $stmtR->fetchAll(PDO::FETCH_ASSOC);

        if (empty($reservas)) return [];

        $stmtD = $this->db->prepare(
            "SELECT ds.id_reserva, s.nombre_servicio, ds.precio_unitario AS precio
             FROM detalle_servicio ds
             INNER JOIN servicio s ON ds.id_servicio = s.id_servicio
             WHERE ds.id_reserva = ?"
        );

        // Verificar si la reserva ya tiene pago
        $stmtPago = $this->db->prepare(
            "SELECT id_pago FROM pago WHERE id_reserva = ? LIMIT 1"
        );

        foreach ($reservas as &$r) {
            $stmtD->execute([$r['id_reserva']]);
            $detalles = $stmtD->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($detalles)) {
                $r['servicios']       = $detalles;
                $r['nombre_servicio'] = implode(', ', array_column($detalles, 'nombre_servicio'));
                $r['precio']          = array_sum(array_column($detalles, 'precio'));
            } else {
                // Fallback reservas antiguas (un solo servicio)
                $stmtS = $this->db->prepare(
                    "SELECT nombre_servicio, precio FROM servicio WHERE id_servicio = ?"
                );
                $stmtS->execute([$r['id_servicio']]);
                $srv = $stmtS->fetch(PDO::FETCH_ASSOC);
                $r['servicios']       = $srv ? [$srv] : [];
                $r['nombre_servicio'] = $srv['nombre_servicio'] ?? '—';
                $r['precio']          = (float)($srv['precio'] ?? 0);
            }

            // ¿Ya pagó?
            $stmtPago->execute([$r['id_reserva']]);
            $r['pagado'] = (bool)$stmtPago->fetchColumn();
        }
        unset($r);

        return $reservas;
    }

    // ── OBTENER TODAS (ADMIN) ────────────────────────────────
    public function obtenerTodas(): array
    {
        $stmt = $this->db->prepare(
            "SELECT reserva.*, cliente.nombre AS nombre_cliente,
                    empleados.nombre AS nombre_empleado
             FROM reserva
             INNER JOIN cliente   ON reserva.id_cliente   = cliente.id_cliente
             LEFT  JOIN empleados ON reserva.id_empleados = empleados.id_empleados
             ORDER BY reserva.fecha DESC, reserva.hora DESC"
        );
        $stmt->execute();
        $reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($reservas)) return [];

        $stmtD = $this->db->prepare(
            "SELECT ds.id_reserva, s.nombre_servicio, ds.precio_unitario AS precio
             FROM detalle_servicio ds
             INNER JOIN servicio s ON ds.id_servicio = s.id_servicio
             WHERE ds.id_reserva = ?"
        );

        foreach ($reservas as &$r) {
            $stmtD->execute([$r['id_reserva']]);
            $detalles = $stmtD->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($detalles)) {
                $r['servicios']       = $detalles;
                $r['nombre_servicio'] = implode(', ', array_column($detalles, 'nombre_servicio'));
                $r['precio']          = array_sum(array_column($detalles, 'precio'));
            } else {
                $stmtS = $this->db->prepare(
                    "SELECT nombre_servicio, precio FROM servicio WHERE id_servicio = ?"
                );
                $stmtS->execute([$r['id_servicio']]);
                $srv = $stmtS->fetch(PDO::FETCH_ASSOC);
                $r['servicios']       = $srv ? [$srv] : [];
                $r['nombre_servicio'] = $srv['nombre_servicio'] ?? '—';
                $r['precio']          = (float)($srv['precio'] ?? 0);
            }
        }
        unset($r);

        return $reservas;
    }

    // ── HELPERS ──────────────────────────────────────────────
    public function existeConflicto($fecha, $hora): bool
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM reserva
             WHERE fecha = ? AND hora = ? AND estado != 'cancelada'"
        );
        $stmt->execute([$fecha, $hora]);
        return $stmt->fetchColumn() > 0;
    }

    public function buscarPorId($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM reserva WHERE id_reserva = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function actualizarEstado($id, $estado): bool
    {
        $stmt = $this->db->prepare("UPDATE reserva SET estado = ? WHERE id_reserva = ?");
        return $stmt->execute([$estado, $id]);
    }

    // Obtener total de una reserva desde detalle_servicio
    public function obtenerTotal(int $idReserva): float
    {
        $stmt = $this->db->prepare(
            "SELECT COALESCE(SUM(subtotal), 0) FROM detalle_servicio WHERE id_reserva = ?"
        );
        $stmt->execute([$idReserva]);
        return (float)$stmt->fetchColumn();
    }
}
?>
