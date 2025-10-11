<?php
session_start();
include_once('../backend/conexion.php');

// Verificar si el usuario es administrador
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../web/login.php");
    exit;
}

// Verificar si se pasó el ID del pedido
if (!isset($_GET['id'])) {
    echo "<p>⚠️ No se ha seleccionado ningún pedido.</p>";
    exit;
}

$pedido_id = intval($_GET['id']);

// Obtener información del pedido con datos del cliente
$sql_pedido = "
    SELECT p.id, p.fecha, p.total, p.estado,
           u.nombre AS cliente, u.email, u.telefono
    FROM pedidos p
    INNER JOIN usuarios u ON p.usuario_id = u.id
    WHERE p.id = $pedido_id
";
$res_pedido = $conn->query($sql_pedido);

if ($res_pedido->num_rows == 0) {
    echo "<p>❌ No se encontró el pedido solicitado.</p>";
    exit;
}

$pedido = $res_pedido->fetch_assoc();

// Obtener detalle de productos del pedido
$sql_detalle = "
    SELECT pr.nombre, dp.cantidad, dp.precio_unitario,
           (dp.cantidad * dp.precio_unitario) AS subtotal
    FROM detalle_pedido dp
    INNER JOIN productos pr ON dp.producto_id = pr.id
    WHERE dp.pedido_id = $pedido_id
";
$res_detalle = $conn->query($sql_detalle);

$total = 0;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>🧾 Detalle Pedido #<?= $pedido['id'] ?> - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; font-family: Arial, sans-serif; padding: 40px; }
        .container { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        h2 { color: #222; font-weight: bold; margin-bottom: 25px; }
        .info { background: #eef2f7; padding: 15px; border-radius: 10px; margin-bottom: 20px; }
        .info p { margin: 4px 0; }
        .total { text-align: right; font-weight: bold; font-size: 1.2em; margin-top: 15px; }
        .btns { margin-top: 25px; display: flex; gap: 15px; }
    </style>
</head>
<body>
<div class="container">
    <h2>🧾 Detalle del Pedido #<?= $pedido['id'] ?></h2>

    <div class="info">
        <p><strong>Cliente:</strong> <?= htmlspecialchars($pedido['cliente']) ?></p>
        <p><strong>Correo:</strong> <?= htmlspecialchars($pedido['email']) ?></p>
        <p><strong>Teléfono:</strong> <?= htmlspecialchars($pedido['telefono']) ?></p>
        <p><strong>Fecha:</strong> <?= $pedido['fecha'] ?></p>
        <p><strong>Estado:</strong> <?= ucfirst($pedido['estado']) ?></p>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($item = $res_detalle->fetch_assoc()): ?>
                <?php $total += $item['subtotal']; ?>
                <tr>
                    <td><?= htmlspecialchars($item['nombre']) ?></td>
                    <td><?= $item['cantidad'] ?></td>
                    <td>$<?= number_format($item['precio_unitario'], 0, ',', '.') ?></td>
                    <td>$<?= number_format($item['subtotal'], 0, ',', '.') ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <p class="total">💰 Total: $<?= number_format($total, 0, ',', '.') ?></p>

    <div class="btns">
        <a href="factura.php?id=<?= $pedido['id'] ?>" class="btn btn-success">📄 Descargar factura</a>
        <a href="dashboard_pedidos_lista.php" class="btn btn-secondary btn-volver">⬅️ Volver a pedidos</a>

    </div>
</div>
</body>
</html>
