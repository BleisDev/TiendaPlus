<?php
include("../backend/conexion.php");

$resultado = $conexion->query("SELECT * FROM categorias");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Categorías</title>
    <link rel="stylesheet" href="../web/estilos.css">
</head>
<body>
    <h1>📁 Categorías</h1>
    <a href="crear_categoria.php"> Nueva Categoría</a>
    <table border="1"
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Acciones</th>
        </tr>
        <?php while ($fila = $resultado->fetch_assoc()) { ?>
        <tr>
            <td><?= $fila['id'] ?></td>
            <td><?= $fila['nombre'] ?></td>
            <td><?= $fila['descripcion'] ?></td>
            <td>
                <a href="editar_categoria.php?id=<?= $fila['id'] ?>">✏️ Editar</a>
                <a href="eliminar_categoria.php?id=<?= $fila['id'] ?>">🗑️ Eliminar</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
