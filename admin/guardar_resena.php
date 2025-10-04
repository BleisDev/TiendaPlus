<?php
// backend/guardar_resena.php
session_start();
include("conexion.php"); // $conn (mysqli)

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../web/index.php");
    exit;
}

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../web/login.php");
    exit;
}

$usuario_id = intval($_SESSION['usuario_id']);
$producto_id = intval($_POST['producto_id'] ?? 0);
$comentario = trim($_POST['comentario'] ?? '');
$calificacion = intval($_POST['calificacion'] ?? 5);
if ($calificacion < 1) $calificacion = 1;
if ($calificacion > 5) $calificacion = 5;

if ($producto_id <= 0 || $comentario === '') {
    $_SESSION['msg'] = "Datos de reseña incompletos.";
    header("Location: ../web/perfil.php");
    exit;
}

// (Opcional) verificar que el usuario compró el producto:
// $q = $conn->prepare("SELECT 1 FROM detalle_pedidos dp JOIN pedidos p ON dp.pedido_id = p.id WHERE p.usuario_id = ? AND dp.producto_id = ? LIMIT 1");
// $q->bind_param("ii",$usuario_id,$producto_id); $q->execute(); if ($q->get_result()->num_rows==0) { ... }

$stmt = $conn->prepare("INSERT INTO resenas (usuario_id, producto_id, comentario, calificacion, fecha) VALUES (?, ?, ?, ?, NOW())");
$stmt->bind_param("iisi", $usuario_id, $producto_id, $comentario, $calificacion);
if ($stmt->execute()) {
    $_SESSION['msg'] = "Reseña guardada correctamente.";
    header("Location: ../web/producto.php?id=".$producto_id);
    exit;
} else {
    $_SESSION['msg'] = "Error al guardar reseña: " . $stmt->error;
    header("Location: ../web/perfil.php");
    exit;
}
