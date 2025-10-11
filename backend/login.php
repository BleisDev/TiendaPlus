<?php
session_start();
include_once('conexion.php'); // Ajusta la ruta si es necesario

$errorLogin = "";

if(isset($_POST['login'])) {
    $correo = trim($_POST['correo']);
    $pass   = trim($_POST['pass']);

    $sql = $conn->prepare("SELECT * FROM usuarios WHERE email = ? LIMIT 1");
    $sql->bind_param("s", $correo);
    $sql->execute();
    $res = $sql->get_result();

    if($res->num_rows > 0) {
        $usuario = $res->fetch_assoc();

        // --- ADMIN: contraseña en texto plano ---
        if($usuario['rol'] === 'admin' && $pass === $usuario['password']) {
            $_SESSION['id_usuario'] = $usuario['id'];
            $_SESSION['nombre'] = $usuario['nombre'];
            $_SESSION['rol'] = $usuario['rol'];
            header("Location: panel.php"); // Admin directo al panel
            exit;
        } 
        // --- CLIENTE: contraseña con hash ---
        elseif($usuario['rol'] !== 'admin' && password_verify($pass, $usuario['password'])) {
            $_SESSION['id_usuario'] = $usuario['id'];
            $_SESSION['nombre'] = $usuario['nombre'];
            $_SESSION['rol'] = $usuario['rol'];
            header("Location: index.php"); // Cliente al front
            exit;
        } 
        else {
            $errorLogin = "Contraseña incorrecta";
        }
    } else {
        $errorLogin = "Correo no encontrado";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Login Administrador</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { font-family: Arial, sans-serif; background:#f8f9fa; }
.container { max-width: 400px; margin:100px auto; }
.btn-pink { background:#f08db2; color:white; font-weight:600; border-radius:30px; padding:10px; border:none; }
.btn-pink:hover { background:#e76da0; }
</style>
</head>
<body>
<div class="container">
    <h2 class="text-center mb-4">Login Administrador</h2>
    <?php if($errorLogin): ?>
        <div class="alert alert-danger"><?= $errorLogin ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="mb-3">
            <label>Correo electrónico</label>
            <input type="email" name="correo" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Contraseña</label>
            <input type="password" name="pass" class="form-control" required>
        </div>
        <button type="submit" name="login" class="btn btn-pink w-100">Acceder</button>
    </form>
</div>
</body>
</html>
