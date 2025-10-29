<?php
session_start();
include_once('../backend/conexion.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = $conn->prepare("SELECT * FROM usuarios WHERE email = ?");
    $sql->bind_param("s", $email);
    $sql->execute();
    $result = $sql->get_result();

    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();

        // Comparar contraseña (si está encriptada)
        if (password_verify($password, $usuario['password'])) {
            // ✅ Guardar datos en sesión
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nombre'] = $usuario['nombre'];
            $_SESSION['usuario_email'] = $usuario['email'];
            $_SESSION['usuario_rol'] = $usuario['rol'];

            // Redirigir correctamente al perfil
            header("Location: ../web/perfil.php");
            exit;
        } else {
            echo "⚠️ Contraseña incorrecta.";
        }
    } else {
        echo "⚠️ Usuario no encontrado.";
    }
}
?>
