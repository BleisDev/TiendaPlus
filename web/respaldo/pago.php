<?php
session_start();
include_once('../backend/conexion.php');

$usuario_id = $_SESSION['usuario_id'] ?? 0;
if (!$usuario_id) {
    header("Location: login.php");
    exit;
}

// Obtener total del carrito
$sql = "SELECT SUM(p.precio * c.cantidad) as total
        FROM carrito c
        JOIN productos p ON c.producto_id = p.id
        WHERE c.usuario_id = $usuario_id";
$total = $conn->query($sql)->fetch_assoc()['total'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Crear pedido (tabla maestro)
    $conn->query("INSERT INTO pedidos (usuario_id, fecha, total) VALUES ($usuario_id, NOW(), $total)");
    $id_pedido = $conn->insert_id;

    // Insertar detalles
    $sql_detalle = "SELECT producto_id, cantidad, precio FROM carrito WHERE usuario_id = $usuario_id";
    $res = $conn->query($sql_detalle);
    while($fila = $res->fetch_assoc()) {
        $conn->query("INSERT INTO detalle_pedido (pedido_id, producto_id, cantidad, precio_unitario)
                      VALUES ($id_pedido, {$fila['producto_id']}, {$fila['cantidad']}, {$fila['precio']})");
    }

    // Vaciar carrito
    $conn->query("DELETE FROM carrito WHERE usuario_id = $usuario_id");

    // Redirigir al resumen
    header("Location: resumen_compra.php?id=$id_pedido");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Confirmar Compra</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
<h2>💳 Confirmar Compra</h2>
<p>Total a pagar: <strong>$<?= number_format($total,0,',','.') ?></strong></p>
<form method="post">
<button type="submit" class="btn btn-success">Generar Venta</button>
<a href="carrito.php" class="btn btn-secondary">Volver al carrito</a>
</form>
</div>
</body>
</html>
