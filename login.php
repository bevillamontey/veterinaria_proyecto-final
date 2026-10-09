<?php require 'includes/header.php'; ?>
<div class="login-wrap">
    <div class="login">
        <h1>🐾 Animal Life</h1>
        <h2 style="text-align:center">Iniciar sesión</h2>
        
        <form class="form" action="dashboard.php" method="post">
            <label>Correo</label>
            <input type="email" name="correo" required>
            
            <label>Contraseña</label>
            <input type="password" name="contrasena" required>
            
            <button type="submit">Ingresar</button>
        </form>
    </div>
</div>
<?php require 'includes/footer.php'; ?>
