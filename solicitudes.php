<?php
require_once 'includes/data.php';
require_login();

$action = $_POST['action'] ?? $_GET['action'] ?? '';
$msg = '';
$rol = $_SESSION['rol'];

if ($action === 'save') {
    q_exec($pdo, "INSERT INTO solicitudes (id_mascota, motivo) VALUES (?,?)",
           [(int)$_POST['id_mascota'], trim($_POST['motivo'])]);
    $msg = 'Solicitud registrada.';
    $action = '';
}
if ($action === 'estado') {
    q_exec($pdo, "UPDATE solicitudes SET estado=? WHERE id_solicitud=?",
           [$_GET['nuevo'], (int)$_GET['id']]);
    $msg = 'Estado actualizado.';
    $action = '';
}

$mascotas = q_all($pdo, "SELECT id_mascota, nombre FROM mascotas ORDER BY nombre");
$lista = q_all($pdo, "SELECT s.*, m.nombre AS mascota FROM solicitudes s 
                     JOIN mascotas m ON s.id_mascota=m.id_mascota 
                     ORDER BY s.id_solicitud DESC");

require 'includes/header.php';
?>
<div class="page-title"><a class="back" href="dashboard.php">← Volver al panel</a><h1>Solicitudes de atención</h1><p>Solicitudes realizadas por los propietarios.</p></div>
<?php if ($msg): ?><p class="ok"><?= $msg ?></p><?php endif; ?>

<?php if ($rol === 'Propietario'): ?>
<section class="section">
  <h2>Nueva solicitud</h2>
  <form class="form" method="post" action="solicitudes.php">
    <input type="hidden" name="action" value="save">
    <label>Mascota</label>
    <select name="id_mascota" required>
      <?php foreach ($mascotas as $m): ?>
        <option value="<?= $m['id_mascota'] ?>"><?= htmlspecialchars($m['nombre']) ?></option>
      <?php endforeach; ?>
    </select>
    <label>Motivo</label><input name="motivo" required>
    <button type="submit">Enviar solicitud</button>
  </form>
</section>
<?php endif; ?>

<section class="section">
  <h2>Listado</h2>
  <div class="table-box"><table>
    <tr><th>ID</th><th>Mascota</th><th>Motivo</th><th>Estado</th><th>Acciones</th></tr>
    <?php foreach ($lista as $s): ?>
      <tr>
        <td><?= $s['id_solicitud'] ?></td>
        <td><?= htmlspecialchars($s['mascota']) ?></td>
        <td><?= htmlspecialchars($s['motivo']) ?></td>
        <td><span class="badge"><?= htmlspecialchars($s['estado']) ?></span></td>
        <td>
          <?php if (in_array($rol, ['Administrador','Recepcionista']) && $s['estado']==='Pendiente'): ?>
            <a class="btn small" href="solicitudes.php?action=estado&id=<?= $s['id_solicitud'] ?>&nuevo=Aceptada">Aprobar</a>
            <a class="btn small danger" href="solicitudes.php?action=estado&id=<?= $s['id_solicitud'] ?>&nuevo=Rechazada">Rechazar</a>
          <?php else: ?>—<?php endif; ?>
        </td>
      </tr>
    <?php endforeach; ?>
  </table></div>
</section>
<?php require 'includes/footer.php'; ?>