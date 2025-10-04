<?php
session_start();
include("conexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['carrito_id'])) {
    $carrito_id = intval($_POST['carrito_id']);
    $stmt = $conn->prepare("DELETE FROM carrito WHERE id = ? LIMIT 1");
    $stmt->bind_param("i", $carrito_id);
    $stmt->execute();
}
header("Location: ../web/carrito.php");
exit;
