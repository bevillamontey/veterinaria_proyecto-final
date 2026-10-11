<?php
require_once 'includes/data.php';
require_rol(['Administrador','Recepcionista']);

$action = $_POST['action'] ?? $_GET['action'] ?? '';
$msg = '';

if ($action === 'save') {
    $id = (int)($_POST['id'] ?? 0);
    $datos = [
        trim($_POST['nombre']),
        trim($_POST['dni']),
        trim($_POST['telefono']),
        trim($_POST['correo']),
        trim($_POST['direccion']),
    ];
    try {
        if ($id > 0) {
            q_exec($pdo, "UPDATE propietarios SET nombre=?, dni=?, telefono=?, correo=?, direccion=? WHERE id_propietario=?",
                   array_merge($datos, [$id]));
            $msg = 'Propietario actualizado correctamente.';
        } else {
            q_exec($pdo, "INSERT INTO propietarios (nombre, dni, telefono, correo, direccion) VALUES (?,?,?,?,?)", $datos);
            $msg = 'Propietario registrado correctamente.';
        }
    } catch (PDOException $e) {
        $msg = 'Error: el DNI ya está registrado.';
    }
    $action = '';
}
if ($action === 'delete') {
    q_exec($pdo, "DELETE FROM propietarios WHERE id_propietario=?", [(int)$_GET['id']]);
    $msg = 'Propietario eliminado.';
    $action = '';
}

$edit  = ($action === 'edit') ? q_one($pdo, "SELECT * FROM propietarios WHERE id_propietario=?", [(int)$_GET['id']]) : null;
$lista = q_all($pdo, "SELECT * FROM propietarios ORDER BY id_propietario DESC");

require 'includes/header.php';
?>
<div class="page-title"><a class="back" href="dashboard.php">← Volver al panel</a><h1>Gestión de propietarios</h1><p>Registrar, consultar, editar y eliminar propietarios.</p></div>
<?php if ($msg): ?><p class="ok"><?= $msg ?></p><?php endif; ?>

<section class="section">
  <h2><?= $edit ? 'Editar propietario' : 'Nuevo propietario' ?></h2>
  <form class="form" method="post" action="propietarios.php">
    <input type="hidden" name="action" value="save">
    <input type="hidden" name="id" value="<?= $edit['id_propietario'] ?? 0 ?>">
    <label>Nombre</label><input name="nombre" required value="<?= htmlspecialchars($edit['nombre'] ?? '') ?>">
    <label>DNI</label><input name="dni" required value="<?= htmlspecialchars($edit['dni'] ?? '') ?>">
    <label>Teléfono</label><input name="telefono" value="<?= htmlspecialchars($edit['telefono'] ?? '') ?>">
    <label>Correo</label><input type="email" name="correo" value="<?= htmlspecialchars($edit['correo'] ?? '') ?>">
    <label>Dirección</label><input name="direccion" value="<?= htmlspecialchars($edit['direccion'] ?? '') ?>">
    <button type="submit"><?= $edit ? 'Actualizar' : 'Registrar' ?></button>
    <?php if ($edit): ?><a class="btn secondary" href="propietarios.php">Cancelar</a><?php endif; ?>
  </form>
</section>

<section class="section">
  <h2>Listado</h2>
  <div class="table-box"><table>
    <tr><th>ID</th><th>Nombre</th><th>DNI</th><th>Teléfono</th><th>Correo</th><th>Acciones</th></tr>
    <?php foreach ($lista as $p): ?>
      <tr>
        <td><?= $p['id_propietario'] ?></td>
        <td><?= htmlspecialchars($p['nombre']) ?></td>
        <td><?= htmlspecialchars($p['dni']) ?></td>
        <td><?= htmlspecialchars($p['telefono']) ?></td>
        <td><?= htmlspecialchars($p['correo']) ?></td>
        <td>
          <a class="btn small" href="propietarios.php?action=edit&id=<?= $p['id_propietario'] ?>">Editar</a>
          <a class="btn small danger" href="propietarios.php?action=delete&id=<?= $p['id_propietario'] ?>" onclick="return confirm('¿Eliminar?')">Eliminar</a>
        </td>
      </tr>
    <?php endforeach; ?>
  </table></div>
</section>
<?php require 'includes/footer.php'; ?>