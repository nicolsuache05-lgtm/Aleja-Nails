<?php
$titulo = 'Servicios';

require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';

$iconos = [
    'Manicure' => '💅',
    'Pedicure' => '👣',
    'Capilar'  => '💆🏽‍♀️',
    'Otros'    => '✨'
];

$servicios = $servicios ?? [];
$grupos = [];

foreach ($servicios as $s) {
    $categoria = $s['categoria'] ?? 'Otros';
    $grupos[$categoria][] = $s;
}
?>

<main>

    <h1 style="font-size:22px;font-weight:600;color:#c0375a;margin-bottom:1.5rem">
        💅 Servicios
    </h1>

    <?php if (empty($grupos)): ?>

        <div class="card">
            <p>No hay servicios registrados.</p>
        </div>

    <?php else: ?>

        <?php foreach ($grupos as $cat => $items): ?>
            <div class="card">
                <h2>
                    <?= htmlspecialchars(($iconos[$cat] ?? '✨') . ' ' . $cat) ?>
                </h2>

                <div class="tabla-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th style="width:160px">Precio (COP)</th>
                                <th style="width:180px">Acción</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($items as $s): ?>
                                <tr>
                                    <td>
                                        <?= htmlspecialchars($s['nombre_servicio'] ?? 'Sin nombre') ?>
                                    </td>

                                    <td>
                                        <form 
                                            id="form-editar-<?= htmlspecialchars($s['id_servicio'] ?? '') ?>"
                                            action="/Mi-proyecto-formativo/public/index.php?action=actualizarServicio" 
                                            method="POST"
                                        >
                                            <input 
                                                type="hidden" 
                                                name="id_servicio" 
                                                value="<?= htmlspecialchars($s['id_servicio'] ?? '') ?>"
                                            >

                                            <input 
                                                type="text" 
                                                name="descripcion"
                                                value="<?= htmlspecialchars($s['descripcion'] ?? '') ?>"
                                                style="width:100%;padding:6px 10px;border:1.5px solid #f4c0d1;
                                                       border-radius:8px;font-family:'Poppins',sans-serif;font-size:13px;
                                                       background:#fdf0f5;color:#4a2030"
                                            >
                                        </form>
                                    </td>

                                    <td>
                                        <input 
                                            form="form-editar-<?= htmlspecialchars($s['id_servicio'] ?? '') ?>"
                                            type="number" 
                                            name="precio"
                                            value="<?= htmlspecialchars($s['precio'] ?? 0) ?>"
                                            min="0" 
                                            step="500" 
                                            required
                                            style="width:130px;padding:6px 10px;border:1.5px solid #f4c0d1;
                                                   border-radius:8px;font-family:'Poppins',sans-serif;font-size:13px;
                                                   background:#fdf0f5;color:#4a2030;font-weight:600"
                                        >
                                    </td>

                                    <td style="display:flex;gap:6px;">
                                        <button 
                                            form="form-editar-<?= htmlspecialchars($s['id_servicio'] ?? '') ?>"
                                            type="submit" 
                                            class="btn btn-primary btn-sm"
                                        >
                                            Guardar
                                        </button>

                                        <form 
                                            action="/Mi-proyecto-formativo/public/index.php?action=eliminarServicio" 
                                            method="POST"
                                            onsubmit="return confirm('¿Seguro que deseas eliminar este servicio?');"
                                        >
                                            <input 
                                                type="hidden" 
                                                name="id_servicio" 
                                                value="<?= htmlspecialchars($s['id_servicio'] ?? '') ?>"
                                            >

                                            <button type="submit" class="btn btn-danger btn-sm">
                                                Eliminar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endforeach; ?>

    <?php endif; ?>

</main>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>