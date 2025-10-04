<?php
include("../includes/db.php");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Producto</title>
</head>
<body>
    <h2>Nuevo Producto</h2>
    <form action="guardar_producto.php" method="POST" enctype="multipart/form-data">
        <label>Nombre:</label><br>
        <input type="text" name="nombre" required><br><br>

        <label>Descripción:</label><br>
        <textarea name="descripcion"></textarea><br><br>

        <label>Precio:</label><br>
        <input type="number" step="0.01" name="precio" required><br><br>

        <label>Stock:</label><br>
        <input type="number" name="stock" required><br><br>

        <label>Imagen:</label><br>
        <input type="file" name="imagen"><br><br>

        <button type="submit">Guardar</button>
    </form>
</body>
</html>
