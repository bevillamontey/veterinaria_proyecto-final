<?php
require_once 'includes/data.php';
require_login();

$action = $_POST['action'] ?? $_GET['action'] ?? '';
$msg = '';
$rol = $_SESSION['rol'];

if ($action === 'save') {
    if ($rol !== 'Veterinario' && $rol !== 'Administrador') {
        $msg = 'No tienes permiso para registrar atenciones.';
    } else {
        q_exec($pdo, "INSERT INTO atenciones (id_mascota, id_usuario, diagnostico, tratamiento, observaciones) VALUES (?,?,?,?,?)",
               [(int)$_POST['id_mascota'], (int)$_SESSION['id_usuario'],
                trim($_POST['diagnostico']), trim($_POST['tratamiento']), trim($_POST['observaciones'])]);
        $msg = 'Atención registrada correctamente.';
    }
    $action = '';
}

$mascotas = q_all($pdo, "SELECT id_mascota, nombre FROM mascotas ORDER BY nombre");
$lista = q_all($pdo, "SELECT a.*, m.nombre AS mascota, u.nombre AS veterinario 
                     FROM atenciones a 
                     JOIN mascotas m ON a.id_mascota=m.id_mascota 
                     JOIN usuarios u ON a.id_usuario=u.id_usuario 
                     ORDER BY a.fecha DESC");

require 'includes/header.php';
?>
<div class="page-title"><a class="back" href="dashboard.php">← Volver al panel</a><h1>Atenciones veterinarias</h1><p>Registro de diagnóstico, tratamiento y observaciones.</p></div>
<?php if ($msg): ?><p class="ok"><?= $msg ?></p><?php endif; ?>

<?php if (in_array($rol, ['Veterinario','Administrador'])): ?>
<section class="section">
  <h2>Registrar atención</h2>
  <form class="form" method="post" action="atenciones.php">
    <input type="hidden" name="action" value="save">
    <label>Mascota</label>
    <select name="id_mascota" required>
      <?php foreach ($mascotas as $m): ?>
        <option value="<?= $m['id_mascota'] ?>"><?= htmlspecialchars($m['nombre']) ?></option>
      <?php endforeach; ?>
    </select>
    <label>Diagnóstico</label><textarea name="diagnostico" rows="2" required></textarea>
    <label>Tratamiento</label><textarea name="tratamiento" rows="2" required></textarea>
    <label>Observaciones</label><textarea name="observaciones" rows="2"></textarea>
    <button type="submit">Registrar atención</button>
  </form>
</section>
<?php endif; ?>

<section class="section">
  <h2>Historial de atenciones</h2>
  <div class="table-box"><table>
    <tr><th>Fecha</th><th>Mascota</th><th>Veterinario</th><th>Diagnóstico</th><th>Tratamiento</th><th>Observaciones</th></tr>
    <?php foreach ($lista as $a): ?>
      <tr>
        <td><?= htmlspecialchars($a['fecha']) ?></td>
        <td><?= htmlspecialchars($a['mascota']) ?></td>
        <td><?= htmlspecialchars($a['veterinario']) ?></td>
        <td><?= htmlspecialchars($a['diagnostico']) ?></td>
        <td><?= htmlspecialchars($a['tratamiento']) ?></td>
        <td><?= htmlspecialchars($a['observaciones']) ?></td>
      </tr>
    <?php endforeach; ?>
  </table></div>
</section>
<?php require 'includes/footer.php'; ?>