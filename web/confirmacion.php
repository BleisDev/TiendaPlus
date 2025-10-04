<?php
session_start();
if(empty($_SESSION['carrito'])) {
    header("Location: catalogo.php");
    exit;
}

// Recibir datos del formulario
$nombre   = $_POST['nombre'] ?? '';
$direccion = $_POST['direccion'] ?? '';
$metodo    = $_POST['metodo_pago'] ?? '';

// Calcular total
$total = array_sum(array_column($_SESSION['carrito'], 'precio'));

// Aquí iría el INSERT en la base de datos (pedidos + detalle),
// usando también $nombre, $direccion, $metodo y $total.

// Limpiar carrito
unset($_SESSION['carrito']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Confirmación</title>
  <link rel="stylesheet" href="estilos.css">
</head>
<body>
  <h1 class="titulo">Pedido Confirmado</h1>
  <p><strong>Nombre:</strong> <?= htmlspecialchars($nombre) ?></p>
  <p><strong>Dirección:</strong> <?= htmlspecialchars($direccion) ?></p>
  <p><strong>Método de pago:</strong> <?= htmlspecialchars($metodo) ?></p>
  <p><strong>Total:</strong> $<?= number_format($total,0,',','.') ?></p>
  <a href="catalogo.php" class="btn">Volver al catálogo</a>
</body>
</html>
