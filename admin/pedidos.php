<?php
include("../backend/conexion.php");
$resultado = $conn->query("SELECT * FROM pedidos");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pedidos - Admin</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>
    <h1>Pedidos Realizados</h1>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>ID Usuario</th>
            <th>Total</th>
            <th>Estado</th>
        </tr>
        <?php while($fila = $resultado->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $fila['id']; ?></td>
            <td><?php echo $fila['usuario_id']; ?></td>
            <td><?php echo $fila['total']; ?></td>
            <td><?php echo $fila['estado']; ?></td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
