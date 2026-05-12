<?php

require_once __DIR__ . '/../config/database.php';

class Usuario {

    private PDO $db;

    public function __construct()
    {
        $this->db = Database::conectar();
    }

    // ─── AUTENTICACIÓN ────────────────────────────────────────

    /** Busca un usuario por correo (para login) */
    public function buscarPorCorreo(string $correo): array|false
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM usuarios WHERE correo = ? LIMIT 1"
        );
        $stmt->execute([$correo]);
        return $stmt->fetch();
    }

    // ─── CRUD ─────────────────────────────────────────────────

    /**
     * Crea un nuevo usuario.
     * $datos debe tener: nombre, apellido, correo, telefono, password, rol
     */
    public function crear(array $datos): bool
    {
        try {
            $stmt = $this->db->prepare(
                "INSERT INTO usuarios (nombre, apellido, correo, telefono, password, rol, activo)
                 VALUES (?, ?, ?, ?, ?, ?, 1)"
            );

            return $stmt->execute([
                trim($datos['nombre']),
                trim($datos['apellido']   ?? ''),
                trim($datos['correo']),
                trim($datos['telefono']   ?? ''),
                password_hash($datos['password'], PASSWORD_DEFAULT),
                $datos['rol'] ?? 'cliente',
            ]);

        } catch (PDOException $e) {
            error_log("Usuario::crear — " . $e->getMessage());
            return false;
        }
    }

    /** Devuelve todos los usuarios ordenados por id desc */
    public function obtenerTodos(): array
    {
        return $this->db
            ->query("SELECT * FROM usuarios ORDER BY id DESC")
            ->fetchAll();
    }

    /** Busca un usuario por id */
    public function buscarPorId(int $id): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Edita datos de un usuario.
     * $datos: nombre, apellido, correo, telefono, rol, activo
     */
    public function editar(int $id, array $datos): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE usuarios
             SET nombre=?, apellido=?, correo=?, telefono=?, rol=?, activo=?
             WHERE id=?"
        );
        return $stmt->execute([
            $datos['nombre'],
            $datos['apellido']  ?? '',
            $datos['correo'],
            $datos['telefono']  ?? '',
            $datos['rol'],
            $datos['activo'],
            $id,
        ]);
    }

    /** Activa o desactiva un usuario (toggle) */
    public function cambiarEstado(int $id, int $activo): bool
    {
        $stmt = $this->db->prepare("UPDATE usuarios SET activo=? WHERE id=?");
        return $stmt->execute([$activo, $id]);
    }

    /**
     * Verifica si un correo ya existe.
     * Si se pasa $id se excluye ese registro (útil al editar).
     */
    public function correoExiste(string $correo, ?int $id = null): bool
    {
        if ($id) {
            $stmt = $this->db->prepare(
                "SELECT id FROM usuarios WHERE correo=? AND id != ? LIMIT 1"
            );
            $stmt->execute([$correo, $id]);
        } else {
            $stmt = $this->db->prepare(
                "SELECT id FROM usuarios WHERE correo=? LIMIT 1"
            );
            $stmt->execute([$correo]);
        }
        return $stmt->rowCount() > 0;
    }
}
?>
