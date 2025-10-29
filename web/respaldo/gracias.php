<?php
session_start();
include_once('../backend/conexion.php');

// Si no hay ID de pedido, redirigir al inicio
if (!isset($_GET['pedido_id'])) {
    header('Location: index.php');
    exit;
}

$pedido_id = intval($_GET['pedido_id']);

// Obtener datos del pedido
$sqlPedido = $conn->prepare("SELECT p.id, p.fecha, p.total, u.nombre 
                             FROM pedidos p
                             JOIN usuarios u ON p.usuario_id = u.id
                             WHERE p.id = ?");
$sqlPedido->bind_param("i", $pedido_id);
$sqlPedido->execute();
$resultPedido = $sqlPedido->get_result();
$pedido = $resultPedido->fetch_assoc();

// Obtener productos comprados
$sqlDetalle = $conn->prepare("SELECT d.cantidad, d.precio_unitario, pr.nombre 
                              FROM detalle_pedido d
                              JOIN productos pr ON d.producto_id = pr.id
                              WHERE d.pedido_id = ?");
$sqlDetalle->bind_param("i", $pedido_id);
$sqlDetalle->execute();
$resultDetalle = $sqlDetalle->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Gracias por tu compra - Tienda Plus</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background:#fff5f8; font-family:'Poppins',sans-serif; }
.container { max-width:700px; margin-top:60px; }
.card { border-radius:15px; padding:30px; box-shadow:0 0 10px rgba(0,0,0,0.1); }
.btn-pink { background:#f08db2; color:white; font-weight:700; border-radius:30px; padding:10px 20px; }
</style>
</head>
<body>
<div class="container">
  <div class="card text-center">
    <h1 class="mb-4 text-success">🎉 ¡Gracias por tu compra!</h1>
    <p><strong>Pedido N°:</strong> <?= $pedido['id'] ?></p>
    <p><strong>Cliente:</strong> <?= htmlspecialchars($pedido['nombre']) ?></p>
    <p><strong>Fecha:</strong> <?= $pedido['fecha'] ?></p>
    <hr>
    <h4>Resumen de tu pedido:</h4>
    <table class="table table-bordered mt-3">
      <thead class="table-light">
        <tr>
          <th>Producto</th>
          <th>Cantidad</th>
          <th>Precio Unitario</th>
          <th>Subtotal</th>
        </tr>
      </thead>
      <tbody>
      <?php while($detalle = $resultDetalle->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($detalle['nombre']) ?></td>
          <td><?= $detalle['cantidad'] ?></td>
          <td>$<?= number_format($detalle['precio_unitario'], 0, ',', '.') ?></td>
          <td>$<?= number_format($detalle['precio_unitario'] * $detalle['cantidad'], 0, ',', '.') ?></td>
        </tr>
      <?php endwhile; ?>
      </tbody>
    </table>
    <h4 class="mt-3 text-end">Total: $<?= number_format($pedido['total'], 0, ',', '.') ?></h4>
    <hr>
    <a href="index.php" class="btn btn-pink mt-3">Volver al inicio</a>
  </div>
</div>
</body>
</html>
