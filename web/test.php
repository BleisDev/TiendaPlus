<?php
$pass = "123456"; // contraseña del login
$hash = "123456"; // lo que está en la base de datos

if ($pass === $hash) {
    echo "Funciona ✅";
} else {
    echo "No funciona ❌";
}
?>
