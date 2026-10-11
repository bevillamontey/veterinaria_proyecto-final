<?php
require_once 'includes/data.php';
require_rol('Administrador');

$action = $_POST['action'] ?? $_GET['action'] ?? '';
$msg = '';

if ($action === 'save') {
    $id     = (int)($_POST['id'] ?? 0);
    $nombre = trim($_POST['nombre']);
    $correo = trim($_POST['correo']);
    $rol    = (int)$_POST['id_rol'];
    $pass   = $_POST['contrasena'] ?? '';

    try {
        if ($id > 0) {
            if ($pass !== '') {
                q_exec($pdo, "UPDATE usuarios SET nombre=?, correo=?, contrasena=?, id_rol=? WHERE id_usuario=?",
                       [$nombre, $correo, $pass, $rol, $id]);
            } else {
                q_exec($pdo, "UPDATE usuarios SET nombre=?, correo=?, id_rol=? WHERE id_usuario=?",
                       [$nombre, $correo, $rol, $id]);
            }
            $msg = 'Usuario actualizado.';
        } else {
            q_exec($pdo, "INSERT INTO usuarios (nombre, correo, contrasena, id_rol) VALUES (?,?,?,?)",
                   [$nombre, $correo, $pass, $rol]);
            $msg = 'Usuario registrado.';
        }
    } catch (PDOException $e) {
        $msg = 'Error: el correo ya existe o los datos son inválidos.';
    }
    $action = '';
}
if ($action === 'delete') {
    if ((int)$_GET['id'] === (int)$_SESSION['id_usuario']) {
        $msg = 'No puedes eliminar tu propio usuario.';
    } else {
        q_exec($pdo, "DELETE FROM usuarios WHERE id_usuario=?", [(int)$_GET['id']]);
        $msg = 'Usuario eliminado.';
    }
    $action = '';
}

$edit  = ($action === 'edit') ? q_one($pdo, "SELECT * FROM usuarios WHERE id_usuario=?", [(int)$_GET['id']]) : null;
$roles = q_all($pdo, "SELECT * FROM roles ORDER BY id_rol");
$lista = q_all($pdo, "SELECT u.*, r.nombre AS rol FROM usuarios u JOIN roles r ON u.id_rol=r.id_rol ORDER BY u.id_usuario");

require 'includes/header.php';
?>
<div class="page-title"><a class="back" href="dashboard.php">← Volver al panel</a><h1>Gestión de usuarios</h1><p>Cuentas de usuarios y roles del sistema.</p></div>
<?php if ($msg): ?><p class="ok"><?= $msg ?></p><?php endif; ?>

<section class="section">
  <h2><?= $edit ? 'Editar usuario' : 'Nuevo usuario' ?></h2>
  <form class="form" method="post" action="usuarios.php">
    <input type="hidden" name="action" value="save">
    <input type="hidden" name="id" value="<?= $edit['id_usuario'] ?? 0 ?>">
    <label>Nombre</label><input name="nombre" required value="<?= htmlspecialchars($edit['nombre'] ?? '') ?>">
    <label>Correo</label><input type="email" name="correo" required value="<?= htmlspecialchars($edit['correo'] ?? '') ?>">
    <label>Contraseña <?= $edit ? '(dejar vacío para no cambiar)' : '' ?></label>
    <input type="password" name="contrasena" <?= $edit ? '' : 'required' ?>>
    <label>Rol</label>
    <select name="id_rol" required>
      <?php foreach ($roles as $r): ?>
        <option value="<?= $r['id_rol'] ?>" <?= (($edit['id_rol'] ?? 0)==$r['id_rol'])?'selected':'' ?>><?= $r['nombre'] ?></option>
      <?php endforeach; ?>
    </select>
    <button type="submit"><?= $edit ? 'Actualizar' : 'Registrar' ?></button>
    <?php if ($edit): ?><a class="btn secondary" href="usuarios.php">Cancelar</a><?php endif; ?>
  </form>
</section>

<section class="section">
  <h2>Listado</h2>
  <div class="table-box"><table>
    <tr><th>ID</th><th>Nombre</th><th>Correo</th><th>Rol</th><th>Acciones</th></tr>
    <?php foreach ($lista as $u): ?>
      <tr>
        <td><?= $u['id_usuario'] ?></td>
        <td><?= htmlspecialchars($u['nombre']) ?></td>
        <td><?= htmlspecialchars($u['correo']) ?></td>
        <td><?= htmlspecialchars($u['rol']) ?></td>
        <td>
          <a class="btn small" href="usuarios.php?action=edit&id=<?= $u['id_usuario'] ?>">Editar</a>
          <a class="btn small danger" href="usuarios.php?action=delete&id=<?= $u['id_usuario'] ?>" onclick="return confirm('¿Eliminar?')">Eliminar</a>
        </td>
      </tr>
    <?php endforeach; ?>
  </table></div>
</section>
<?php require 'includes/footer.php'; ?>