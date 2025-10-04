<?php
include("../backend/conexion.php");
$resultado = $conn->query("SELECT * FROM productos");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Productos - Admin</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>
    <h1>Inventario de Productos</h1>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Precio</th>
            <th>Stock</th>
        </tr>
        <?php while($fila = $resultado->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $fila['id']; ?></td>
            <td><?php echo $fila['nombre']; ?></td>
            <td><?php echo $fila['precio']; ?></td>
            <td><?php echo $fila['stock']; ?></td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
