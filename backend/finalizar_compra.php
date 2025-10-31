<?php
// 🔹 Mostrar errores (para depurar)
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once('../backend/conexion.php');

// --- Verificar sesión y carrito ---
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../web/login.php");
    exit;
}

if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
    header("Location: ../web/carrito.php");
    exit;
}

// --- Obtener datos del usuario ---
$usuario_id = intval($_SESSION['usuario_id']);
$stmt = $conn->prepare("SELECT nombre, email FROM usuarios WHERE id=?");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();
$stmt->close();

// --- Obtener datos del formulario de checkout ---
$direccion = $_POST['direccion'] ?? 'No especificada';
$ciudad = $_POST['ciudad'] ?? 'No especificada';
$metodo_pago = $_POST['metodo_pago'] ?? 'Pendiente';

// --- Calcular total ---
$total = 0;
foreach ($_SESSION['carrito'] as $item) {
    if (isset($item['precio'], $item['cantidad'])) {
        $total += $item['precio'] * $item['cantidad'];
    }
}
$total = floatval($total);

// --- Insertar pedido ---
$stmt = $conn->prepare("
    INSERT INTO pedidos (usuario_id, total, estado, cliente, email, direccion, ciudad, metodo_pago, fecha)
    VALUES (?, ?, 'pendiente', ?, ?, ?, ?, ?, NOW())
");
$stmt->bind_param(
    "idsssss",
    $usuario_id,
    $total,
    $usuario['nombre'],
    $usuario['email'],
    $direccion,
    $ciudad,
    $metodo_pago
);
$stmt->execute();
$pedido_id = $stmt->insert_id;
$stmt->close();

// --- Insertar detalles del pedido ---
foreach ($_SESSION['carrito'] as $item) {
    if (!isset($item['id'], $item['cantidad'], $item['precio'])) continue;

    $producto_id = intval($item['id']);
    $cantidad = intval($item['cantidad']);
    $precio = floatval($item['precio']);

    $stmt_det = $conn->prepare("
        INSERT INTO detalle_pedido (pedido_id, producto_id, cantidad, precio)
        VALUES (?, ?, ?, ?)
    ");
    $stmt_det->bind_param("iiid", $pedido_id, $producto_id, $cantidad, $precio);
    $stmt_det->execute();
    $stmt_det->close();
}

// --- Vaciar carrito ---
unset($_SESSION['carrito']);

// --- Redirigir a factura ---
header("Location: ../backend/factura.php?pedido_id=" . $pedido_id);
exit;
?>
