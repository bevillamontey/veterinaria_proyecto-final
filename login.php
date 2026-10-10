<?php
require_once 'includes/data.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = trim($_POST['correo'] ?? '');
    $pass   = $_POST['contrasena'] ?? '';

    $u = q_one($pdo, "SELECT u.*, r.nombre AS rol 
                      FROM usuarios u 
                      JOIN roles r ON u.id_rol = r.id_rol 
                      WHERE u.correo=? AND u.estado=1", [$correo]);

    if ($u && password_verify($pass, $u['contrasena'])) {
        $_SESSION['id_usuario'] = $u['id_usuario'];
        $_SESSION['nombre']     = $u['nombre'];
        $_SESSION['rol']        = $u['rol'];
        header('Location: dashboard.php'); exit;
    }
    $error = 'Credenciales incorrectas.';
}
require 'includes/header.php';
?>
<div class="login-wrap"><div class="login">
  <h1>🐾 Animal Life</h1>
  <h2 style="text-align:center">Iniciar sesión</h2>
  <?php if ($error): ?><p class="error"><?= $error ?></p><?php endif; ?>
  <form class="form" method="post" action="login.php" style="box-shadow:none;padding:0;background:none">
    <label>Correo</label>
    <input type="email" name="correo" value="admin@animallife.com" required>
    <label>Contraseña</label>
    <input type="password" name="contrasena" value="admin123" required>
    <button type="submit">Ingresar</button>
  </form>
  <div class="demo">
    <strong>Usuarios de prueba:</strong><br>
    admin@animallife.com / admin123<br>
    recepcion@animallife.com / recepcion123<br>
    veterinario@animallife.com / vet123<br>
    propietario@animallife.com / prop123
  </div>
</div></div>
<?php require 'includes/footer.php'; ?>