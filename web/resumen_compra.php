<?php
session_start();
include_once('../backend/conexion.php');

$id_usuario = $_SESSION['id_usuario'] ?? 0;
$id_pedido = $_GET['id'] ?? 0;
if (!$id_usuario || !$id_pedido) die("Pedido no válido");

// Obtener pedido
$sql = "SELECT p.id, p.fecha, p.total, u.nombre, u.email
        FROM pedidos p
        JOIN usuarios u ON p.usuario_id = u.id
        WHERE p.id = $id_pedido AND p.usuario_id = $id_usuario";
$pedido = $conn->query($sql)->fetch_assoc();
if (!$pedido) die("Pedido no encontrado");

// Detalle productos
$sql_detalle = "SELECT pr.nombre, dp.cantidad, dp.precio_unitario, dp.cantidad*dp.precio_unitario AS subtotal
                FROM detalle_pedido dp
                JOIN productos pr ON dp.producto_id = pr.id
                WHERE dp.pedido_id = $id_pedido";
$detalles = $conn->query($sql_detalle);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Resumen de Compra #<?= $pedido['id'] ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
<h2>🧾 Resumen de Pedido #<?= $pedido['id'] ?></h2>
<p><strong>Cliente:</strong> <?= htmlspecialchars($pedido['nombre']) ?></p>
<p><strong>Correo:</strong> <?= htmlspecialchars($pedido['email']) ?></p>
<p><strong>Fecha:</strong> <?= $pedido['fecha'] ?></p>

<table class="table table-bordered mt-3">
<thead class="table-dark">
<tr>
<th>Producto</th>
<th>Cantidad</th>
<th>Precio Unitario</th>
<th>Subtotal</th>
</tr>
</thead>
<tbody>
<?php while($fila = $detalles->fetch_assoc()): ?>
<tr>
<td><?= htmlspecialchars($fila['nombre']) ?></td>
<td><?= $fila['cantidad'] ?></td>
<td>$<?= number_format($fila['precio_unitario'],0,',','.') ?></td>
<td>$<?= number_format($fila['subtotal'],0,',','.') ?></td>
</tr>
<?php endwhile; ?>
</tbody>
</table>

<h4 class="text-end">Total: $<?= number_format($pedido['total'],0,',','.') ?></h4>

<a href="factura.php?id=<?= $pedido['id'] ?>" class="btn btn-success">🧾 Descargar Factura PDF</a>
<a href="index.php" class="btn btn-secondary">⬅️ Volver a Tienda</a>
</div>
</body>
</html>
