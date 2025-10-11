<?php
session_start();
include_once('conexion.php');

if(!isset($_SESSION['id_usuario']) || !isset($_POST['nombre'])){
    header("Location: ../web/carrito.php");
    exit;
}

$id_usuario = intval($_SESSION['id_usuario']);
$nombre = trim($_POST['nombre']);
$direccion = trim($_POST['direccion']);
$metodo_pago = trim($_POST['metodo_pago']);

// Guardar pedido en 'pedidos'
$stmt = $conn->prepare("
INSERT INTO pedidos (id_usuario, nombre, direccion_envio, metodo_pago, fecha, estado)
VALUES (?, ?, ?, ?, NOW(), 'pendiente')
");
$stmt->bind_param("isss", $id_usuario, $nombre, $direccion, $metodo_pago);
$stmt->execute();
$id_pedido = $stmt->insert_id;
$stmt->close();

// Guardar detalle
$carrito = $conn->query("SELECT c.id_producto, c.cantidad, p.precio 
                         FROM carrito c JOIN productos p ON c.id_producto=p.id 
                         WHERE c.id_usuario=$id_usuario");

$total = 0;
while($item = $carrito->fetch_assoc()){
    $subtotal = $item['precio']*$item['cantidad'];
    $total += $subtotal;

    $stmt2 = $conn->prepare("
        INSERT INTO detalle_pedido (id_pedido, id_producto, cantidad, precio_unitario)
        VALUES (?, ?, ?, ?)
    ");
    $stmt2->bind_param("iiid", $id_pedido, $item['id_producto'], $item['cantidad'], $item['precio']);
    $stmt2->execute();
    $stmt2->close();
}

// Guardar info en sesión para mostrar en pedido_exitoso
$_SESSION['pedido_info'] = [
    'nombre'=>$nombre,
    'direccion'=>$direccion,
    'metodo_pago'=>$metodo_pago,
    'total'=>$total
];

// Limpiar carrito
$conn->query("DELETE FROM carrito WHERE id_usuario=$id_usuario");

// Redirigir a confirmación
header("Location: ../web/pedido_exitoso.php");
exit;
