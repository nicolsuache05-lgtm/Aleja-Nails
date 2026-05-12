<?php
require_once __DIR__ . '/../config/database.php';

/**
 * Modelo para la tabla `administrador`
 * Columnas: id_administrador, nombre, usuario, contraseña
 *
 * NOTA: la contraseña en esta tabla se guarda en texto plano
 * según la estructura original. Se recomienda migrar a hash
 * en el futuro, pero por ahora se respeta la BD existente.
 */
class Administrador {

    private PDO $db;

    public function __construct()
    {
        $this->db = Database::conectar();
    }

    /** Busca un admin por nombre de usuario o por correo */
    public function buscarPorUsuario(string $usuario): array|false
    {
        // Agregar columna correo si no existe
        try {
            $cols = $this->db->query("DESCRIBE administrador")->fetchAll(PDO::FETCH_COLUMN);
            if (!in_array('correo', $cols)) {
                $this->db->exec(
                    "ALTER TABLE administrador ADD COLUMN correo VARCHAR(100) DEFAULT NULL AFTER nombre"
                );
            }
        } catch (PDOException $e) {
            error_log("Administrador: " . $e->getMessage());
        }

        $stmt = $this->db->prepare(
            "SELECT * FROM administrador
             WHERE usuario = ? OR correo = ?
             LIMIT 1"
        );
        $stmt->execute([$usuario, $usuario]);
        return $stmt->fetch();
    }

    /** Todos los administradores */
    public function obtenerTodos(): array
    {
        return $this->db
            ->query("SELECT * FROM administrador ORDER BY id_administrador DESC")
            ->fetchAll();
    }

    /** Busca por id */
    public function buscarPorId(int $id): array|false
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM administrador WHERE id_administrador = ? LIMIT 1"
        );
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}
?>
