<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Aleja-Nails — Sistema de Belleza</title>
<link rel="icon" type="image/png" href="img/ico.png">

<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;700&display=swap" rel="stylesheet">

<style>
body { font-family: 'Poppins', sans-serif; }

/* 🎨 GRADIENTE ROSADO */
.gradient-bg {
    background: linear-gradient(135deg, #ef86baff, #efb4d3ff, #f970bbff);
}

/* SLIDER */
.slide { display:none; }
.slide.active { display:block; }

/* HOVER */
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

<div class="flex items-center gap-2">
    <div class="w-8 h-8 gradient-bg rounded-xl flex items-center justify-center">
        💅
    </div>
    <span class="font-bold text-pink-600">Aleja-Nails</span>
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
    <img src="img/uñas.jpg" class="absolute w-full h-full object-cover" style="filter:brightness(0.4)">
    
    <div class="absolute inset-0 gradient-bg opacity-60"></div>

    <div class="relative flex flex-col justify-center items-center h-full text-center">
        <h1 class="text-5xl font-bold">Aleja-Nails 💅</h1>
        <p class="mt-4 text-lg">Belleza, estilo y elegancia en un solo lugar</p>

        <a href="public/index.php?action=registro" class="mt-6 gradient-bg px-8 py-3 rounded-xl">
            Registrarse
        </a>
        <a href="public/index.php?action=login" class="mt-6 gradient-bg px-8 py-3 rounded-xl">
            Iniciar Sesión
        </a>
    </div>
</div>

</section>

<!-- NOSOTROS -->
<section id="nosotros" class="py-20 text-center">

<h2 class="text-3xl font-bold text-pink-600 mb-4">Sobre Nosotros</h2>
<p class="max-w-xl mx-auto text-gray-600 mb-10">
Somos un salón con estilo, elegancia y organización,
Brindamos una experiencia única en el cuidado de tus uñas con servicios modernos y profesionales.
</p>

<div class="grid md:grid-cols-2 gap-8 max-w-4xl mx-auto">

<div class="feature-card bg-pink-50 p-6 rounded-xl">
<h3 class="text-xl font-bold text-pink-600">🎯 Misión</h3>
<p>Ofrecer servicios de belleza de alta calidad que resalten la elegancia y estilo de cada cliente.</p>
</div>

<div class="feature-card bg-pink-50 p-6 rounded-xl">
<h3 class="text-xl font-bold text-pink-600">💡 Visión</h3>
<p>Ser un salón reconocido por innovación, calidad y atención personalizada en cada servicio.</p>
</div>

</div>

</section>

<!-- SERVICIOS -->
<section id="servicios" class="py-20 bg-pink-50">

<h2 class="text-center text-3xl font-bold text-pink-600 mb-10">Servicios</h2>

<div class="grid md:grid-cols-3 gap-6 max-w-6xl mx-auto px-4">

<div class="bg-white p-6 rounded-xl shadow">
<h3 class="font-bold text-pink-600">💅 Servicio de Manicure</h3>
<p>Ofrecemos servicios manicure de alta calidad que resalten la elegancia y estilo de cada cliente.</p>
</div>

<div class="bg-white p-6 rounded-xl shadow">
<h3 class="font-bold text-pink-600">👣 Servicio de Pedicure</h3>
<p>Brindamos servicios pedicure de calidad, delicadeza y elegancia personalizada en cada servicio.</p>
</div>

<div class="bg-white p-6 rounded-xl shadow">
<h3 class="font-bold text-pink-600">💆🏽‍♀️ Servicio de Capilar</h3>
<p>Ofrecemos servicios capilares que realcen la elegancia, el brillo y salud de cada cabello.</p>
</div>

</div>

</section>


<!-- FOOTER -->
<footer style="text-align:center;padding:1rem;font-size:12px;color:#b07090;background:white;border-top:1px solid #fce4ef;">
  © <?= date('Y') ?> Aleja-Nails · Salón de Belleza Profesional
</footer>

<script>
let slides = document.querySelectorAll('.slide');
let current = 0;

setInterval(()=>{
    slides[current].classList.remove('active');
    current = (current + 1) % slides.length;
    slides[current].classList.add('active');
},5000);
</script>

</body>
</html>