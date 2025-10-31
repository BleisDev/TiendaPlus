<?php
session_start();
include_once('../backend/conexion.php'); // Ajusta ruta si es necesario

$error = "";
$redir = $_GET['redir'] ?? 'index.php';

if (isset($_POST['registrar'])) {
    $nombre = trim($_POST['nombre']);
    // Sanitizar email para eliminar espacios invisibles u otros caracteres
    $email_raw = $_POST['email'] ?? '';
    $email = filter_var(trim($email_raw), FILTER_SANITIZE_EMAIL);
    $pass   = trim($_POST['pass']);

    // Validaciones básicas
    if (empty($nombre) || empty($email) || empty($pass)) {
        $error = "❌ Todos los campos son obligatorios.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "❌ El correo no tiene un formato válido.";
    } elseif (strlen($pass) < 6) {
        $error = "❌ La contraseña debe tener al menos 6 caracteres.";
    } else {
        // Verificar si el correo ya está registrado
        $sqlCheck = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
        $sqlCheck->bind_param("s", $email);
        $sqlCheck->execute();
        $resultadoCheck = $sqlCheck->get_result();

        if ($resultadoCheck->num_rows > 0) {
            $error = "❌ El correo ya está registrado.";
        } else {
            // Cifrar contraseña y guardar
            $clave_segura = password_hash($pass, PASSWORD_DEFAULT);
            $rol = 'cliente';

            $sql = $conn->prepare("INSERT INTO usuarios (nombre, email, password, rol) VALUES (?, ?, ?, ?)");
            $sql->bind_param("ssss", $nombre, $email, $clave_segura, $rol);

            if ($sql->execute()) {
                // Guardar sesión con nombre correcto
                $_SESSION['usuario_id'] = $conn->insert_id;
                $_SESSION['nombre'] = $nombre;
                $_SESSION['rol'] = $rol;

                // Mensaje de éxito en sesión y redirección
                $_SESSION['registro_exito'] = "✅ Usuario registrado correctamente. Bienvenido/a, $nombre.";
                header("Location: $redir");
                exit;
            } else {
                $error = "❌ Error al crear el usuario: " . $conn->error;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Registro - Tienda Plus</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { font-family: Arial, sans-serif; background:#f8f9fa; }
.container { max-width: 450px; margin:80px auto; }
.btn-pink { background:#f08db2; color:white; font-weight:600; border-radius:30px; padding:10px; border:none; }
.btn-pink:hover { background:#e76da0; }
</style>
</head>
<body>
<div class="container">
    <h2 class="text-center mb-4">🛍️ Registro de Usuario</h2>

    <?php
    // Mostrar mensaje de éxito si existe (después de registro)
    if (!empty($_SESSION['registro_exito'])) {
        echo '<div class="alert alert-success">'.htmlspecialchars($_SESSION['registro_exito']).'</div>';
        unset($_SESSION['registro_exito']);
    }
    // Mostrar error del servidor si existe
    if (!empty($error)) {
        echo '<div class="alert alert-danger">'.htmlspecialchars($error).'</div>';
    }
    ?>

    <form method="POST" id="formRegistro" novalidate>
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
        <button type="submit" name="registrar" class="btn btn-pink w-100">Registrarme</button>
        <p class="text-center mt-3">¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a></p>
    </form>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
  const form = document.querySelector("#formRegistro");

  form.addEventListener("submit", function(e) {
    const nombre = document.querySelector("input[name='nombre']");
    const email = document.querySelector("input[name='email']");
    const pass = document.querySelector("input[name='pass']");
    let errores = [];

    // Validaciones visuales (regex corregido)
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (nombre.value.trim() === "") errores.push("⚠️ El nombre es obligatorio.");
    if (email.value.trim() === "") errores.push("⚠️ El correo es obligatorio.");
    else if (!emailRegex.test(email.value.trim()))
      errores.push("⚠️ El correo no es válido.");
    if (pass.value.trim() === "") errores.push("⚠️ La contraseña es obligatoria.");
    else if (pass.value.trim().length < 6)
      errores.push("⚠️ La contraseña debe tener al menos 6 caracteres.");

    // Mostrar errores
    if (errores.length > 0) {
      e.preventDefault();
      let alerta = document.createElement("div");
      alerta.className = "alert alert-danger mt-3";
      alerta.innerHTML = errores.join("<br>");
      const alertaAnterior = document.querySelector(".alert-danger");
      if (alertaAnterior) alertaAnterior.remove();
      form.appendChild(alerta);
      return false;
    }
  });
});
</script>
</body>
</html>
