<?php
require_once 'includes/data.php';
require_rol(['Administrador','Recepcionista']);

$action = $_POST['action'] ?? $_GET['action'] ?? '';
$msg = '';

if ($action === 'save') {
    q_exec($pdo, "INSERT INTO pagos (id_propietario, concepto, monto, metodo) VALUES (?,?,?,?)",
           [(int)$_POST['id_propietario'], trim($_POST['concepto']), (float)$_POST['monto'], trim($_POST['metodo'])]);
    $msg = 'Pago registrado.';
    $action = '';
}

$props = q_all($pdo, "SELECT id_propietario, nombre FROM propietarios ORDER BY nombre");
$lista = q_all($pdo, "SELECT p.*, pr.nombre AS propietario FROM pagos p 
                     JOIN propietarios pr ON p.id_propietario=pr.id_propietario 
                     ORDER BY p.fecha DESC");

require 'includes/header.php';
?>
<div class="page-title"><a class="back" href="dashboard.php">← Volver al panel</a><h1>Gestión de pagos</h1><p>Registro y consulta de pagos realizados por los propietarios.</p></div>
<?php if ($msg): ?><p class="ok"><?= $msg ?></p><?php endif; ?>

<section class="section">
  <h2>Registrar pago</h2>
  <form class="form" method="post" action="pagos.php">
    <input type="hidden" name="action" value="save">
    <label>Propietario</label>
    <select name="id_propietario" required>
      <?php foreach ($props as $p): ?>
        <option value="<?= $p['id_propietario'] ?>"><?= htmlspecialchars($p['nombre']) ?></option>
      <?php endforeach; ?>
    </select>
    <label>Concepto</label><input name="concepto" required>
    <label>Monto (S/)</label><input type="number" step="0.01" name="monto" required>
    <label>Método</label>
    <select name="metodo" required>
      <option>Efectivo</option><option>Yape</option><option>Plin</option><option>Tarjeta</option><option>Transferencia</option>
    </select>
    <button type="submit">Registrar pago</button>
  </form>
</section>

<section class="section">
  <h2>Listado</h2>
  <div class="table-box"><table>
    <tr><th>ID</th><th>Propietario</th><th>Concepto</th><th>Monto</th><th>Método</th><th>Fecha</th></tr>
    <?php foreach ($lista as $p): ?>
      <tr>
        <td><?= $p['id_pago'] ?></td>
        <td><?= htmlspecialchars($p['propietario']) ?></td>
        <td><?= htmlspecialchars($p['concepto']) ?></td>
        <td>S/ <?= number_format($p['monto'],2) ?></td>
        <td><?= htmlspecialchars($p['metodo']) ?></td>
        <td><?= htmlspecialchars($p['fecha']) ?></td>
      </tr>
    <?php endforeach; ?>
  </table></div>
</section>
<?php require 'includes/footer.php'; ?>