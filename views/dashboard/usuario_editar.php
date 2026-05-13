<?php
// views/dashboard/usuario_editar.php

$titulo = 'Editar Usuario';

require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';

$usuario = $usuario ?? [];
?>

<main>

  <h1>Editar Usuario ✏️</h1>

  <div class="card" style="max-width:520px;">

    <h2>Datos del usuario</h2>

    <form action="index.php?action=guardarEdicion" method="POST">

      <input 
        type="hidden" 
        name="id" 
        value="<?= htmlspecialchars($usuario['id_cliente'] ?? '') ?>"
      >

      <div class="form-row">

        <div class="form-group">
          <label>Nombre</label>

          <input 
            type="text" 
            name="nombre" 
            required
            value="<?= htmlspecialchars($usuario['nombre'] ?? '') ?>"
          >
        </div>

        <div class="form-group">
          <label>Apellido</label>

          <input 
            type="text" 
            name="apellido" 
            required
            value="<?= htmlspecialchars($usuario['apellido'] ?? '') ?>"
          >
        </div>

      </div>

      <div class="form-group">
        <label>Correo electrónico</label>

        <input 
          type="email" 
          name="correo" 
          required
          value="<?= htmlspecialchars($usuario['correo'] ?? '') ?>"
        >
      </div>

      <div class="form-group">
        <label>Teléfono</label>

        <input 
          type="tel" 
          name="telefono"
          value="<?= htmlspecialchars($usuario['telefono'] ?? '') ?>"
        >
      </div>

      <div class="form-group">

        <label>Rol</label>

        <select name="rol" required>

          <option value="">— Selecciona —</option>

          <option 
            value="cliente"
            <?= (($usuario['rol'] ?? '') === 'cliente') ? 'selected' : '' ?>
          >
            Cliente
          </option>

          <option 
            value="admin"
            <?= (($usuario['rol'] ?? '') === 'admin') ? 'selected' : '' ?>
          >
            Admin
          </option>

          <option 
            value="empleado"
            <?= (($usuario['rol'] ?? '') === 'empleado') ? 'selected' : '' ?>
          >
            Empleado
          </option>

        </select>

      </div>

      <div style="display:flex;gap:12px;margin-top:8px;">

        <button type="submit" class="btn btn-primary">
          Guardar cambios
        </button>

        <a href="index.php?action=adminPanel" class="btn btn-outline">
          Cancelar
        </a>

      </div>

    </form>

  </div>

</main>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>