<?php
session_start();
include("conexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['carrito_id'], $_POST['cantidad'])) {
    $carrito_id = intval($_POST['carrito_id']);
    $cantidad = max(1, intval($_POST['cantidad']));
    $stmt = $conn->prepare("UPDATE carrito SET cantidad = ? WHERE id = ? LIMIT 1");
    $stmt->bind_param("ii", $cantidad, $carrito_id);
    $stmt->execute();
}
header("Location: ../web/carrito.php");
exit;
