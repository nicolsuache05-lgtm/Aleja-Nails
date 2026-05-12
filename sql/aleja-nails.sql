-- ============================================================
-- BASE DE DATOS: aleja-nails  (estructura real del proyecto)
-- ============================================================

-- Agregar columna password a cliente si no existe
ALTER TABLE `cliente`
    ADD COLUMN IF NOT EXISTS `password` VARCHAR(255) NOT NULL DEFAULT '' AFTER `correo`,
    ADD COLUMN IF NOT EXISTS `activo`   TINYINT(1)   NOT NULL DEFAULT 1  AFTER `password`;

-- Datos de prueba: administrador  (contraseña: admin123)
INSERT IGNORE INTO `administrador` (nombre, usuario, contraseña) VALUES
('Alejandra Gómez', 'admin', 'admin123');

-- Datos de prueba: cliente  (password: cliente123)
-- El hash se genera con password_hash('cliente123', PASSWORD_DEFAULT)
INSERT IGNORE INTO `cliente` (nombre, telefono, correo, password) VALUES
('Laura Martínez', '3109876543', 'laura@correo.com',
 '$2y$10$.2m39CgZVeY.MRdJeAKF2e51eQ1BNidFDd5wGgQ.Y7kM9iO3nAoTq');

-- Datos de prueba: servicios
INSERT IGNORE INTO `servicio` (nombre_servicio, descripcion, precio, id_administrador) VALUES
('Manicure clásica',  'Limpieza, corte y esmaltado tradicional',           25000, 1),
('Pedicure spa',      'Exfoliación, hidratación y esmaltado de pies',      35000, 1),
('Uñas acrílicas',    'Extensión de uñas en acrílico con diseño incluido', 60000, 1),
('Semipermanente',    'Esmaltado de larga duración con lámpara UV',        45000, 1),
('Nail art diseño',   'Diseño artístico personalizado por uña',            15000, 1),
('Retiro acrílico',   'Retiro seguro de uñas acrílicas o gel',             20000, 1);
