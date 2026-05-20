<?php
/**
 * ChatbotController — respuestas automáticas para Aleja-Nails
 * Endpoint: index.php?action=chatbot  (POST JSON)
 */
require_once __DIR__ . '/../config/database.php';

class ChatbotController
{
    private PDO $db;

    // ── Datos de contacto del salón ──────────────────────────
    private const TEL_DISPLAY  = '304 408 5465';
    private const TEL_WA       = '57 3044085465';   // formato internacional sin +
    private const NEQUI        = '304 408 5465';
    private const DAVIPLATA    = '304 408 5465';
    private const TITULAR      = 'Alejandra Vanegas';

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $this->db = Database::conectar();
    }

    public function responder(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $body    = file_get_contents('php://input');
        $data    = json_decode($body, true);
        $mensaje = trim(strtolower($data['mensaje'] ?? ''));

        if ($mensaje === '') {
            echo json_encode(['respuesta' => 'Escribe algo para que pueda ayudarte 😊']);
            exit;
        }

        echo json_encode(['respuesta' => $this->procesar($mensaje)]);
        exit;
    }

    // ── Motor de respuestas ──────────────────────────────────

    private function procesar(string $msg): string
    {
        // ── Saludos ──────────────────────────────────────────
        if ($this->contiene($msg, ['hola','buenas','buenos','buen dia','buenas tardes','buenas noches','hey','hi','saludos'])) {
            $nombre = $_SESSION['usuario_nombre'] ?? $_SESSION['nombre'] ?? '';
            $saludo = $nombre ? "¡Hola, {$nombre}! 💅" : "¡Hola! 💅";
            return "{$saludo} Bienvenida a <b>Aleja-Nails</b>. ¿En qué te puedo ayudar?\n\n"
                 . "Puedes preguntarme sobre:\n"
                 . "• 💅 Servicios y precios\n"
                 . "• 📅 Cómo agendar una cita\n"
                 . "• 🕐 Horarios de atención\n"
                 . "• 💳 Métodos de pago\n"
                 . "• 📍 Ubicación y contacto";
        }

        // ── Despedidas ───────────────────────────────────────
        if ($this->contiene($msg, ['adios','chao','hasta luego','bye','nos vemos','gracias','thank'])) {
            return "¡Hasta pronto! 💖 Fue un placer atenderte.\n\n"
                 . "Recuerda que puedes contactarnos en cualquier momento:\n"
                 . "📱 <a href='https://wa.me/" . self::TEL_WA . "' target='_blank' "
                 . "style='color:#e8527a;font-weight:600'>WhatsApp: " . self::TEL_DISPLAY . "</a>";
        }

        // ── Teléfono / Contacto ──────────────────────────────
        if ($this->contiene($msg, ['telefono','teléfono','numero','número','contacto','whatsapp','llamar','comunicar','escribir','celular'])) {
            return "📞 <b>Contáctanos aquí:</b>\n\n"
                 . "📱 <b>WhatsApp / Llamadas:</b>\n"
                 . "<a href='https://wa.me/" . self::TEL_WA . "' target='_blank' "
                 . "style='color:#e8527a;font-weight:600;font-size:15px'>"
                 . "💬 " . self::TEL_DISPLAY . "</a>\n\n"
                 . "🕐 Atención: Lun–Vie 8am–7pm · Sáb 8am–6pm · Dom 9am–3pm\n\n"
                 . "¡Responderemos lo más pronto posible! 💖";
        }

        // ── Servicios (lista general) ────────────────────────
        if ($this->contiene($msg, ['servicio','servicios','que ofrecen','que hacen','que tienen','catalogo','catálogo','menu','menú'])) {
            return $this->listarServicios();
        }

        // ── Manicure ─────────────────────────────────────────
        if ($this->contiene($msg, ['manicure','mani','uñas manos','unas manos','esmaltado','esmalte','acrilicas','acrílicas','gel manos'])) {
            return $this->serviciosPorCategoria('Manicure');
        }

        // ── Pedicure ─────────────────────────────────────────
        if ($this->contiene($msg, ['pedicure','pedi','uñas pies','unas pies','pies','gel pies'])) {
            return $this->serviciosPorCategoria('Pedicure');
        }

        // ── Capilar ──────────────────────────────────────────
        if ($this->contiene($msg, ['capilar','cabello','pelo','tinte','keratina','corte','hidratacion','hidratación','alisado','brushing'])) {
            return $this->serviciosPorCategoria('Capilar');
        }

        // ── Precios ──────────────────────────────────────────
        if ($this->contiene($msg, ['precio','precios','cuanto','cuánto','costo','costos','valor','valores','tarifa','tarifas','cuanto cuesta','cuánto cuesta'])) {
            return $this->listarServicios();
        }

        // ── Agendar / Reservar ───────────────────────────────
        if ($this->contiene($msg, ['agendar','reservar','cita','turno','appointment','como reservo','cómo reservo','quiero una cita','quiero reservar'])) {
            $link = isset($_SESSION['usuario_id'])
                ? '<a href="/Mi-proyecto-formativo/public/index.php?action=agendarCita" style="color:#e8527a;font-weight:600">👉 Agendar mi cita</a>'
                : '<a href="/Mi-proyecto-formativo/public/index.php?action=login" style="color:#e8527a;font-weight:600">👉 Iniciar sesión para agendar</a>';
            return "Para agendar tu cita sigue estos pasos:\n\n"
                 . "1️⃣ Inicia sesión o regístrate\n"
                 . "2️⃣ Ve a <b>Agendar cita</b>\n"
                 . "3️⃣ Selecciona uno o varios servicios\n"
                 . "4️⃣ Elige fecha y hora disponible\n"
                 . "5️⃣ Confirma y realiza tu pago\n\n"
                 . $link . "\n\n"
                 . "¿Prefieres agendar por WhatsApp?\n"
                 . "<a href='https://wa.me/" . self::TEL_WA . "?text=Hola,%20quiero%20agendar%20una%20cita' "
                 . "target='_blank' style='color:#e8527a;font-weight:600'>📱 " . self::TEL_DISPLAY . "</a>";
        }

        // ── Mis reservas ─────────────────────────────────────
        if ($this->contiene($msg, ['mis reservas','mis citas','ver reservas','ver citas','reservas','historial'])) {
            if (isset($_SESSION['usuario_id'])) {
                return "Puedes ver todas tus citas aquí:\n"
                     . '<a href="/Mi-proyecto-formativo/public/index.php?action=misReservas" style="color:#e8527a;font-weight:600">📅 Ver mis reservas</a>';
            }
            return "Debes iniciar sesión para ver tus reservas.\n"
                 . '<a href="/Mi-proyecto-formativo/public/index.php?action=login" style="color:#e8527a;font-weight:600">🔐 Iniciar sesión</a>';
        }

        // ── Cancelar reserva ─────────────────────────────────
        if ($this->contiene($msg, ['cancelar','cancelar cita','cancelar reserva','anular'])) {
            return "Para cancelar una cita:\n\n"
                 . "1️⃣ Ve a <b>Mis Reservas</b>\n"
                 . "2️⃣ Busca la cita que deseas cancelar\n"
                 . "3️⃣ Haz clic en el botón <b>Cancelar</b>\n\n"
                 . "⚠️ Solo puedes cancelar citas en estado <b>Pendiente</b>.\n\n"
                 . "¿Necesitas ayuda? Escríbenos:\n"
                 . "<a href='https://wa.me/" . self::TEL_WA . "' target='_blank' "
                 . "style='color:#e8527a;font-weight:600'>📱 " . self::TEL_DISPLAY . "</a>";
        }

        // ── Pago / Transferencia ─────────────────────────────
        if ($this->contiene($msg, ['pago','pagar','metodo de pago','método de pago','como pago','cómo pago','efectivo','transferencia','tarjeta','nequi','daviplata','pse'])) {
            return "💳 <b>Métodos de pago aceptados:</b>\n\n"
                 . "💵 <b>Efectivo</b>\n"
                 . "  Paga directamente en el salón\n\n"
                 . "🏦 <b>Transferencia / Nequi / Daviplata</b>\n"
                 . "  Titular: <b>" . self::TITULAR . "</b>\n"
                 . "  Nequi: <b>" . self::NEQUI . "</b>\n"
                 . "  Daviplata: <b>" . self::DAVIPLATA . "</b>\n"
                 . "  <small style='color:#8a5068'>Envía el comprobante por WhatsApp</small>\n\n"
                 . "� <b>Comprobante de pago:</b>\n"
                 . "<a href='https://wa.me/" . self::TEL_WA . "?text=Hola,%20adjunto%20mi%20comprobante%20de%20pago' "
                 . "target='_blank' style='color:#e8527a;font-weight:600'>Enviar a " . self::TEL_DISPLAY . "</a>";
        }

        // ── Horarios ─────────────────────────────────────────
        if ($this->contiene($msg, ['horario','horarios','hora','horas','atienden','abren','cierran','cuando abren','cuando atienden','disponibilidad'])) {
            return "🕐 <b>Horarios de atención:</b>\n\n"
                 . "• Lunes a Viernes: <b>8:00 am – 7:00 pm</b>\n"
                 . "• Sábados: <b>8:00 am – 6:00 pm</b>\n"
                 . "• Domingos: <b>9:00 am – 3:00 pm</b>\n\n"
                 . "Puedes agendar citas en intervalos de 30 minutos.\n\n"
                 . "📱 ¿Tienes dudas? Escríbenos:\n"
                 . "<a href='https://wa.me/" . self::TEL_WA . "' target='_blank' "
                 . "style='color:#e8527a;font-weight:600'>" . self::TEL_DISPLAY . "</a>";
        }

        // ── Ubicación ────────────────────────────────────────
        if ($this->contiene($msg, ['ubicacion','ubicación','donde','dónde','direccion','dirección','como llegar','cómo llegar','lugar','local'])) {
            return "📍 <b>Encuéntranos aquí:</b>\n\n"
                 . "Aleja-Nails Salón de Belleza\n"
                 . "Calle Principal #123, Local 4\n"
                 . "Barrio Centro\n\n"
                 . "📞 <b>Teléfono / WhatsApp:</b>\n"
                 . "<a href='https://wa.me/" . self::TEL_WA . "' target='_blank' "
                 . "style='color:#e8527a;font-weight:600;font-size:15px'>"
                 . "📱 " . self::TEL_DISPLAY . "</a>\n\n"
                 . "🕐 Lun–Vie 8am–7pm · Sáb 8am–6pm · Dom 9am–3pm";
        }

        // ── Registro ─────────────────────────────────────────
        if ($this->contiene($msg, ['registrar','registro','crear cuenta','nueva cuenta','sign up','inscribir'])) {
            return "Para registrarte es muy fácil:\n\n"
                 . "1️⃣ Haz clic en <b>Registrarse</b>\n"
                 . "2️⃣ Completa tus datos (nombre, teléfono, correo)\n"
                 . "3️⃣ Crea tu contraseña\n"
                 . "4️⃣ ¡Listo! Ya puedes agendar citas\n\n"
                 . '<a href="/Mi-proyecto-formativo/public/index.php?action=registro" style="color:#e8527a;font-weight:600">✨ Registrarme ahora</a>';
        }

        // ── Login ────────────────────────────────────────────
        if ($this->contiene($msg, ['login','iniciar sesion','iniciar sesión','entrar','ingresar','acceder','contraseña','password'])) {
            return "Para iniciar sesión:\n\n"
                 . "1️⃣ Haz clic en <b>Iniciar Sesión</b>\n"
                 . "2️⃣ Ingresa tu correo y contraseña\n"
                 . "3️⃣ ¡Accede a tu cuenta!\n\n"
                 . '<a href="/Mi-proyecto-formativo/public/index.php?action=login" style="color:#e8527a;font-weight:600">🔐 Ir al login</a>';
        }

        // ── Ayuda general ────────────────────────────────────
        if ($this->contiene($msg, ['ayuda','help','no entiendo','no se','no sé','que puedes','qué puedes','opciones'])) {
            return "Puedo ayudarte con:\n\n"
                 . "💅 <b>Servicios</b> — escribe \"servicios\"\n"
                 . "💰 <b>Precios</b> — escribe \"precios\"\n"
                 . "📅 <b>Agendar</b> — escribe \"agendar cita\"\n"
                 . "🕐 <b>Horarios</b> — escribe \"horarios\"\n"
                 . "💳 <b>Pagos</b> — escribe \"métodos de pago\"\n"
                 . "📍 <b>Ubicación</b> — escribe \"dónde están\"\n"
                 . "📞 <b>Contacto</b> — escribe \"teléfono\"\n"
                 . "📋 <b>Mis reservas</b> — escribe \"mis reservas\"";
        }

        // ── Respuesta por defecto ────────────────────────────
        return "No entendí bien tu pregunta 🤔\n\n"
             . "Puedes preguntarme sobre servicios, precios, horarios, pagos o ubicación.\n\n"
             . "O contáctanos directamente:\n"
             . "<a href='https://wa.me/" . self::TEL_WA . "' target='_blank' "
             . "style='color:#e8527a;font-weight:600'>📱 WhatsApp: " . self::TEL_DISPLAY . "</a>";
    }

    // ── Helpers ──────────────────────────────────────────────

    private function contiene(string $msg, array $palabras): bool
    {
        foreach ($palabras as $p) {
            if (str_contains($msg, $p)) return true;
        }
        return false;
    }

    private function listarServicios(): string
    {
        try {
            $stmt = $this->db->query(
                "SELECT nombre_servicio, precio,
                        COALESCE(categoria, 'General') AS categoria
                 FROM servicio
                 ORDER BY categoria, precio ASC"
            );
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($rows)) {
                return "Actualmente estamos actualizando nuestro catálogo.\n\n"
                     . "Contáctanos para más información:\n"
                     . "<a href='https://wa.me/" . self::TEL_WA . "' target='_blank' "
                     . "style='color:#e8527a;font-weight:600'>📱 " . self::TEL_DISPLAY . "</a>";
            }

            $grupos = [];
            foreach ($rows as $r) {
                $grupos[$r['categoria']][] = $r;
            }

            $iconos = ['Manicure'=>'💅','Pedicure'=>'👣','Capilar'=>'💆🏽‍♀️','General'=>'✨'];
            $txt = "✨ <b>Nuestros servicios y precios:</b>\n\n";

            foreach ($grupos as $cat => $items) {
                $ico  = $iconos[$cat] ?? '✨';
                $txt .= "{$ico} <b>{$cat}</b>\n";
                foreach ($items as $s) {
                    $precio = number_format((float)$s['precio'], 0, ',', '.');
                    $txt   .= "  • {$s['nombre_servicio']} — <b>\${$precio}</b>\n";
                }
                $txt .= "\n";
            }

            $txt .= "¿Quieres agendar? Escribe <b>agendar cita</b> 📅\n\n"
                  . "¿Tienes dudas? Escríbenos:\n"
                  . "<a href='https://wa.me/" . self::TEL_WA . "' target='_blank' "
                  . "style='color:#e8527a;font-weight:600'>📱 " . self::TEL_DISPLAY . "</a>";
            return $txt;

        } catch (PDOException $e) {
            return "No pude cargar los servicios en este momento. Intenta de nuevo.";
        }
    }

    private function serviciosPorCategoria(string $categoria): string
    {
        try {
            $stmt = $this->db->prepare(
                "SELECT nombre_servicio, descripcion, precio
                 FROM servicio
                 WHERE LOWER(categoria) = LOWER(?)
                 ORDER BY precio ASC"
            );
            $stmt->execute([$categoria]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($rows)) {
                return "No encontré servicios de {$categoria} en este momento.\n"
                     . "Escribe <b>servicios</b> para ver todo el catálogo.";
            }

            $iconos = ['Manicure'=>'💅','Pedicure'=>'👣','Capilar'=>'💆🏽‍♀️'];
            $ico    = $iconos[$categoria] ?? '✨';

            $txt = "{$ico} <b>Servicios de {$categoria}:</b>\n\n";
            foreach ($rows as $s) {
                $precio = number_format((float)$s['precio'], 0, ',', '.');
                $desc   = $s['descripcion'] ? " — {$s['descripcion']}" : '';
                $txt   .= "• <b>{$s['nombre_servicio']}</b>{$desc}\n";
                $txt   .= "  Precio: <b>\${$precio}</b>\n\n";
            }

            $txt .= "¿Quieres agendar? Escribe <b>agendar cita</b> 📅\n\n"
                  . "¿Consultas? Escríbenos:\n"
                  . "<a href='https://wa.me/" . self::TEL_WA . "' target='_blank' "
                  . "style='color:#e8527a;font-weight:600'>📱 " . self::TEL_DISPLAY . "</a>";
            return $txt;

        } catch (PDOException $e) {
            return "No pude cargar los servicios en este momento.";
        }
    }
}
?>
