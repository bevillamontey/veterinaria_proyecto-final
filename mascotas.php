<?php
require_once 'includes/data.php';
require_rol(['Administrador','Recepcionista','Veterinario']);

$action = $_POST['action'] ?? $_GET['action'] ?? '';
$msg = '';

if ($action === 'save') {
    $id = (int)($_POST['id'] ?? 0);
    $datos = [
        trim($_POST['nombre']),
        trim($_POST['especie']),
        trim($_POST['raza']),
        (int)$_POST['edad'],
        (int)$_POST['id_propietario'],
    ];
    if ($id > 0) {
        q_exec($pdo, "UPDATE mascotas SET nombre=?, especie=?, raza=?, edad=?, id_propietario=? WHERE id_mascota=?",
               array_merge($datos, [$id]));
        $msg = 'Mascota actualizada.';
    } else {
        q_exec($pdo, "INSERT INTO mascotas (nombre, especie, raza, edad, id_propietario) VALUES (?,?,?,?,?)", $datos);
        $msg = 'Mascota registrada.';
    }
    $action = '';
}
if ($action === 'delete') {
    q_exec($pdo, "DELETE FROM mascotas WHERE id_mascota=?", [(int)$_GET['id']]);
    $msg = 'Mascota eliminada.';
    $action = '';
}

$edit  = ($action === 'edit') ? q_one($pdo, "SELECT * FROM mascotas WHERE id_mascota=?", [(int)$_GET['id']]) : null;
$props = q_all($pdo, "SELECT id_propietario, nombre FROM propietarios ORDER BY nombre");
$lista = q_all($pdo, "SELECT m.*, p.nombre AS propietario FROM mascotas m 
                     JOIN propietarios p ON m.id_propietario=p.id_propietario 
                     ORDER BY m.id_mascota DESC");

require 'includes/header.php';
?>
<div class="page-title"><a class="back" href="dashboard.php">← Volver al panel</a><h1>Gestión de mascotas</h1><p>Mascotas registradas y asociadas a sus propietarios.</p></div>
<?php if ($msg): ?><p class="ok"><?= $msg ?></p><?php endif; ?>

<section class="section">
  <h2><?= $edit ? 'Editar mascota' : 'Nueva mascota' ?></h2>
  <form class="form" method="post" action="mascotas.php">
    <input type="hidden" name="action" value="save">
    <input type="hidden" name="id" value="<?= $edit['id_mascota'] ?? 0 ?>">
    <label>Nombre</label><input name="nombre" required value="<?= htmlspecialchars($edit['nombre'] ?? '') ?>">
    <label>Especie</label>
    <select name="especie" required>
      <?php foreach (['Perro','Gato','Ave','Roedor','Otro'] as $e): ?>
        <option <?= (($edit['especie'] ?? '')===$e)?'selected':'' ?>><?= $e ?></option>
      <?php endforeach; ?>
    </select>
    <label>Raza</label><input name="raza" value="<?= htmlspecialchars($edit['raza'] ?? '') ?>">
    <label>Edad</label><input type="number" name="edad" min="0" value="<?= htmlspecialchars($edit['edad'] ?? 0) ?>">
    <label>Propietario</label>
    <select name="id_propietario" required>
      <?php foreach ($props as $p): ?>
        <option value="<?= $p['id_propietario'] ?>" <?= (($edit['id_propietario'] ?? 0)==$p['id_propietario'])?'selected':'' ?>>
          <?= htmlspecialchars($p['nombre']) ?>
        </option>
      <?php endforeach; ?>
    </select>
    <button type="submit"><?= $edit ? 'Actualizar' : 'Registrar' ?></button>
    <?php if ($edit): ?><a class="btn secondary" href="mascotas.php">Cancelar</a><?php endif; ?>
  </form>
</section>

<section class="section">
  <h2>Listado</h2>
  <div class="table-box"><table>
    <tr><th>ID</th><th>Mascota</th><th>Especie</th><th>Raza</th><th>Edad</th><th>Propietario</th><th>Acciones</th></tr>
    <?php foreach ($lista as $m): ?>
      <tr>
        <td><?= $m['id_mascota'] ?></td>
        <td><?= htmlspecialchars($m['nombre']) ?></td>
        <td><?= htmlspecialchars($m['especie']) ?></td>
        <td><?= htmlspecialchars($m['raza']) ?></td>
        <td><?= (int)$m['edad'] ?></td>
        <td><?= htmlspecialchars($m['propietario']) ?></td>
        <td>
          <a class="btn small" href="mascotas.php?action=edit&id=<?= $m['id_mascota'] ?>">Editar</a>
          <a class="btn small danger" href="mascotas.php?action=delete&id=<?= $m['id_mascota'] ?>" onclick="return confirm('¿Eliminar?')">Eliminar</a>
        </td>
      </tr>
    <?php endforeach; ?>
  </table></div>
</section>
<?php require 'includes/footer.php'; ?>