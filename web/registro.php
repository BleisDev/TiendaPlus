<?php
session_start();
include_once('../backend/conexion.php');

$error = "";
$redir = $_GET['redir'] ?? 'index.php';

if (isset($_POST['registrar'])) {
    $nombre = trim($_POST['nombre']);
    $email  = trim($_POST['email']);
    $pass   = trim($_POST['pass']);

    // Validaciones
    if (empty($nombre) || empty($email) || empty($pass)) {
        $error = "❌ Todos los campos son obligatorios.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "❌ El correo no tiene un formato válido.";
    } elseif (strlen($pass) < 6) {
        $error = "❌ La contraseña debe tener al menos 6 caracteres.";
    } else {
        $sqlCheck = $conn->prepare("SELECT * FROM usuarios WHERE email = ?");
        $sqlCheck->bind_param("s", $email);
        $sqlCheck->execute();
        $resultadoCheck = $sqlCheck->get_result();

        if ($resultadoCheck->num_rows > 0) {
            $error = "❌ El correo ya está registrado.";
        } else {
            $clave_segura = password_hash($pass, PASSWORD_DEFAULT);

            $sql = $conn->prepare("INSERT INTO usuarios (nombre, email, password) VALUES (?, ?, ?)");
            $sql->bind_param("sss", $nombre, $email, $clave_segura);
            $sql->execute();

            $_SESSION['id_usuario'] = $conn->insert_id;
            $_SESSION['nombre'] = $nombre;

            header("Location: $redir");
            exit;
        }
    }
}
?>

<!-- HTML del formulario -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5" style="max-width: 500px;">
    <h2 class="mb-4">👤 Registro de Usuario</h2>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label>Nombre completo</label>
            <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>">
        </div>
        <div class="mb-3">
            <label>Correo electrónico</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
        </div>
        <div class="mb-3">
            <label>Contraseña</label>
            <input type="password" name="pass" class="form-control">
        </div>
        <button type="submit" name="registrar" class="btn btn-primary w-100">Registrarme</button>
    </form>
</div>
</body>
</html>
