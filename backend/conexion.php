<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "tiendaplus";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("❌ Error de conexión: " . $conn->connect_error);
}

// ✅ No mostrar mensaje en pantalla
// echo "✅ Conexión exitosa a la base de datos.";
?>
