<?php
require_once 'includes/data.php';
require_rol(['Administrador','Recepcionista']);

$action = $_POST['action'] ?? $_GET['action'] ?? '';
$msg = '';

if ($action === 'save') {
    $id = (int)($_POST['id'] ?? 0);
    $datos = [
        (int)$_POST['id_mascota'],
        $_POST['fecha'],
        $_POST['hora'],
        $_POST['estado'],
    ];
    if ($id > 0) {
        q_exec($pdo, "UPDATE citas SET id_mascota=?, fecha=?, hora=?, estado=? WHERE id_cita=?",
               array_merge($datos, [$id]));
        $msg = 'Cita actualizada.';
    } else {
        q_exec($pdo, "INSERT INTO citas (id_mascota, fecha, hora, estado) VALUES (?,?,?,?)", $datos);
        $msg = 'Cita registrada.';
    }
    $action = '';
}
if ($action === 'estado') {
    q_exec($pdo, "UPDATE citas SET estado=? WHERE id_cita=?", [$_GET['nuevo'], (int)$_GET['id']]);
    $msg = 'Estado actualizado.';
    $action = '';
}
if ($action === 'delete') {
    q_exec($pdo, "DELETE FROM citas WHERE id_cita=?", [(int)$_GET['id']]);
    $msg = 'Cita eliminada.';
    $action = '';
}

$edit     = ($action === 'edit') ? q_one($pdo, "SELECT * FROM citas WHERE id_cita=?", [(int)$_GET['id']]) : null;
$mascotas = q_all($pdo, "SELECT id_mascota, nombre FROM mascotas ORDER BY nombre");
$lista    = q_all($pdo, "SELECT c.*, m.nombre AS mascota FROM citas c 
                        JOIN mascotas m ON c.id_mascota=m.id_mascota 
                        ORDER BY c.fecha DESC, c.hora DESC");

require 'includes/header.php';
?>
<div class="page-title"><a class="back" href="dashboard.php">← Volver al panel</a><h1>Gestión de citas</h1><p>Registro, consulta y actualización del estado de las citas.</p></div>
<?php if ($msg): ?><p class="ok"><?= $msg ?></p><?php endif; ?>

<section class="section">
  <h2><?= $edit ? 'Editar cita' : 'Nueva cita' ?></h2>
  <form class="form" method="post" action="citas.php">
    <input type="hidden" name="action" value="save">
    <input type="hidden" name="id" value="<?= $edit['id_cita'] ?? 0 ?>">
    <label>Mascota</label>
    <select name="id_mascota" required>
      <?php foreach ($mascotas as $m): ?>
        <option value="<?= $m['id_mascota'] ?>" <?= (($edit['id_mascota'] ?? 0)==$m['id_mascota'])?'selected':'' ?>>
          <?= htmlspecialchars($m['nombre']) ?>
        </option>
      <?php endforeach; ?>
    </select>
    <label>Fecha</label><input type="date" name="fecha" required value="<?= $edit['fecha'] ?? '' ?>">
    <label>Hora</label><input type="time" name="hora" required value="<?= $edit['hora'] ?? '' ?>">
    <label>Estado</label>
    <select name="estado" required>
      <?php foreach (['Programada','Confirmada','Atendida','Cancelada'] as $e): ?>
        <option <?= (($edit['estado'] ?? '')===$e)?'selected':'' ?>><?= $e ?></option>
      <?php endforeach; ?>
    </select>
    <button type="submit"><?= $edit ? 'Actualizar' : 'Registrar' ?></button>
    <?php if ($edit): ?><a class="btn secondary" href="citas.php">Cancelar</a><?php endif; ?>
  </form>
</section>

<section class="section">
  <h2>Listado</h2>
  <div class="table-box"><table>
    <tr><th>ID</th><th>Mascota</th><th>Fecha</th><th>Hora</th><th>Estado</th><th>Acciones</th></tr>
    <?php foreach ($lista as $c): ?>
      <tr>
        <td><?= $c['id_cita'] ?></td>
        <td><?= htmlspecialchars($c['mascota']) ?></td>
        <td><?= htmlspecialchars($c['fecha']) ?></td>
        <td><?= substr($c['hora'],0,5) ?></td>
        <td><span class="badge"><?= htmlspecialchars($c['estado']) ?></span></td>
        <td>
          <a class="btn small" href="citas.php?action=edit&id=<?= $c['id_cita'] ?>">Editar</a>
          <?php if ($c['estado'] !== 'Atendida'): ?>
            <a class="btn small" href="citas.php?action=estado&id=<?= $c['id_cita'] ?>&nuevo=Atendida">Atender</a>
          <?php endif; ?>
          <a class="btn small danger" href="citas.php?action=delete&id=<?= $c['id_cita'] ?>" onclick="return confirm('¿Eliminar?')">Eliminar</a>
        </td>
      </tr>
    <?php endforeach; ?>
  </table></div>
</section>
<?php require 'includes/footer.php'; ?>