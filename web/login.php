<?php
session_start();
include_once('../backend/conexion.php'); 

$errorLogin = "";
$successRegistro = "";

// --- LOGIN ---
if (isset($_POST['login'])) {
    $correo = trim($_POST['correo']);
    $pass   = trim($_POST['pass']);

    $sql = $conn->prepare("SELECT * FROM usuarios WHERE email = ? LIMIT 1");
    $sql->bind_param("s", $correo);
    $sql->execute();
    $res = $sql->get_result();

    if ($res->num_rows > 0) {
        $usuario = $res->fetch_assoc();

        // ADMIN (contraseña en texto plano)
        if ($usuario['rol'] === 'admin' && $pass === $usuario['password']) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['nombre'] = $usuario['nombre'];
            $_SESSION['rol'] = $usuario['rol'];
            header("Location: ../backend/panel.php");
            exit;
        } 
        // CLIENTE (contraseña en hash)
        elseif ($usuario['rol'] === 'cliente' && password_verify($pass, $usuario['password'])) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['nombre'] = $usuario['nombre'];
            $_SESSION['rol'] = $usuario['rol'];
            header("Location: index.php");
            exit;
        } else {
            $errorLogin = "Contraseña incorrecta.";
        }
    } else {
        $errorLogin = "Correo no encontrado.";
    }
}

// --- REGISTRO ---
if (isset($_POST['registro'])) {
    $nombre = trim($_POST['nombre']);
    $correo = trim($_POST['correo']);
    $pass   = password_hash(trim($_POST['pass']), PASSWORD_BCRYPT);

    $sql = $conn->prepare("INSERT INTO usuarios (nombre, email, password, rol, fecha_creacion) VALUES (?, ?, ?, 'cliente', NOW())");
    $sql->bind_param("sss", $nombre, $correo, $pass);

    if ($sql->execute()) {
        $successRegistro = "Usuario registrado correctamente. Ya puedes iniciar sesión.";
    } else {
        $errorLogin = "Error al registrar usuario.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Acceder - Tienda Plus</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
  background-color: #fdfdfd;
  font-family: 'Poppins', sans-serif;
}
.container {
  max-width: 420px;
  margin-top: 80px;
}
.tabs {
  display: flex;
  justify-content: center;
  margin-bottom: 30px;
}
.tabs button {
  background: none;
  border: none;
  font-size: 1.3rem;
  font-weight: 500;
  color: #bbb;
  margin: 0 10px;
  padding-bottom: 5px;
  cursor: pointer;
  border-bottom: 2px solid transparent;
}
.tabs button.active {
  color: #ff6fae;
  border-color: #ff6fae;
}
.btn-pink {
  background: #ff6fae;
  color: white;
  font-weight: 600;
  border-radius: 25px;
  padding: 10px;
  border: none;
  width: 100%;
}
.btn-pink:hover { background: #e85c9d; }
.card {
  border-radius: 15px;
  box-shadow: 0 0 10px rgba(0,0,0,0.05);
  padding: 25px;
}
</style>
</head>
<body>
<div class="container">
    <div class="tabs">
        <button class="active" id="btnLogin" onclick="mostrarFormulario('login')">Acceder</button>
        <button id="btnRegistro" onclick="mostrarFormulario('registro')">Registrarse</button>
    </div>

    <div id="formLogin" class="card">
        <h4 class="text-center mb-4">Acceder</h4>
        <?php if($errorLogin): ?><div class="alert alert-danger"><?= $errorLogin ?></div><?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <label>Correo electrónico *</label>
                <input type="email" name="correo" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Contraseña *</label>
                <input type="password" name="pass" class="form-control" required>
            </div>
            <button type="submit" name="login" class="btn-pink">Acceder</button>
        </form>
    </div>

    <div id="formRegistro" class="card" style="display:none;">
        <h4 class="text-center mb-4">Registro</h4>
        <?php if($successRegistro): ?><div class="alert alert-success"><?= $successRegistro ?></div><?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <label>Nombre completo *</label>
                <input type="text" name="nombre" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Correo electrónico *</label>
                <input type="email" name="correo" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Contraseña *</label>
                <input type="password" name="pass" class="form-control" required>
            </div>
            <button type="submit" name="registro" class="btn-pink">Registrarse</button>
        </form>
    </div>
</div>

<script>
function mostrarFormulario(tipo) {
    const login = document.getElementById('formLogin');
    const registro = document.getElementById('formRegistro');
    const btnLogin = document.getElementById('btnLogin');
    const btnRegistro = document.getElementById('btnRegistro');

    if (tipo === 'login') {
        login.style.display = 'block';
        registro.style.display = 'none';
        btnLogin.classList.add('active');
        btnRegistro.classList.remove('active');
    } else {
        login.style.display = 'none';
        registro.style.display = 'block';
        btnLogin.classList.remove('active');
        btnRegistro.classList.add('active');
    }
}
</script>
</body>
</html>
