<?php
header('Content-Type: application/json');
session_start();
require_once 'conn.php'; // Ajusta la ruta a tu archivo de conexión

try {
    // --- Validar sesión ---
    if (!isset($_SESSION['id_usuario'])) {
        throw new Exception("Usuario no autenticado");
    }

    $usuario_id = $_SESSION['id_usuario'];

    // --- Validar carrito ---
    if (empty($_SESSION['carrito'])) {
        throw new Exception("El carrito está vacío");
    }

    $conn->begin_transaction();

    // --- Calcular total ---
    $total = 0;
    foreach ($_SESSION['carrito'] as $item) {
        $total += $item['cantidad'] * $item['precio'];
    }

    // --- Obtener datos del usuario ---
    $stmtUser = $conn->prepare("SELECT nombre, email FROM usuarios WHERE id=? LIMIT 1");
    $stmtUser->bind_param("i", $usuario_id);
    $stmtUser->execute();
    $resUser = $stmtUser->get_result();
    $usuario = $resUser->fetch_assoc();
    if (!$usuario) throw new Exception("Usuario no encontrado");

    $nombre_cliente = $usuario['nombre'];
    $email_cliente  = $usuario['email'];

    // --- Insertar pedido ---
    $stmtPedido = $conn->prepare("
        INSERT INTO pedidos (usuario_id, cliente, email, fecha, total, estado)
        VALUES (?, ?, ?, NOW(), ?, 'pendiente')
    ");
    $stmtPedido->bind_param("issd", $usuario_id, $nombre_cliente, $email_cliente, $total);
    $stmtPedido->execute();
    $pedido_id = $conn->insert_id;

    // --- Insertar detalle del pedido ---
    $stmtDetalle = $conn->prepare("
        INSERT INTO detalle_pedido (pedido_id, producto_id, cantidad, precio_unitario)
        VALUES (?, ?, ?, ?)
    ");

    foreach ($_SESSION['carrito'] as $producto_id => $item) {
        $cantidad = $item['cantidad'];
        $precio   = $item['precio'];
        $stmtDetalle->bind_param("iiid", $pedido_id, $producto_id, $cantidad, $precio);
        $stmtDetalle->execute();
    }

    // --- Limpiar carrito ---
    unset($_SESSION['carrito']);
    $stmtDelete = $conn->prepare("DELETE FROM carrito WHERE usuario_id=?");
    $stmtDelete->bind_param("i", $usuario_id);
    $stmtDelete->execute();

    $conn->commit();

    echo json_encode([
        "ok" => true,
        "pedido_id" => $pedido_id
    ]);
    exit;

} catch (Exception $e) {
    $conn->rollback();
    echo json_encode([
        "ok" => false,
        "error" => $e->getMessage()
    ]);
    exit;
}
