<?php
session_start();
include_once('../backend/conexion.php');

// Verificar sesión y carrito
if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
    header("Location: ../web/carrito.php");
    exit;
}

// Datos del cliente
$cliente = $_SESSION['nombre'] ?? 'Cliente';
$email = $_SESSION['email'] ?? '';
$direccion = $_POST['direccion'] ?? '';
$ciudad = $_POST['ciudad'] ?? '';
$metodo_pago = $_POST['metodo_pago'] ?? 'Efectivo';

// Calcular total
$total = 0;
foreach ($_SESSION['carrito'] as $producto) {
    $total += $producto['precio'] * $producto['cantidad'];
}

// Insertar pedido
$stmt = $conn->prepare("INSERT INTO pedidos (cliente, email, direccion, ciudad, metodo_pago, total, estado, fecha)
                        VALUES (?, ?, ?, ?, ?, ?, 'pendiente', NOW())");
$stmt->bind_param("sssssd", $cliente, $email, $direccion, $ciudad, $metodo_pago, $total);
$stmt->execute();
$pedido_id = $stmt->insert_id;

// Insertar detalle del pedido
$stmtDetalle = $conn->prepare("INSERT INTO detalle_pedido (pedido_id, producto_id, cantidad, precio) VALUES (?, ?, ?, ?)");

foreach ($_SESSION['carrito'] as $item) {
    $producto_id = $item['id'] ?? $item['producto_id'] ?? $item['id_producto'] ?? null;
    $cantidad = $item['cantidad'] ?? 1;
    $precio = $item['precio'] ?? 0;

    if ($producto_id) {
        $stmtDetalle->bind_param("iiid", $pedido_id, $producto_id, $cantidad, $precio);
        $stmtDetalle->execute();
    }
}

// Guardar ID de pedido en sesión
$_SESSION['pedido_id'] = $pedido_id;
$_SESSION['total_pedido'] = $total;

// Vaciar carrito
unset($_SESSION['carrito']);

// Redirigir
header("Location: ../web/pedido_exitoso.php");
exit;
?>
