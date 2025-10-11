<?php
session_start();
include_once('../backend/conexion.php');

$rol = $_SESSION['rol'] ?? 'cliente';
$id_pedido = $_GET['id'] ?? 0;

if ($id_pedido == 0) die("❌ Pedido no válido");

// Consulta del pedido
$sql = "SELECT p.id, p.fecha, p.total, u.nombre AS cliente, u.email
        FROM pedidos p
        INNER JOIN usuarios u ON p.usuario_id = u.id
        WHERE p.id = $id_pedido";
$res = $conn->query($sql);
$pedido = $res->fetch_assoc();
if (!$pedido) die("❌ Pedido no encontrado");

// Consulta detalle productos
$sql_detalle = "SELECT pr.nombre, dp.cantidad, dp.precio_unitario, (dp.cantidad*dp.precio_unitario) AS subtotal
                FROM detalle_pedido dp
                INNER JOIN productos pr ON dp.producto_id = pr.id
                WHERE dp.pedido_id = $id_pedido";
$detalles = $conn->query($sql_detalle);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>🧾 Detalle del Pedido #<?= $pedido['id'] ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
    background: #f8f9fa;
    font-family: 'Segoe UI', sans-serif;
}
.container {
    margin-top: 50px;
    background: #fff;
    padding: 35px;
    border-radius: 15px;
    box-shadow: 0 6px 18px rgba(0,0,0,0.1);
}
h2 {
    margin-bottom: 20px;
    color: #333;
    text-align: center;
}
.table th, .table td {
    text-align: center;
    vertical-align: middle;
}
.botones {
    display: flex;
    justify-content: space-between;
    margin-top: 30px;
}
</style>
</head>
<body>

<div class="container">
    <h2>🧾 Detalle del Pedido #<?= $pedido['id'] ?></h2>
    <p><strong>Cliente:</strong> <?= htmlspecialchars($pedido['cliente']) ?></p>
    <p><strong>Correo:</strong> <?= htmlspecialchars($pedido['email']) ?></p>
    <p><strong>Fecha:</strong> <?= $pedido['fecha'] ?></p>

    <table class="table table-bordered mt-4">
        <thead class="table-dark">
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($detalles && $detalles->num_rows > 0): ?>
                <?php while ($fila = $detalles->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($fila['nombre']) ?></td>
                        <td><?= $fila['cantidad'] ?></td>
                        <td>$<?= number_format($fila['precio_unitario'], 0, ',', '.') ?></td>
                        <td>$<?= number_format($fila['subtotal'], 0, ',', '.') ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="4">No hay productos en este pedido.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <h4 class="text-end mt-3"><strong>Total:</strong> $<?= number_format($pedido['total'], 0, ',', '.') ?></h4>

    <div class="botones">
        <a href="http://localhost/TiendaPlus/backend/panel.php?tabla=pedidos" class="btn btn-secondary">
            ⬅️ Volver a pedidos
        </a>
        <a href="factura.php?id=<?= $id_pedido ?>" class="btn btn-success">
            🧾 Descargar factura PDF
        </a>
    </div>
</div>

</body>
</html>

</html>
