<?php
session_start();
$id = intval($_GET['id'] ?? 0);
if($id && isset($_SESSION['carrito'][$id])) unset($_SESSION['carrito'][$id]);
header("Location: ../web/carrito.php");
exit;
