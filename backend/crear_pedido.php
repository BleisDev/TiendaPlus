<?php
session_start();
require_once 'conn.php';

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit;
}

// Verificar si el carrito no está vacío
if (empty($_SESSION['carrito'])) {
    echo "El carrito está vacío.";
    exit;
}

try {
    $conn->begin_transaction();

    $usuario_id = $_SESSION['usuario_id'];

    // Calcular el total del pedido
    $total = 0;
    foreach ($_SESSION['carrito'] as $item) {
        $total += $item['cantidad'] * $item['precio'];
    }

    // Insertar en la tabla 'pedidos'
    $stmt_pedido = $conn->prepare("INSERT INTO pedidos (usuario_id, fecha, total, estado) VALUES (?, NOW(), ?, 'pendiente')");
    $stmt_pedido->bind_param("id", $usuario_id, $total);
    $stmt_pedido->execute();

    $pedido_id = $conn->insert_id; // ID del nuevo pedido

    // Insertar en 'detalle_pedido'
    $stmt_detalle = $conn->prepare("INSERT INTO detalle_pedido (pedido_id, producto_id, cantidad, precio_unitario) VALUES (?, ?, ?, ?)");
    foreach ($_SESSION['carrito'] as $producto_id => $item) {
        $cantidad = $item['cantidad'];
        $precio = $item['precio'];

        $
