<?php require 'includes/header.php'; ?>
<div class="dashboard"><div class="welcome"><h1>Panel principal</h1><p>Bienvenido, Administrador.</p><span>Rol: Administrador</span></div>
<div class="grid">
 <div class="module"><div class="icon">👥</div><h3>Usuarios</h3><p>Gestión de cuentas y asignación de roles.</p><a class="btn" href="usuarios.php">Ver módulo</a></div>
 <div class="module"><div class="icon">👤</div><h3>Propietarios</h3><p>Registrar, editar y consultar propietarios.</p><a class="btn" href="propietarios.php">Ver módulo</a></div>
 <div class="module"><div class="icon">🐶</div><h3>Mascotas</h3><p>Gestionar las mascotas asociadas a cada propietario.</p><a class="btn" href="mascotas.php">Ver módulo</a></div>
 <div class="module"><div class="icon">📨</div><h3>Solicitudes</h3><p>Gestionar solicitudes de atención.</p><a class="btn" href="solicitudes.php">Ver módulo</a></div>
 <div class="module"><div class="icon">📅</div><h3>Citas</h3><p>Registrar y consultar citas.</p><a class="btn" href="citas.php">Ver módulo</a></div>
 <div class="module"><div class="icon">🩺</div><h3>Atenciones</h3><p>Registrar diagnóstico, tratamiento y observaciones.</p><a class="btn" href="atenciones.php">Ver módulo</a></div>
 <div class="module"><div class="icon">💳</div><h3>Pagos</h3><p>Consultar los pagos registrados.</p><a class="btn" href="pagos.php">Ver módulo</a></div>
</div></div>
<?php require 'includes/footer.php'; ?>
