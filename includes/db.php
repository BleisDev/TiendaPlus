<?php
$host = "localhost";
$user = "root";  // o tu usuario de MySQL
$pass = "";      // tu contraseña de MySQL (si no tiene, déjalo vacío)
$db   = "tiendaplus";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>
