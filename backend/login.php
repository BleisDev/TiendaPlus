<?php
session_start();
require_once "conexion.php";
$db = new Conexion();
$conexion = $db->conexion;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo   = $_POST['correo'];
    $password = $_POST['password'];

    $query = $conexion->prepare("SELECT * FROM usuarios WHERE correo = ?");
    $query->execute([$correo]);
    $usuario = $query->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($password, $usuario['password'])) {
        $_SESSION['usuario'] = $usuario['nombre'];
        echo "Bienvenido, " . $usuario['nombre'] . " 🎉 <a href='../web/index.php'>Ir al inicio</a>";
    } else {
        echo "Correo o contraseña incorrectos ❌ <a href='../web/login.html'>Volver</a>";
    }
}
?>
