<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/UsuarioController.php';
require_once __DIR__ . '/../controllers/AdminUsuarioController.php';
require_once __DIR__ . '/../controllers/ChatbotController.php';

$action = $_GET['action'] ?? 'inicio';

switch ($action) {
    case 'login':
        (new AuthController())->login();
        exit;
    case 'procesarLogin':
        (new AuthController())->procesarLogin();
        exit;
    case 'registro':
        (new AuthController())->mostrarRegistro();
        exit;
    case 'procesarRegistro':
        (new AuthController())->procesarRegistro();
        exit;
    case 'logout':
        (new AuthController())->logout();
        exit;
    case 'dashboard':
        (new UsuarioController())->dashboard();
        exit;
    case 'agendarCita':
        (new UsuarioController())->agendarCita();
        exit;
    case 'misReservas':
        (new UsuarioController())->misReservas();
        exit;
    case 'cancelarReserva':
        (new UsuarioController())->cancelarReserva();
        exit;
    case 'pagar':
        (new UsuarioController())->pagar();
        exit;
    case 'chatbot':
        (new ChatbotController())->responder();
        exit;
    case 'adminPanel':
        (new AdminUsuarioController())->dashboard();
        exit;
    case 'listarClientes':
        (new AdminUsuarioController())->listarClientes();
        exit;
    case 'toggleCliente':
        (new AdminUsuarioController())->toggleCliente();
        exit;
    case 'listarReservas':
        (new AdminUsuarioController())->listarReservas();
        exit;
    case 'actualizarReserva':
        (new AdminUsuarioController())->actualizarReserva();
        exit;
    case 'listarServicios':
        (new AdminUsuarioController())->listarServicios();
        exit;
    case 'actualizarServicio':
        (new AdminUsuarioController())->actualizarServicio();
        exit;
    case 'eliminarServicio':
        (new AdminUsuarioController())->eliminarServicio();
        exit;
    case 'verPagos':
        (new AdminUsuarioController())->verPagos();
        exit;
    case 'inicio':
    default:
        // Continúa al HTML de abajo
        break;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>


    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Aleja-Nails — Sistema de Belleza</title>

    <link rel="icon" type="image/png" href="../img/favicon.png">

    <!-- TAILWIND -->
     <script src="https://cdn.tailwindcss.com"></script>
     <meta charset="UTF-8">

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;700&display=swap"
          rel="stylesheet">

    <style>

        body {
            font-family: 'Poppins', sans-serif;
        }

        .gradient-bg {
            background: linear-gradient(
                135deg,
                #ef86ba,
                #efb4d3,
                #f970bb
            );
        }

        .slide {
            display: none;
        }

        .slide.active {
            display: block;
        }

        .feature-card:hover {
            transform: translateY(-4px);
            transition: 0.3s;
        }

    </style>

</head>


<body class="bg-white text-gray-800">

<!-- NAVBAR -->

<nav class="bg-white shadow sticky top-0 z-50">

    <div class="max-w-7xl mx-auto px-4 flex justify-between h-16 items-center">

        <div class="flex items-center gap-3">

            <img src="../img/favicon.png" class="w-10 h-10 rounded-lg shadow-sm">

            <span class="font-bold text-pink-600 text-2xl tracking-tight">
                Aleja-Nails
            </span>

        </div>

        <div class="hidden md:flex gap-6">

            <a href="#inicio">Inicio</a>

            <a href="#nosotros">Nosotros</a>

            <a href="#servicios">Servicios</a>

        </div>

    </div>

</nav>

<!-- HERO -->

<section id="inicio" class="relative h-[550px] text-white">

    <div class="slide active h-full">

        <img src="../img/salon_hero.png"
             class="absolute w-full h-full object-cover"
             style="filter:brightness(0.5)">

        <div class="absolute inset-0 gradient-bg opacity-60"></div>

        <div class="relative flex flex-col justify-center items-center h-full text-center">

            <h1 class="text-5xl font-bold">
                Aleja-Nails 💅
            </h1>

            <p class="mt-4 text-lg">
                Belleza, estilo y elegancia en un solo lugar
            </p>

            <!-- BOTONES -->

            <div class="flex gap-4 mt-8">

                <!-- REGISTRO -->

                <a
                    href="?action=registro"
                    class="gradient-bg px-8 py-3 rounded-xl text-white font-semibold hover:scale-105 transition"
                >
                    Registrarse
                </a>

                <!-- LOGIN -->

                <a
                    href="?action=login"
                    class="gradient-bg px-8 py-3 rounded-xl text-white font-semibold hover:scale-105 transition"
                >
                    Iniciar Sesión
                </a>

            </div>

        </div>

    </div>

</section>

<!-- NOSOTROS -->

<section id="nosotros" class="py-20 text-center">

    <h2 class="text-3xl font-bold text-pink-600 mb-4">
        Sobre Nosotros
    </h2>

    <p class="max-w-xl mx-auto text-gray-600 mb-10">

        Somos un salón con estilo, elegancia y organización.
        Brindamos una experiencia única en el cuidado de tus uñas
        con servicios modernos y profesionales.

    </p>

    <div class="grid md:grid-cols-2 gap-8 max-w-4xl mx-auto">

        <div class="feature-card bg-pink-50 p-6 rounded-xl">

            <h3 class="text-xl font-bold text-pink-600">
                🎯 Misión
            </h3>

            <p>
                Ofrecer servicios de belleza de alta calidad
                que resalten la elegancia y estilo de cada cliente.
            </p>

        </div>

        <div class="feature-card bg-pink-50 p-6 rounded-xl">

            <h3 class="text-xl font-bold text-pink-600">
                💡 Visión
            </h3>

            <p>
                Ser un salón reconocido por innovación,
                calidad y atención personalizada.
            </p>

        </div>

    </div>

</section>

<!-- SERVICIOS -->

<section id="servicios" class="py-20 bg-pink-50">

    <h2 class="text-center text-3xl font-bold text-pink-600 mb-10">
        Servicios
    </h2>

    <div class="grid md:grid-cols-3 gap-6 max-w-6xl mx-auto px-4">

        <div class="bg-white p-6 rounded-xl shadow">

            <h3 class="font-bold text-pink-600">
                💅 Servicio de Manicure
            </h3>

            <p>
                Servicios manicure modernos, elegantes
                y personalizados.
            </p>

        </div>

        <div class="bg-white p-6 rounded-xl shadow">

            <h3 class="font-bold text-pink-600">
                👣 Servicio de Pedicure
            </h3>

            <p>
                Pedicure profesional con delicadeza,
                calidad y elegancia.
            </p>

        </div>

        <div class="bg-white p-6 rounded-xl shadow">

            <h3 class="font-bold text-pink-600">
                💆🏽‍♀️ Servicio de Capilar
            </h3>

            <p>
                Tratamientos capilares modernos
                para resaltar tu belleza.
            </p>

        </div>

    </div>

</section>

<!-- FOOTER -->

<footer class="text-center py-4 text-sm text-pink-400 bg-white border-t border-pink-100">

    © <?= date('Y') ?> Aleja-Nails · Salón de Belleza Profesional

</footer>

<?php
// Chatbot flotante en la página de inicio
require_once __DIR__ . '/../views/layouts/chatbot.php';
?>

<script>

    let slides = document.querySelectorAll('.slide');

    let current = 0;

    setInterval(() => {

        slides[current].classList.remove('active');

        current = (current + 1) % slides.length;

        slides[current].classList.add('active');

    }, 5000);

</script>

</body>
</html>