<?php
require_once __DIR__ . '/../config/database.php';

/**
 * Modelo para la tabla `cliente`
 * Columnas: id_cliente, nombre, telefono, correo, password, activo
 */
class Cliente {

    private PDO $db;

    public function __construct()
    {
        $this->db = Database::conectar();
    }

    // ── LOGIN ────────────────────────────────────────────────

    /** Busca un cliente por correo */
    public function buscarPorCorreo(string $correo): array|false
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM cliente WHERE correo = ? LIMIT 1"
        );
        $stmt->execute([$correo]);
        $row = $stmt->fetch();
        // Si la columna activo no existe, asumir activo=1
        if ($row && !isset($row['activo'])) {
            $row['activo'] = 1;
        }
        return $row;
    }

    // ── CRUD ─────────────────────────────────────────────────

    /**
     * Registra un nuevo cliente.
     * $datos: nombre, telefono, correo, password
     */
    public function crear(array $datos): bool
    {
        try {
            // Detectar si la columna 'password' ya existe en la tabla
            $cols = $this->db->query("DESCRIBE cliente")->fetchAll(PDO::FETCH_COLUMN);

            // Si no existe la columna password, agregarla automáticamente
            if (!in_array('password', $cols)) {
                $this->db->exec("ALTER TABLE cliente ADD COLUMN password VARCHAR(255) NOT NULL DEFAULT ''");
            }
            if (!in_array('activo', $cols)) {
                $this->db->exec("ALTER TABLE cliente ADD COLUMN activo TINYINT(1) NOT NULL DEFAULT 1");
            }

            $stmt = $this->db->prepare(
                "INSERT INTO cliente (nombre, telefono, correo, password)
                 VALUES (?, ?, ?, ?)"
            );
            return $stmt->execute([
                trim($datos['nombre']),
                trim($datos['telefono'] ?? ''),
                trim($datos['correo']),
                password_hash($datos['password'], PASSWORD_DEFAULT),
            ]);
        } catch (PDOException $e) {
            error_log("Cliente::crear — " . $e->getMessage());
            // Guardar el error real en sesión para mostrarlo
            $_SESSION['flash_error'] = "Error BD: " . $e->getMessage();
            return false;
        }
    }

    /** Todos los clientes */
    public function obtenerTodos(): array
    {
        return $this->db
            ->query("SELECT * FROM cliente ORDER BY id_cliente DESC")
            ->fetchAll();
    }

    /** Busca por id */
    public function buscarPorId(int $id): array|false
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM cliente WHERE id_cliente = ? LIMIT 1"
        );
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /** Edita un cliente */
    public function editar(int $id, array $datos): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE cliente SET nombre=?, telefono=?, correo=? WHERE id_cliente=?"
        );
        return $stmt->execute([
            $datos['nombre'],
            $datos['telefono'] ?? '',
            $datos['correo'],
            $id,
        ]);
    }

    /** Activa / desactiva (solo si la columna existe) */
    public function cambiarEstado(int $id, int $activo): bool
    {
        try {
            $stmt = $this->db->prepare(
                "UPDATE cliente SET activo=? WHERE id_cliente=?"
            );
            return $stmt->execute([$activo, $id]);
        } catch (PDOException $e) {
            error_log("Cliente::cambiarEstado — " . $e->getMessage());
            return false;
        }
    }

    /** Verifica si el correo ya existe (excluye $id al editar) */
    public function correoExiste(string $correo, ?int $id = null): bool
    {
        if ($id) {
            $stmt = $this->db->prepare(
                "SELECT id_cliente FROM cliente WHERE correo=? AND id_cliente != ? LIMIT 1"
            );
            $stmt->execute([$correo, $id]);
        } else {
            $stmt = $this->db->prepare(
                "SELECT id_cliente FROM cliente WHERE correo=? LIMIT 1"
            );
            $stmt->execute([$correo]);
        }
        return $stmt->rowCount() > 0;
    }
}
?>
