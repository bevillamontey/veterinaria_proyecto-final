<?php
require_once 'includes/data.php';
require_login();
require 'includes/header.php';

$rol = $_SESSION['rol'];

$modulos = [
  'Administrador' => [
    ['icon'=>'👥','t'=>'Usuarios','d'=>'Cuentas y roles.','u'=>'usuarios.php'],
    ['icon'=>'👤','t'=>'Propietarios','d'=>'Registrar y editar.','u'=>'propietarios.php'],
    ['icon'=>'🐶','t'=>'Mascotas','d'=>'Mascotas por propietario.','u'=>'mascotas.php'],
    ['icon'=>'📨','t'=>'Solicitudes','d'=>'Gestionar solicitudes.','u'=>'solicitudes.php'],
    ['icon'=>'📅','t'=>'Citas','d'=>'Registrar y consultar.','u'=>'citas.php'],
    ['icon'=>'🩺','t'=>'Atenciones','d'=>'Diagnóstico y tratamiento.','u'=>'atenciones.php'],
    ['icon'=>'💳','t'=>'Pagos','d'=>'Registro de pagos.','u'=>'pagos.php'],
  ],
  'Recepcionista' => [
    ['icon'=>'👤','t'=>'Propietarios','d'=>'Registrar y editar.','u'=>'propietarios.php'],
    ['icon'=>'🐶','t'=>'Mascotas','d'=>'Mascotas por propietario.','u'=>'mascotas.php'],
    ['icon'=>'📨','t'=>'Solicitudes','d'=>'Aprobar o rechazar.','u'=>'solicitudes.php'],
    ['icon'=>'📅','t'=>'Citas','d'=>'Registrar y actualizar.','u'=>'citas.php'],
    ['icon'=>'💳','t'=>'Pagos','d'=>'Registrar y consultar.','u'=>'pagos.php'],
  ],
  'Veterinario' => [
    ['icon'=>'🐶','t'=>'Mascotas','d'=>'Consultar mascotas.','u'=>'mascotas.php'],
    ['icon'=>'🩺','t'=>'Atenciones','d'=>'Registrar atención.','u'=>'atenciones.php'],
  ],
  'Propietario' => [
    ['icon'=>'📨','t'=>'Mis solicitudes','d'=>'Solicitar atención.','u'=>'solicitudes.php'],
    ['icon'=>'🩺','t'=>'Servicios','d'=>'Ver servicios.','u'=>'servicios.php'],
  ],
];
$lista = $modulos[$rol] ?? [];
?>
<div class="dashboard">
  <div class="welcome">
    <h1>Panel principal</h1>
    <p>Bienvenido, <?= htmlspecialchars($_SESSION['nombre']) ?>.</p>
    <span>Rol: <?= htmlspecialchars($rol) ?></span>
  </div>
  <div class="grid">
    <?php foreach ($lista as $m): ?>
      <div class="module">
        <div class="icon"><?= $m['icon'] ?></div>
        <h3><?= $m['t'] ?></h3>
        <p><?= $m['d'] ?></p>
        <a class="btn" href="<?= $m['u'] ?>">Ver módulo</a>
      </div>
    <?php endforeach; ?>
  </div>
</div>
<?php require 'includes/footer.php'; ?>