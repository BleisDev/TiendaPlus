<?php
session_start();
include("conexion.php");

$accion = $_POST['accion'] ?? '';
$id = intval($_POST['producto_id'] ?? 0);
$cantidad = intval($_POST['cantidad'] ?? 1);

if ($accion == 'agregar' && $id>0) {
    // aumentar en sesión
    if(!isset($_SESSION['carrito'])) $_SESSION['carrito']=[];
    if(isset($_SESSION['carrito'][$id])) $_SESSION['carrito'][$id]['cantidad'] += $cantidad;
    else {
        $p = $conn->query("SELECT * FROM productos WHERE id=$id")->fetch_assoc();
        $p['cantidad'] = $cantidad;
        $_SESSION['carrito'][$id] = $p;
    }
}

if ($accion == 'actualizar' && $id>0) {
    if(isset($_SESSION['carrito'][$id])) $_SESSION['carrito'][$id]['cantidad'] = max(1,$cantidad);
}

if ($accion == 'eliminar' && $id>0) {
    unset($_SESSION['carrito'][$id]);
}

// opcional: si usuario logueado sincronizar a tabla carrito
header("Location: ../web/carrito.php");
exit;
