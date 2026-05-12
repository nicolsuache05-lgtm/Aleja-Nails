<?php
// views/dashboard/usuario_editar.php
$titulo = 'Editar Usuario';
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';
?>
<main>
  <h1>Editar Usuario ✏️</h1>

  <div class="card" style="max-width:520px;">
    <h2>Datos del usuario</h2>
    <form action="index.php?action=guardarEdicion" method="POST">
      <input type="hidden" name="id" value="<?= $usuario['id'] ?>">

      <div class="form-row">
        <div class="form-group">
          <label>Nombre</label>
          <input type="text" name="nombre" required value=" ">
        </div>
        <div class="form-group">
          <label>Apellido</label>
          <input type="text" name="apellido" required value=" ">
        </div>
      </div>

      <div class="form-group">
        <label>Correo electrónico</label>
        <input type="email" name="correo" required value=" ">
      </div>

      <div class="form-group">
        <label>Teléfono</label>
        <input type="tel" name="telefono" value=" ">
      </div>

      <div class="form-group">
        <label>Rol</label>
        <select name="rol" required>
            <option value="">— Selecciona —</option>
          <option value="Cliente">Cliente</option>
          <option value="Admin">Admin</option>
          <option value="Empleado">Empleado</option>
        </select>
      </div>

      <div style="display:flex;gap:12px;margin-top:8px;">
        <button type="submit" class="btn btn-primary">Guardar cambios</button>
        <a href="index.php?action=adminPanel" class="btn btn-outline">Cancelar</a>
      </div>
    </form>
  </div>
</main>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
