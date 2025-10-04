<?php
include("../includes/db.php");

$result = $conn->query("SELECT * FROM productos");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Productos</title>
</head>
<body>
    <h2>Productos</h2>
    <a href="crear_producto.php">+ Crear producto</a>
    <br><br>
    <table border="1" cellpadding="10">
        <tr>
            <th>ID</th><th>Nombre</th><th>Precio</th><th>Stock</th><th>Imagen</th><th>Acciones</th>
        </tr>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['nombre'] ?></td>
            <td><?= $row['precio'] ?></td>
            <td><?= $row['stock'] ?></td>
            <td><img src="../web/<?= $row['imagen'] ?>" width="80"></td>
            <td>
                <a href="editar_producto.php?id=<?= $row['id'] ?>">Editar</a> |
                <a href="eliminar_producto.php?id=<?= $row['id'] ?>" 
                   onclick="return confirm('¿Seguro que deseas eliminar este producto?')">Eliminar</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
