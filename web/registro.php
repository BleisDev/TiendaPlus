<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// 1️⃣ Conexión a la base de datos
$conn = new mysqli("localhost", "root", "", "TiendaPlus");

// 2️⃣ Verificar conexión
if ($conn->connect_error) {
    die("❌ Conexión fallida: " . $conn->connect_error);
}

// 3️⃣ Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['nombre'], $_POST['correo'], $_POST['password'])) {
    $nombre   = trim($_POST['nombre']);
    $correo   = trim($_POST['correo']);
    $password = trim($_POST['password']);

    // 🔒 Encriptar contraseña para clientes
    $passwordHash = password_hash($password, PASSWORD_BCRYPT);

    // Verificar si ya existe el correo
    $check = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
    $check->bind_param("s", $correo);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $error = "⚠️ El correo ya está registrado. Intenta con otro.";
    } else {
        $stmt = $conn->prepare("INSERT INTO usuarios (nombre, email, password, rol) VALUES (?, ?, ?, 'cliente')");
        $stmt->bind_param("sss", $nombre, $correo, $passwordHash);

        if ($stmt->execute()) {
            // Redirige al login
            header("Location: login.php");
            exit();
        } else {
            $error = "Error al registrar: " . $stmt->error;
        }
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Registrarse - Tienda Plus</title>
<style>
body { font-family: Arial, sans-serif; padding: 20px; background-color: #f7f7f7; }
h2 { text-align: center; }
form { max-width: 400px; margin: 40px auto; background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0px 0px 10px rgba(0,0,0,0.1); }
input { width: 100%; padding: 10px; margin: 10px 0; border-radius: 5px; border: 1px solid #ccc; }
button { padding: 10px; width: 100%; background-color: #ff69b4; color: #fff; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; }
button:hover { background-color: #ff85c1; }
p.error { color: red; text-align: center; }
p.link { text-align: center; margin-top: 15px; }
a { color: #ff69b4; text-decoration: none; }
</style>
</head>
<body>

<h2>Registrarse</h2>
<?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>

<form method="POST" action="">
    <label>Nombre:</label>
    <input type="text" name="nombre" required>

    <label>Correo:</label>
    <input type="email" name="correo" required>

    <label>Contraseña:</label>
    <input type="password" name="password" required>

    <button type="submit">Registrarse</button>
</form>

<p class="link">¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a></p>

</body>
</html>
