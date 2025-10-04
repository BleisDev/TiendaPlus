<?php
session_start();
include("conexion.php");

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../web/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../web/carrito.php");
    exit;
}

$usuario_id = intval($_SESSION['usuario_id']);
$direccion = $conn->real_escape_string($_POST['direccion'] ?? '');
$metodo_pago = $conn->real_escape_string($_POST['metodo_pago'] ?? 'Efectivo');

$carrito = $_SESSION['carrito'] ?? [];
if (empty($carrito)) {
    $_SESSION['msg'] = "Carrito vacío.";
    header("Location: ../web/carrito.php");
    exit;
}

// calcular total
$total = 0;
foreach($carrito as $it) {
    $total += $it['precio'] * $it['cantidad'];
}

// insertar pedido
$stmt = $conn->prepare("INSERT INTO pedidos (usuario_id, total, estado, direccion, metodo_pago, fecha) VALUES (?, ?, 'Pendiente', ?, ?, NOW())");
$stmt->bind_param("idss", $usuario_id, $total, $direccion, $metodo_pago);
if (!$stmt->execute()) {
    $_SESSION['msg'] = "Error creando pedido: " . $stmt->error;
    header("Location: ../web/carrito.php");
    exit;
}

$pedido_id = $stmt->insert_id;

// detalle
$detalle = $conn->prepare("INSERT INTO detalle_pedidos (pedido_id, producto_id, cantidad, precio_unit) VALUES (?, ?, ?, ?)");
foreach($carrito as $it) {
    $pid = intval($it['id']);
    $cant = intval($it['cantidad']);
    $precio = floatval($it['precio']);
    $detalle->bind_param("iiid", $pedido_id, $pid, $cant, $precio);
    $detalle->execute();
}

// limpiar carrito
unset($_SESSION['carrito']);
$_SESSION['msg'] = "Pedido creado correctamente. ID: " . $pedido_id;
header("Location: ../web/confirmacion.php?pedido_id=".$pedido_id);
exit;
