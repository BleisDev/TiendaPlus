<?php
include("../includes/db.php");
$id = $_GET['id'];
$result = $conn->query("SELECT * FROM productos WHERE id=$id");
$producto = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Producto</title>
</head>
<body>
    <h2>Editar Producto</h2>
    <form action="update_producto.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $producto['id'] ?>">

        <label>Nombre:</label><br>
        <input type="text" name="nombre" value="<?= $producto['nombre'] ?>" required><br><br>

        <label>Descripción:</label><br>
        <textarea name="descripcion"><?= $producto['descripcion'] ?></textarea><br><br>

        <label>Precio:</label><br>
        <input type="number" step="0.01" name="precio" value="<?= $producto['precio'] ?>" required><br><br>

        <label>Stock:</label><br>
        <input type="number" name="stock" value="<?= $producto['stock'] ?>" required><br><br>

        <label>Imagen:</label><br>
        <input type="file" name="imagen"><br>
        <img src="../web/<?= $producto['imagen'] ?>" width="80"><br><br>

        <button type="submit">Actualizar</button>
    </form>
</body>
</html>
