<?php
require_once __DIR__ . '/data.php';

$publicas = ['index.php','servicios.php','login.php'];
if (!in_array(basename($_SERVER['PHP_SELF']), $publicas)) require_login();
$rol_actual = $_SESSION['rol'] ?? null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Animal Life</title>
<link rel="stylesheet" href="css/estilos.css">
</head>
<body>
<header class="topbar">
  <div class="logo">🐾 Animal Life</div>
  <nav>
    <a href="index.php">Inicio</a>
    <a href="servicios.php">Servicios</a>
    <?php if ($rol_actual): ?>
      <a href="dashboard.php">Panel</a>
      <span class="user"><?= htmlspecialchars($_SESSION['nombre']) ?> (<?= htmlspecialchars($rol_actual) ?>)</span>
      <a href="logout.php">Cerrar sesión</a>
    <?php else: ?>
      <a href="login.php">Iniciar sesión</a>
    <?php endif; ?>
  </nav>
</header>
<main class="container">