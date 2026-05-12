<?php
require_once __DIR__ . '/../config/database.php';

/**
 * Modelo para la tabla `servicio`
 * Columnas: id_servicio, nombre_servicio, descripcion, precio, id_administrador
 */
class Servicio {

    private PDO $db;

    public function __construct()
    {
        $this->db = Database::conectar();
    }

    public function obtenerTodos(): array
    {
        try {
            // Agregar columna categoria si no existe
            $cols = $this->db->query("DESCRIBE servicio")->fetchAll(PDO::FETCH_COLUMN);
            if (!in_array('categoria', $cols)) {
                $this->db->exec(
                    "ALTER TABLE servicio ADD COLUMN categoria VARCHAR(50) NOT NULL DEFAULT 'Manicure' AFTER descripcion"
                );
            }
            return $this->db
                ->query("SELECT * FROM servicio ORDER BY categoria ASC, nombre_servicio ASC")
                ->fetchAll();
        } catch (PDOException $e) {
            error_log("Servicio::obtenerTodos — " . $e->getMessage());
            // Fallback sin categoria
            return $this->db
                ->query("SELECT *, 'Manicure' AS categoria FROM servicio ORDER BY nombre_servicio ASC")
                ->fetchAll();
        }
    }

    /** Devuelve servicios agrupados por categoría */
    public function obtenerAgrupados(): array
    {
        $grupos = [];
        foreach ($this->obtenerTodos() as $s) {
            $grupos[$s['categoria'] ?? 'Manicure'][] = $s;
        }
        return $grupos;
    }

    public function buscarPorId(int $id): array|false
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM servicio WHERE id_servicio = ? LIMIT 1"
        );
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function crear(array $datos): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO servicio (nombre_servicio, descripcion, precio, id_administrador)
             VALUES (?, ?, ?, ?)"
        );
        return $stmt->execute([
            $datos['nombre_servicio'],
            $datos['descripcion'] ?? null,
            $datos['precio'],
            $datos['id_administrador'],
        ]);
    }

    public function editar(int $id, array $datos): bool
    {
        // Si no viene nombre_servicio, solo actualizar precio y descripción
        if (empty($datos['nombre_servicio'])) {
            $stmt = $this->db->prepare(
                "UPDATE servicio SET descripcion=?, precio=? WHERE id_servicio=?"
            );
            return $stmt->execute([
                $datos['descripcion'] ?? null,
                $datos['precio'],
                $id,
            ]);
        }

        $stmt = $this->db->prepare(
            "UPDATE servicio SET nombre_servicio=?, descripcion=?, precio=?
             WHERE id_servicio=?"
        );
        return $stmt->execute([
            $datos['nombre_servicio'],
            $datos['descripcion'] ?? null,
            $datos['precio'],
            $id,
        ]);
    }
    public function eliminar(int $id): bool
    {
        $stmt = $this->db->prepare(
            "DELETE FROM servicio WHERE id_servicio=?"
        );
        return $stmt->execute([$id]);
    }
}
?>
