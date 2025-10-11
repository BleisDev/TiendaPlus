<?php
require_once '../backend/conn.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pedido_id = intval($_POST['pedido_id']);
    $nuevo_estado = $_POST['estado'];

    // Validar estado
    $estados_validos = ['pendiente', 'enviado', 'entregado', 'cancelado'];
    if (!in_array($nuevo_estado, $estados_validos)) {
        die("Estado no válido");
    }

    // Actualizar en base de datos
    $stmt = $conn->prepare("UPDATE pedidos SET estado = ? WHERE id = ?");
    $stmt->bind_param("si", $nuevo_estado, $pedido_id);
    if ($stmt->execute()) {
        header("Location: dashboard_pedidos.php?id=" . $pedido_id);
        exit;
    } else {
        echo "❌ Error al actualizar estado.";
    }
}
?>
