<?php

class Database {

    private static string $host   = "127.0.0.1";
    private static int    $port   = 3320;
    private static string $dbname = "aleja-nails";   // nombre exacto en HeidiSQL
    private static string $user   = "root";
    private static string $pass   = "";
    private static ?PDO   $conexion = null;

    /**
     * Devuelve la conexión PDO (singleton).
     * DSN correcto: host y port como parámetros separados.
     */
    public static function getConnection(): PDO
    {
        if (self::$conexion === null) {
            try {
                $dsn = sprintf(
                    "mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4",
                    self::$host,
                    self::$port,
                    self::$dbname
                );

                self::$conexion = new PDO($dsn, self::$user, self::$pass);

                self::$conexion->setAttribute(PDO::ATTR_ERRMODE,            PDO::ERRMODE_EXCEPTION);
                self::$conexion->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                self::$conexion->setAttribute(PDO::ATTR_EMULATE_PREPARES,   false);

            } catch (PDOException $e) {
                // En producción loguear el error, no mostrarlo
                error_log("DB Error: " . $e->getMessage());
                die(json_encode([
                    'error' => true,
                    'mensaje' => 'No se pudo conectar a la base de datos. Revisa la configuración.'
                ]));
            }
        }

        return self::$conexion;
    }

    /** Alias para compatibilidad con controladores existentes */
    public static function conectar(): PDO
    {
        return self::getConnection();
    }
}
?>
