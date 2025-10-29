<?php
// backend/agregar_carrito_ajax.php
header('Content-Type: application/json; charset=utf-8');
session_start();
require_once 'funciones_carrito.php';

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['ok'=>false,'msg'=>'No has iniciado sesión']);
    exit;
}

$usuario_id = (int)$_SESSION['usuario_id'];
$producto_id = isset($_POST['producto_id']) ? (int)$_POST['producto_id'] : 0;
$cantidad = isset($_POST['cantidad']) ? (int)$_POST['cantidad'] : 1;

if ($producto_id <= 0 || $cantidad <= 0) {
    echo json_encode(['ok'=>false,'msg'=>'Datos inválidos']);
    exit;
}

$res = agregarAlCarritoBD($usuario_id, $producto_id, $cantidad);
if ($res['ok']) {
    echo json_encode(['ok'=>true,'count'=>$res['count']]);
} else {
    echo json_encode(['ok'=>false,'msg'=>$res['msg'] ?? 'Error']);
}
